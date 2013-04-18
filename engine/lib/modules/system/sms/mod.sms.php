<?php

class mod_sms extends SystemModule
{

    function send($text, $to)
    {
        // return if module is not active
        if ($this->get('status') != 'active') {
            return false;
        }
        $gw = $this->get('gateway');

        // check if gateway method exists
        $method = 'gw_' . $gw;
        if (! method_exists($this, $method)) {
            return false;
        }

        $this->debug = $this->get('debug') == '1' ? true : false;

        $ret = $this->$method(antiInternational($text), $to);
        return $ret;
    }

    function gw_clickatell($text, $to)
    {
        $baseurl = "http://api.clickatell.com";
        $text = urlencode($text);

        // auth call
        $url = $baseurl . '/http/auth?user=' . $this->get('username') . '&password=' . $this->get(
            'password'
        ) . '&api_id=' . $this->get('param1');
        if ($this->debug) {
            vzrlog(str_replace($this->get('password'), '*******', $url), 'info', 'Clickatell(auth)');
        }
        // do auth call
        $ret = core::curlpost($url);
        // split our response. return string is on first line of the data returned
        $sess = split(":", $ret['data']);
        if ($sess[0] == "OK") {
            $sess_id = trim($sess[1]); // remove any whitespace
            $cmd = $baseurl . '/http/sendmsg?session_id=' . $sess_id . '&to=' . $to . '&text=' . $text;
            $originator = $this->get('originator');
            if ($originator != '') {
                $cmd .= '&req_feat=16&from=' . $originator;
            }
            // do sendmsg call
            $ret = core::curlpost($cmd);
            if ($this->debug) {
                vzrlog($cmd, 'info', 'Clickatell(request)');
                vzrlog($ret['data'], 'info', 'Clickatell(response)');
            }
            $send = split(":", $ret['data']);

            if ($send[0] == "ID") {
                return true;
            } else {
                vzrlog(
                    'SMS gönderme hatası, No:<' . $to . '> Mesaj:<' . $text . '> Hata: <' . $send[1] . '>',
                    'error',
                    'Clickatell'
                );
                return false;
            }
        } else {
            vzrlog('kullanıcı/şifre hatası: ' . $ret['data'], 'error', 'Clickatell');
        }

    }

    function gw_smsalsat($mesaj, $gsm)
    {
        $user = $this->get('username');
        $pass = $this->get('password');
        $origin = $this->get('originator');
        $gsm = ltrim($gsm, '+90');
        $kanal = ($origin != '') ? 1 : 2;

        $url = "http://www.smsalsat.com/sms/smsapi.php";
        $veri = "user=" . $user . "&pass=" . $pass . "&kanal=" . $kanal . "&na=1&secim=0&origin=" . $origin . "&mesaj=" . $mesaj . "&numaralar=" . $gsm;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $veri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        if ($this->debug) {
            vzrlog(str_replace($this->get('password'), '*******', $veri), 'info', 'SmsAlSat(request)');
            vzrlog($content, 'info', 'SmsAlSat(response)');
        }
        if (preg_match('/@/', $content)) {
            //gönderim başarılı
            $sonuc = explode("@", $content);
            $sonuc_kredi = $sonuc[0];
            $sonuc_rapor = $sonuc[1];
            //echo "gönderim yapıldı ve ".$sonuc_kredi." kredi düşüldü";
            return true;
        } else {
            //gönderim başarısız
            //echo "bir hata oluştu : ".$content;
            vzrlog(
                'SMS gönderme hatası, No:<' . $gsm . '> Mesaj:<' . $mesaj . '> Hata: <' . $send[1] . '>',
                'error',
                'SmsAlSat'
            );
            return false;
        }
    }

