<?php

class core
{


    static function get_currencies()
    {
        global $db;
        return $db->query("SELECT * FROM settings_currencies WHERE status = 'active'", SQL_ALL);

    }

    static function getCurrencies()
    {
        global $db;
        if ($_SESSION['currencies'] == false) {
            $_SESSION['currencies'] = $db->query(
                "SELECT curID,ratio,symbol,code,status FROM settings_currencies",
                SQL_ALL,
                "curID"
            );
        }
        return $_SESSION['currencies'];
    }

    static function getCurrencyById($curID)
    {
        global $config;
        return $config['CURTABLE'][$curID]['symbol'];
    }

    static function generateCode($length, $mode = "hard")
    {

        if ($mode == "easy") {
            $possible = "123456789abcdefghkmnprstuvyz";
        } else {
            $possible = "123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
        }

        $str = "";
        while (strlen($str) < $length) {
            $str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }
        return ($str);
    }

    static function generatePassword($length, $upper = true, $lower = true, $number = true, $symbol = true)
    {
        require('3rdparty/class.chip_password_generator.php');

        $args = array(
            'length'              => $length,
            'alpha_upper_include' => $upper,
            'alpha_lower_include' => $lower,
            'number_include'      => $number,
            'symbol_include'      => $symbol,
        );
        $object = new chip_password_generator($args);
        $password = $object->get_password();
        return $password;

    }

    static function error($msg)
    {
        debuglog("Hata:" . $msg);
        debug_print_backtrace();
        die("Hata: " . $msg);
    }


    static function array_split($arr, $key)
    {
        foreach ($arr as $k => $v) {
            $id = $v[$key];
            unset($v[$key]);
            $v['actions'] = preg_split('//', $v['actions'], - 1, PREG_SPLIT_NO_EMPTY);
            $ret[$id][] = $v;
        }

        return $ret;
    }

    static function str2time($str_date)
    {
        $d = substr($str_date, 0, 2);
        $m = substr($str_date, 3, 2);
        $y = substr($str_date, - 4);
        return mktime(0, 0, 0, $m, $d, $y);
    }

    static function forceLogin()
    {
        if (! $_SESSION["vclient"]->clientID) {
            redirect("?p=user&s=login");
        }
    }


    static function moveRow($table, $IDstr, $id, $dir)
    {
        global $db;
        $rowOrder = $db->query("SELECT rowOrder FROM $table WHERE $IDstr = '$id'", SQL_INIT, "rowOrder");
        if ($dir == "down") {
            $row = $db->query(
                "SELECT * FROM $table WHERE rowOrder > $rowOrder ORDER BY rowOrder ASC LIMIT 1",
                SQL_INIT
            );
        } else {
            $row = $db->query(
                "SELECT * FROM $table WHERE rowOrder < $rowOrder ORDER BY rowOrder DESC LIMIT 1",
                SQL_INIT
            );
        }
        if ($row && $row['rowOrder']) {
            $db->query("UPDATE $table SET rowOrder = '" . $row["rowOrder"] . "' WHERE $IDstr = $id");
            $db->query("UPDATE $table SET rowOrder = '$rowOrder' WHERE $IDstr = " . $row["$IDstr"]);
        }
    }

    static function iconv($par, $dir = 0)
    {
        $charsets = array("ISO-8859-9", "utf-8");
        if ($dir) {
            $charsets = array_reverse($charsets);
        }
        if (is_array($par)) {
            foreach ($par as $k => $v) {
                $par[$k] = core::iconv($v, $dir);
            }
            return $par;
        } else {
            return iconv($charsets[0], $charsets[1], $par);
        }
    }

    static function raise($msg, $type = 'm', $redirect = '')
    {
        $types = array("e" => "error", "w" => "warning", "m" => "message");
        $_SESSION[$types[$type] . "s"][] = '##Messages%' . $msg . '##';
        if ($redirect == 'rt') {
            $redirect = $_SERVER['HTTP_REFERER'];
        }
        if ($redirect) {
            redirect($redirect);
        }
    }

