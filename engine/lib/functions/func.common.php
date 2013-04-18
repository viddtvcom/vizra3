<?php

function loadLangFile($lang = "tr", $type)
{
    global $config;
    if (! preg_match('/^[A-Za-z]{1,20}$/', $lang)) {
        return false;
    }

    $LANG_DIR = $config['BASE_PATH'] . 'engine' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
    $lines = file($LANG_DIR . $lang . DIRECTORY_SEPARATOR . 'common.lang');
    if ($type != 'common') {
        $lines = array_merge(file($LANG_DIR . $lang . DIRECTORY_SEPARATOR . $type . '.lang'), $lines);
    }


    foreach ($lines as $l) {
        if (trim($l) == "") {
            continue;
        } // empty lines
        if (preg_match('/\[(.+?)\]/', $l, $matches)) {
            $section = $matches[1];
            continue;
        }
        $l = explode("=", $l);
        $ret[$section][trim($l[0])] = trim($l[1]);
    }
    return $ret;
}

function lang($key)
{
    global $lang;
    if (preg_match('/(.+?)\%(.+)*/', $key, $matches)) {
        $section = $matches[1];
        $key = $matches[2];
    } else {
        $section = 'General';
    }
    return ($lang[$section][$key] == "") ? "$key" : $lang[$section][$key];
}

function get_cron_key()
{
    return substr(sha1(ENCRYPT_SALT), 0, 9);
}

function generateID($table, $field)
{
    global $db;
    $ID = rand(1000000, 9999999);
    $check = $db->query("SELECT $field FROM $table WHERE $field = " . $ID, SQL_INIT, $field);
    if ($check) {
        return generateID($table, $field);
    } else {
        return $ID;
    }
}