    function gw_atlassms($mesaj, $gsm)
    {
        $user = $this->get('username');
        $pass = $this->get('password');
        $origin = $this->get('originator');
        $gsm = ltrim($gsm, '+90');

        $url = "http://77.245.145.69/api_v1/?content=get_post&";
        $veri = "user=" . $user . "&pass=" . $pass;
        $veri .= "&msg=" . urlencode($mesaj) . "&numbers=" . $gsm . "&flash=0&date=&send_id=";
        if ($origin != '') {
            $veri .= "&sender_id=" . $origin . "&type=send_sms";
        } else {
            $veri .= "&type=send_sms_non_senderid";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $veri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);

        $xml = new XmlToArray($content);
        $arr = $xml->createArray();

        if ($this->debug) {
            vzrlog(str_replace($this->get('password'), '*******', $veri), 'info', 'AtlasSMS (request)');
            vzrlog($content, 'info', 'AtlasSMS (response)');
        }

        if (isset($arr['status']['error'])) {
            $st = explode('|', $arr['status']['error']);
            if ($st[0] == '00') {
                return true;
            }
            // gönderim başarısız
            vzrlog(
                'SMS gönderme hatası, No:<' . $gsm . '> Mesaj:<' . $mesaj . '> Hata: <' . $arr['status']['error'] . '>',
                'error',
                'AtlasSMS'
            );
            return false;
        } else {
            // bağlantı başarısız
            vzrlog('SMS bağlantı hatası, sunucu cevap vermiyor', 'error', 'AtlasSMS');
            return false;
        }
    }

    function gw_toplusmsyolla($mesaj, $gsm)
    {
        $user = $this->get('username');
        $pass = $this->get('password');
        $origin = $this->get('originator');
        $gsm = ltrim($gsm, '+90');

        $url = "http://www.toplusmsyolla.com/smsgonder.php";
        $url .= "?kul_ad=" . $user . "&sifre=" . $pass . "&gonderen=" . urlencode($origin) . "&mesaj=" . urlencode(
            $mesaj
        ) . "&cepteller=" . $gsm;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        if ($this->debug) {
            vzrlog(str_replace($this->get('password'), '*******', $url), 'info', 'TopluSmsYolla(request)');
            vzrlog($content, 'info', 'TopluSmsYolla(response)');
        }
        $result = explode(':', trim(strip_tags($content)));
        if ($result[0] == 1) {
            return true;
        } else {
            //gönderim başarısız
            //echo "bir hata oluştu : ".$content;
            vzrlog(
                'SMS gönderme hatası, No:<' . $gsm . '> Mesaj:<' . $mesaj . '> Hata: <' . $result[1] . '>',
                'error',
                'TopluSmsYolla'
            );
            return false;
        }
    }

    function gw_pusulasms($mesaj, $gsm)
    {
        $adres = "http://api.pusulasms.com/toplusms.asp";
        $user = $this->get('username');
        $pass = $this->get('password');
        $kimden = $this->get('originator');
        $gsm = str_replace('+90', '', $gsm);

        $mesaj = str_replace("\'", "'", $mesaj);
        $mesaj = str_replace('\"', '"', $mesaj);

        $mesaja = urlencode($mesaj);
        $kimden = urlencode($kimden);

        $fields = "kullanici=" . $user . "&parola=" . $pass . "&gonderen=" . $kimden . "&mesaj=" . $mesaja . "&telefonlar=" . $gsm . "&tarife=9";

        // CURL Kütüphanesi ile $adres değişkeninden geri gelen değeri alalım
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $adres);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        // $cevap değişkenine PusulaSMS API'sinden gelen cevabı yükleyelim.
        $cevap = curl_exec($ch);
        curl_close($ch);

        $kod = substr($cevap, 0, 4);


        if ($kod != '3409') {
            $error = ($kod == '3407') ? "Kullanıcı adı ya da şifreniz hatalı. Lütfen kontrol ediniz." : $cevap;
            vzrlog(
                'SMS gönderme hatası, No:<' . $gsm . '> Mesaj:<' . $mesaj . '> Hata: <' . $error . '>',
                'error',
                'Pusula SMS'
            );
            return false;
        } else {
            return true;
        }

    }


} // end of class