    static function formatFilename($origname)
    {
        $filename = strtolower(basename($origname));

        $ret['name'] = substr($filename, 0, strrpos($filename, "."));
        $ret['ext'] = substr($filename, strrpos($filename, ".") + 1, strlen($filename) - strrpos($filename, "."));
        $ret['sysname'] = sha1('VZF' . $ret['name'] . date('YmdHis'));

        return $ret;
    }

    static function uploadFile($fieldname, $fileType, $uploaderID)
    {
        global $db, $config;
        if ($_FILES[$fieldname]["size"] > getSetting('tickets_filesize') * 1024 * 1024) {
            return array("st" => false, "msg" => "File too big");

        } else {
            $origname = $filename = strtolower(basename($_FILES[$fieldname]['name']));
            $name = substr($filename, 0, strrpos($filename, "."));
            $ext = substr($filename, strrpos($filename, ".") + 1, strlen($filename) - strrpos($filename, "."));
            $allowed = explode(",", getSetting('tickets_filetypes'));
            if (! in_array($ext, $allowed)) {
                return array("st" => false, "msg" => "Extension not allowed");
            }
            $sysname = md5("VZ" . $name . date('YmdHis')) . '.' . $ext;
            $filepath = $config['UPLOADS_DIR'] . $fileType . DIRECTORY_SEPARATOR . $sysname;
            if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $filepath)) {
                $sql = "INSERT INTO files (fileType,clientID,sysname,origname,ip,dateUploaded)
                    VALUES ('" . $fileType . "','" . $uploaderID . "','" . $sysname . "','" . $origname . "','" . getenv(
                    REMOTE_ADDR
                ) . "',UNIX_TIMESTAMP())";
                $db->query($sql);
                $fileID = $db->lastInsertID();
                if (! $fileID) {
                    return array("st" => "false", "msg" => "DB Error");
                } else {
                    return array("st" => "true", "fileID" => $fileID, "filename" => $origname);
                }
            } else {
                return array("st" => false, "msg" => "Can not move file to" . $filepath);
            }
        }

    }

    static function uploadImage($sourcePath, $destname, $destfolder)
    {
        global $config;
        require_once($config['LIB_DIR'] . '3rdparty' . DIRECTORY_SEPARATOR . 'thumbnailer' . DIRECTORY_SEPARATOR . 'ThumbLib.inc.php');
        $thumb = PhpThumbFactory::create($sourcePath);
        $thumb->save($config['UPLOADS_DIR'] . $destfolder . DIRECTORY_SEPARATOR . $destname);

        return array("st" => true);
    }


    /*
    *   Senders
    */

    static function mailsender($subject, $to, $body, $fromName = '', $fromEmail = '', $cc = '', $bcc = '')
    {
        global $config;
        $fromName = ($fromName == "") ? getSetting('compinfo_name') : $fromName;
        $fromEmail = ($fromEmail == "") ? getSetting('compinfo_email') : $fromEmail;

        if (getSetting("commset_mail_method") == 'phpmail') {
            $ret = mail(
                $to,
                $subject,
                $body,
                "From: $fromName<$fromEmail>\nMIME-Version: 1.0\nContent-Type: text/html; charset=utf-8\n"
            );
            return array('st' => $ret);
        }

        require_once("3rdparty/Swift-4.0.6/lib/swift_required.php");

        $message = Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array($fromEmail => $fromName))
                ->setTo($to)
                ->setBody($body)
                ->setCharset("utf-8")
                ->setContentType("text/html");

        if ($cc) {
            $message->setCc($cc);
        }
        if ($bcc) {
            $message->setBcc($bcc);
        }

        if (getSetting("commset_smtp_ssl") == '1') {
            $transport = Swift_SmtpTransport::newInstance(
                getSetting("commset_smtp_server"),
                getSetting("commset_smtp_port"),
                'ssl'
            );
        } else {
            $transport = Swift_SmtpTransport::newInstance(
                getSetting("commset_smtp_server"),
                getSetting("commset_smtp_port")
            );
        }

        $transport->setUsername(getSetting("commset_smtp_username"))->setPassword(getSetting("commset_smtp_password"));
        $mailer = Swift_Mailer::newInstance($transport);

        $mailer->send($message);
    }

    static function send_mail($subject, $to, $body, $fromName = '', $fromEmail = '', $cc = '', $bcc = '')
    {
        $data = array(
            'subject'   => $subject,
            'to'        => $to,
            'cc'        => $cc,
            'bcc'       => $bcc,
            'body'      => $body,
            'fromName'  => $fromName,
            'fromEmail' => $fromEmail
        );

        Queue::createJob('sendmail')->setParams($data)->update()->start();
    }

    static function send_msn($text, $email, $queue = true)
    {
        if ($queue == true) {
            $params = array('text' => $text, 'email' => $email);
            Queue::createJob('sendmsn')->setParams($params)->update()->start();
            return;
        }

        require('3rdparty/phpmsnclass/msn.class.php');
        $msn = new MSN('', false);

        if (! $msn->connect(getSetting('notify_msnbot_email'), getSetting('notify_msnbot_pass'))) {
            throw new Exception('MSN Network bağlantı hatası: ' . $msn->error);
        }

        $msn->sendMessage($text, array($email));
    }

    static function send_sms($text, $gsm, $queue = true)
    {
        if ($queue == true) {
            $params = array('text' => $text, 'gsm' => $gsm);
            Queue::createJob('sendsms')->setParams($params)->update()->start();
            return;
        }

        $MOD = Module::getInstance('sms');
        $ret = $MOD->send($text, $gsm);

        if ($ret == false) {
            throw new Exception('SMS gönderilemedi (' . $gsm . ')');
        }
    }


    /*
    *   Security Functions
    */

    static function encrypt($text, $key = '')
    {
        $text = trim($text);
        if ($text == '') {
            return '';
        }
        if ($key == '') {
            $key = ENCRYPT_SALT;
        }
        if (ENCLIB == 'mcrypt') {
            return self::encrypt_mcrypt($text, $key);
        } else {
            return self::encrypt_phpseclib($text, $key);
        }
    }

    static function decrypt($text, $key = '')
    {
        $text = trim($text);
        if ($text == '') {
            return '';
        }
        if ($key == '') {
            $key = ENCRYPT_SALT;
        }
        if (ENCLIB == 'mcrypt') {
            return self::decrypt_mcrypt($text, $key);
        } else {
            return self::decrypt_phpseclib($text, $key);
        }
    }

    static function encrypt_mcrypt($text, $key = '')
    {
        if ($text == '') {
            return '';
        }
        if ($key == "") {
            $key = ENCRYPT_SALT;
        }
        return trim(
            base64_encode(
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256,
                    $key,
                    $text,
                    MCRYPT_MODE_ECB,
                    mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
                )
            )
        );
    }

    static function decrypt_mcrypt($text, $key = '')
    {
        if ($text == '') {
            return '';
        }
        if ($key == "") {
            $key = ENCRYPT_SALT;
        }
        return trim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256,
                $key,
                base64_decode($text),
                MCRYPT_MODE_ECB,
                mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
            )
        );
    }

    static function encrypt_phpseclib($text, $key = '')
    {
        if ($text == '') {
            return false;
        }
        require_once(dirname(__FILE__) . '/../3rdparty/phpseclib/AES.php');
        $aes = new Crypt_AES();
        $aes->setKey($key);
        $enctext = base64_encode($aes->encrypt($text));
        return $enctext;
    }

    static function decrypt_phpseclib($text, $key = '')
    {
        if ($text == '') {
            return false;
        }
        require_once(dirname(__FILE__) . '/../3rdparty/phpseclib/AES.php');
        $aes = new Crypt_AES();
        $aes->setKey($key);
        $dectext = $aes->decrypt(base64_decode($text));
        return $dectext;
    }


    static function curlpost($url, $params = array(), $connect_timeout = 15)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        $response = curl_exec($ch);

        if ($response == false) {
            $ret = array('st' => false, 'msg' => curl_error($ch));
        } else {
            $ret = array('st' => true, 'data' => $response);
        }
        curl_close($ch);
        return $ret;
    }



}