function sanitize($input, $hard = true, $mysql = true)
{
    if (is_array($input)) {
        foreach ($input as $var => $val) {
            $output[$var] = sanitize($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        if ($hard) {
            $input = cleanInput($input);
        }
        if ($mysql) {
            $input = mysql_real_escape_string($input);
        }
        $output = $input;
    }
    return $output;
}

function cleanInput($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
    );
    $output = preg_replace($search, '', $input);
    return $output;
}

/**
 * Adds date to linux timestamp
 *
 * @param mixed $base Original Timestamp
 * @param mixed $amount Days,Months or Years to add
 * @param mixed $type d=>Day, m=>Month, y=>Year
 * @return int (timestamp)
 */
function addDate($base, $amount, $type)
{
    $d = $m = $y = 0;
    if ($type == "m") {
        $m_add = $amount;
    } elseif ($type == "d") {
        $d_add = $amount;
    } elseif ($type == "y") {
        $y_add = $amount;
    }

    $d = date('j', $base) + $d_add;
    $m = date('n', $base) + $m_add;
    $y = date('Y', $base) + $y_add;
    $time = mktime(0, 0, 0, $m, $d, $y);
    return $time;
}

/**
 * Format date to a string
 *
 * @param mixed $date
 * @param mixed $mode date/datetime
 * @param mixed $type short/long
 */
function formatDate($date, $mode = "", $type = "")
{
    if (! $date || ! is_numeric($date)) {
        return "- -";
    }
    global $arr_months, $arr_months_short;
    $arr = ($type == "short") ? $arr_months_short : $arr_months;

    $day = date("j", $date);
    $month = $arr[date("n", $date)];
    $year = date("Y", $date);
    $time = date("H:i", $date);

    if ($mode == "datetime") {
        return "$day $month $year $time";
    } else {
        return "$day $month $year";
    }
}

function format_number($number, $precision, $round = true)
{
    if ($round) {
        return number_format($number, $precision);
    }
    $num = explode('.', $number);
    $num = $num[0] . '.' . substr($num[1], 0, $precision);
    if (! (float)$num) {
        $num = '0.00';
    }
    return $num;
}

function formatFilesize($size)
{
    $size = $size / 1024;
    if ($size > 1024) {
        $size /= 1024;
        $unit = 'MB';
    } else {
        $unit = 'KB';
    }

    return (int)$size . ' ' . $unit;
}

function array2select($arr, $select_field, $value_field, $text_field, $selected = "", $extra = "")
{
    $options = array2options($arr, $value_field, $text_field, $selected);
    return "<select name='$select_field' id='$select_field'>" . $extra . $options . "</select>";
}

function array2options($arr, $value_field, $text_field, $selected)
{
    foreach ((array)$arr as $k => $a) {
        if ($value_field == "") {
            $value = $a;
        } else {
            $value = ($value_field == - 1) ? $k : $a["$value_field"];
        }
        $text = ($text_field != "") ? $a["$text_field"] : $a;
        $ret .= "<option value='$value'";
        if ($selected != "" && $selected == $value) {
            $ret .= " selected";
        }
        $ret .= ">$text</option>";
    }
    return $ret;
}

function moduleConfig($label, $type, $size = "", $description = "")
{
    return array("label" => $label, "type" => $type, "size" => $size, "description" => $description);
}

function convertCurrency($targetCurID, $curID, $amount)
{
    global $config;
    $new = $amount * $config['CURTABLE'][$curID]['ratio'] / $config['CURTABLE'][$targetCurID]['ratio'];
    return round($new, 2);
}

function displayPrice($price, $period, $paycurID, $type = 1)
{
    global $vars, $config;
    $str = number_format($price, 2) . ' ' . $config['CURTABLE'][$paycurID]['symbol'];
    if ($type == 1) {
        $str .= ' - ##BILLING_CYCLES_' . $period . '##';
    } elseif ($type == 2) {
        $str .= ' / ' . displayPeriod($period);
    }
    return $str;
}

function displayPeriod($period)
{
    global $vars;
    return '##BILLING_CYCLES_' . $period . '##';
    if ($period >= 12) {
        $str = $period / 12 . " ##year##";
    } else {
        $str = $period . " ##month##";
    }
    return $str;
}

function displayJSON($data)
{
    if (! is_array($data)) {
        return false;
    }
    echo json_encode($data);
    exit;
}

function getCurrencyById($curID)
{
    global $config;
    return $config['CURTABLE'][$curID]['symbol'];
}

function bb2html($text)
{
    //
    $bbcode = array(
        "[list]",
        "[*]",
        "[/list]",
        "[img]",
        "[/img]",
        "[b]",
        "[/b]",
        "[u]",
        "[/u]",
        "[i]",
        "[/i]",
        '[color="',
        "[/color]",
        "[size=\"",
        "[/size]",
        '[url="',
        "[/url]",
        "[mail=\"",
        "[/mail]",
        "[code]",
        "[/code]",
        "[quote]",
        "[/quote]",
        '"]'
    );
    //
    $htmlcode = array(
        "<ul>",
        "<li>",
        "</ul>",
        "<img src=\"",
        "\">",
        "<b>",
        "</b>",
        "<u>",
        "</u>",
        "<i>",
        "</i>",
        "<span style=\"color:",
        "</span>",
        "<span style=\"font-size:",
        "</span>",
        '<a href="',
        "</a>",
        "<a href=\"mailto:",
        "</a>",
        "<table width=100% bgcolor=lightgray><tr><td bgcolor=white><code>",
        "</code></td></tr></table>",
        "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>",
        "</td></tr></table>",
        '">'
    );
    $newtext = str_replace($bbcode, $htmlcode, $text);
    //$newtext = nl2br($newtext);//second pass
    return $newtext;
}


function _linkify($text, $blank = false, $title = '\\0')
{
    if ($blank) {
        $str = " target = '_blank'";
    }
    return preg_replace(
        "/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i",
        "<a $str href=\"\\0\">$title</a>",
        $text
    );
}

function linkify($text, $blank = false, $title = '$3')
{
    if ($blank) {
        $target = " target = '_blank'";
    }
    $text = preg_replace(
        "/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is",
            "$1$2<a $target href=\"$3\" >" . $title . "</a>",
        $text
    );
    $text = preg_replace(
        "/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is",
            "$1$2<a $target href=\"http://$3\" >" . $title . "</a>",
        $text
    );
    $text = preg_replace(
        "/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is",
            "$1$2<a $target href=\"ftp://$3\" >" . $title . "</a>",
        $text
    );
    //$text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a $target href=\"mailto:$2@$3\">$2@$3</a>", $text);  
    return ($text);
}


function redirect($url)
{
    header("location:" . $url);
    exit();
}

function jsredirect($url)
{
    echo '<script> window.location.replace("' . $url . '")</script>';
    exit();
}

function debug($thing, $die = false)
{
    if (is_array($thing) || is_object($thing)) {
        $thing = "<pre>" . print_r($thing, 1) . "</pre>";
    } else {
        $thing = nl2br(htmlspecialchars($thing));
    }
    $str = "<div style='border:3px solid #00FF00; padding:7; margin:3; background-color:#000000; color:#00FF00; font-size:1.2em; width:95%'>";
    $str .= "<br>" . $thing . "<br>";
    $str .= "</div>";
    echo $str;
    if ($die) {
        die();
    }
}

function debugd($thing, $die = false)
{
    if ($_SERVER['REMOTE_ADDR'] == '217.131.133.222') {
        debug($thing, $die);
    }
}

function debugx()
{
    $arg_list = func_get_args();
    foreach ($arg_list as $arg) {
        debug($arg);
    }
}

function debugTrace()
{
    ob_start();
    debug_print_backtrace();
    $d = ob_get_contents();
    ob_end_clean();
    debug(explode("#", $d));
}

function debuglog($line, $filename = "")
{
    global $config;
    if (is_array($line) || is_object($line)) {
        $line = print_r($line, 1);
    }
    $filename = ($filename == "") ? "debug" : $filename;
    $line = date("[j-M-Y H:i] ") . "[" . getenv(REMOTE_ADDR) . "] " . $line . "\n";
    $fp = @fopen($config["LOGS_DIR"] . $filename . ".log", 'a');
    @fputs($fp, $line);
}

/**
 * Logs system messages
 *
 * @param mixed $msg
 * @param mixed $type note/warning/error
 */
function vzrlog($msg, $type = 'info', $label = 'Sys', $orderID = 0)
{
    global $db;
    if ($orderID > 0) {
        $link = ' (<a target="_blank" href="?p=411&orderID=' . $orderID . '">' . Order::newInstance($orderID)->get(
            'title'
        ) . '</a>)';
    }
    if (is_array($msg) || is_object($msg)) {
        $msg = print_r($msg, 1);
    }

    $db->query(
        "INSERT INTO logs_sys (label,type,message,dateAdded) VALUES ('$label','$type','" . htmlspecialchars(
            sanitize($msg, false)
        ) . $link . "',UNIX_TIMESTAMP())"
    );
}

function history($tblName, $id, $event)
{
    global $db;
    $objectID = sha1($tblName . "-" . $id);
    if ($_SESSION["cpUser"]["uid"]) {
        $isadmin = "0";
        $subject = $_SESSION["cpUser"]["username"];
    } else {
        if ($_SESSION["User"]["uid"]) {
            $subject = $_SESSION["User"]["username"];
        } else {
            $subject = "system";
        }
        $isadmin = "1";
    }
    $db->query(
        "INSERT INTO history (objectID,subject,isadmin,event,dateAdded)
                VALUES ('$objectID','$subject','$isadmin','$event',UNIX_TIMESTAMP())"
    );
}

function getSetting($setting, $split = false)
{
    global $db;

    $do_not_cache = array('license_data');

    if (! in_array($setting, $do_not_cache) && PHP_SAPI != "cli") {
        if (! isset($_SESSION["settings_general"][$setting])) {
            $sql = "SELECT value,encrypted FROM settings_general WHERE setting = '" . $setting . "'";
            $_SESSION["settings_general"][$setting] = $db->query($sql, SQL_INIT);
        }
        $val = $_SESSION["settings_general"][$setting];
    } else {
        $val = $db->query("SELECT value,encrypted FROM settings_general WHERE setting = '$setting'", SQL_INIT);
    }
    if (! $val) {
        return false;
    }

    if ($val['encrypted'] == "1") {
        $ret = core::decrypt($val["value"]);
    } else {
        $ret = $val["value"];
    }

    // split if necesarry
    //if ($binary) { $ret = str_split($ret); }

    return $ret;
}

function setSetting($key, $val, $encrypted = '0')
{
    global $db;
    $sql = "INSERT INTO settings_general (setting,value,encrypted) VALUES ('" . $key . "','" . $val . "','" . $encrypted . "')
            ON DUPLICATE KEY UPDATE value = '" . $val . "'";
    $db->query($sql);

    if (PHP_SAPI != "cli") {
        $_SESSION["settings_general"][$key]['value'] = $val;
        $_SESSION["settings_general"][$key]['encrypted'] = $encrypted;
    }
}

function stripDataFromTags($tagStart, $tagEnd, $string)
{
    $start = strlen($tagStart) + strpos($string, $tagStart);
    if ($tagEnd != "") {
        $end = strpos($string, $tagEnd);
    } else {
        $end = str_replace("<", "</", $tagStart);
    }
    $data = substr($string, $start, $end - $start);
    return $data;
}

function getXmlData($tagname, $data)
{
    $taglen = strlen($tagname) + 2;
    $start = strpos($data, "<" . $tagname . ">");
    if ($start === false) {
        return false;
    }
    $end = strpos($data, "</" . $tagname . ">");
    $value = substr($data, $start + $taglen, $end - $start - $taglen);
    return $value;
}

function getParentIdFromGroupId($groupID)
{
    global $db;
    $parentID = $db->query("SELECT parentID FROM service_groups WHERE groupID = " . $groupID, SQL_INIT, 'parentID');
    return $parentID;
}

function paging($sql, $page, $rpp, $IDstr = "")
{
    global $db;
    $page = ($_POST["page"] == 0) ? 1 : $_POST["page"];
    $page = ($_GET["page"] == 0) ? $page : $_GET["page"];
    if (strstr($sql, "DISTINCT")) {
        $start = strlen("SELECT DISTINCT") + strpos($sql, "SELECT DISTINCT");
    } else {
        $start = strlen("SELECT") + strpos($sql, "SELECT");
    }
    $end = strpos($sql, "FROM");
    $data = substr($sql, $start, $end - $start);
    $IDstr = ($IDstr == "") ? "uid" : $IDstr;

    $sql2 = str_replace($data, " count(DISTINCT $IDstr) AS cnt ", $sql);
    $parr["count"] = $db->query($sql2, SQL_INIT, "cnt");
    $parr["total"] = ceil($parr["count"] / $rpp);
    if ($parr["count"] > $rpp) {
        $parr["limit"] = " LIMIT " . ($page - 1) * $rpp . ", " . $rpp;
    }
    $parr["npage"] = ($page == $parr["total"]) ? false : $page + 1;
    $parr["ppage"] = ($page == 1) ? false : $page - 1;
    $parr['cpage'] = ($parr["count"] == 0) ? 0 : $page;

    $parr['start'] = 2;
    $parr['end'] = $parr['total'];
    if ($parr['total'] > 10) {
        $parr['end'] = $parr['cpage'] + 5;
        $parr['start'] = $parr['cpage'] - 5;
        if ($parr['start'] < 2) {
            $parr['end'] -= $parr['start'];
            $parr['start'] = 2;
        }
        if ($parr['end'] > $parr['total']) {
            $parr['start'] += ($parr['total'] - $parr['end']);
            $parr['end'] = $parr['total'];
        }
    }

    return $parr;
}

function getAdminNickFromAdminId($adminID)
{
    $ADM = new Admin();
    $ADM->adminID = $adminID;
    return $ADM->get('adminNick');
}

function forceSSL()
{
    if ($_GET['p'] == 'queue' || PHP_SAPI == 'cli') {
        return false;
    }
    if (! isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') {
        header('Location: https://' . BASEHOST . $_SERVER['REQUEST_URI']);
        exit();
    }
}

function getCountries()
{
    global $db;
    $sql = "SELECT * FROM countries ORDER BY country ASC";
    $countries = $db->query($sql, SQL_ALL);
    return $countries;
}

function getCountry($code, $field = '')
{
    global $db;
    $sql = "SELECT * FROM countries WHERE country_code = '" . $code . "'";
    $country = $db->query($sql, SQL_INIT);
    if ($field != '') {
        return $country[$field];
    }
    return $country;
}


function getDomainExtensions($ext = "all")
{
    global $db;
    if ($ext != "all") {
        $where = "AND de.extension = '$ext'";
    }
    $sql = "SELECT de.*,sc.symbol,s.paycurID FROM domain_extensions de
                INNER JOIN services s ON s.serviceID = de.serviceID 
                INNER JOIN settings_currencies sc ON sc.curID = s.paycurID  
            WHERE de.status = 'active' $where ORDER BY de.rowOrder";
    $tbl = $db->query($sql, SQL_ALL, "extension");
    return $tbl;
}


function array_rekey($array, $xkey)
{
    $data = array();

    foreach ($array as $key => $value) {
        $xvalue = $value[$xkey];
        unset($value[$xkey]);
        $data[$xvalue] = $value;
    }
    return $data;
}

function arrSearch($array, $name)
{
    foreach ($array as $key => $value) {
        if ($array["$key"]["attributeName"] == $name) {
            return $array["$key"]["attributeValue"];
        }
    }

}

function validate_email($email)
{
    return preg_match(
        '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(mobi|aero|coop|info|museum|name))$/',
        $email
    );
}


function strTrueFalse($val)
{
    return ($val) ? 'true' : 'false';
}

function validateAttrs($attrs)
{
    global $db;
    $valid = true;
    if (! is_array($attrs)) {
        return true;
    }
    $keys = array_keys($attrs);
    $regex = $db->query(
        "SELECT setting,validation,validation_info,label FROM service_attr_types WHERE setting IN ('" . implode(
            "','",
            $keys
        ) . "')",
        SQL_KEY,
        'setting'
    );
    foreach ($attrs as $attr => $value) {
        if ($regex[$attr]['validation'] && ! preg_match('/' . $regex[$attr]['validation'] . '/', $value)) {
            /*            debug($regex[$attr]['validation']);
                        debug($value,1); */
            core::raise($regex[$attr]['label'] . ' &raquo; ' . $regex[$attr]['validation_info'], 'e');
            $valid = false;
        }
    }
    return $valid;
}

function getToken($num)
{
    $_SESSION['form_token'][$num] = md5(uniqid(rand(), true));
    return $_SESSION['form_token'][$num];
}

function readable_time($secs, $num_times = 3)
{
    $times = array(
        31536000 => 'sene',
        2592000  => 'ay',
        604800   => 'hafta',
        86400    => 'gün',
        3600     => 'saat',
        60       => 'dk',
        1        => 'sn'
    );
    $count = 0;
    $time = '';
    foreach ($times AS $key => $value) {
        if ($secs >= $key) {
            $s = '';
            $time .= floor($secs / $key);
            $time .= ' ' . $value . $s;
            $count ++;
            $secs = $secs % $key;
            if ($count > $num_times - 1 || $secs == 0) {
                break;
            } else {
                $time .= ', ';
            }
        }
    }
    return $time;
}


function finename($string, $separator = '-')
{
    $string = trim($string);

    $string = antiInternational($string);
    $string = strtolower($string); // convert to lowercase text

    // Only space, letters, numbers and underscore are allowed
    $string = trim(preg_replace("/[^ A-Za-z0-9_]/", " ", $string));

    /*

    "t" (ASCII 9 (0x09)), a tab.
    "n" (ASCII 10 (0x0A)), a new line (line feed).
    "r" (ASCII 13 (0x0D)), a carriage return. 

    */

    //$string = preg_replace("/[ tnr]+/", "-", $string);
    $string = str_replace(" ", $separator, $string);
    $string = preg_replace("/[ -]+/", "-", $string);
    return $string;
}

function antiInternational($string)
{
    $arr1 = array('ö', 'ç', 'ş', 'ı', 'ğ', 'ü', 'Ö', 'Ç', 'Ş', 'İ', 'Ü', 'Ğ');
    $arr2 = array('o', 'c', 's', 'i', 'g', 'u', 'O', 'C', 'S', 'I', 'U', 'G');

    $string = str_replace($arr1, $arr2, $string);
    return $string;
}


function generateCode($length, $mode = 'hard')
{
    return core::generateCode($length, $mode);
}

function randomCode($length, $possible = "123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ")
{
    $str = "";
    while (strlen($str) < $length) {
        $str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
    }
    return ($str);
}

function getLanguages()
{
    global $config;
    $dir = $config['BASE_PATH'] . 'engine' . DIRECTORY_SEPARATOR . 'lang';
    if (file_exists($dir)) {
        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir() && ! $file->isDot()) {
                $name = $file->getFilename();
                if (substr($name, 0, 1) != '.') {
                    $ret[] = $name;
                }
            }
        }
    }
    return $ret;
}


if (! function_exists('json_decode')) {
    function json_decode($content, $assoc = false)
    {
        require_once(dirname(__FILE__) . '/../3rdparty/JSON.php');
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }
}

if (! function_exists('json_encode')) {
    function json_encode($content)
    {
        require_once(dirname(__FILE__) . '/../3rdparty/JSON.php');
        $json = new Services_JSON;
        return $json->encode($content);
    }
}


function accflow_cmp($a, $b)
{
    if ($a['dateAdded'] == $b['dateAdded']) {
        return 0;
    }
    return ($a['dateAdded'] < $b['dateAdded']) ? - 1 : 1;
}




