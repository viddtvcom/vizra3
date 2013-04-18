<?php
define('_VLC_HOSTNAME', $_SERVER['SERVER_NAME']);
define('_P', $_GET['p']);
define('_MOD', $_GET['mod']);

define(VVERSION, '3.1.3'); // for licensing
require('config/config.php');

if (DEBUG == true) {
    error_reporting(E_ALL ^ E_NOTICE);
    @ini_set('display_errors', 'on');
} else {
    error_reporting(E_ALL ^ (E_NOTICE ^ E_WARNING));
}


/* Correct Apache charset */
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

$config['BASE_PATH'] = str_replace("engine", "", realpath(dirname(__FILE__)));
$config['LIB_DIR'] = $config['BASE_PATH'] . 'engine' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;

set_include_path(get_include_path() . PATH_SEPARATOR . $config['LIB_DIR']);
set_include_path(get_include_path() . PATH_SEPARATOR . $config['LIB_DIR'] . 'classes');
set_include_path(get_include_path() . PATH_SEPARATOR . $config['LIB_DIR'] . 'modules');
set_include_path(get_include_path() . PATH_SEPARATOR . $config['LIB_DIR'] . 'functions');
set_include_path(get_include_path() . PATH_SEPARATOR . $config['LIB_DIR'] . 'variables');

$config['DATE_FORMAT'] = "%d-%m-%Y";
$config['DATETIME_FORMAT'] = "%d.%B.%y %H:%M";

$config['UPLOADS_DIR'] = $config['BASE_PATH'] . 'uploads' . DIRECTORY_SEPARATOR;
$config['DOWNLOADS_DIR'] = $config['BASE_PATH'] . 'uploads' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
$config['LOGS_DIR'] = $config['BASE_PATH'] . 'logs' . DIRECTORY_SEPARATOR;
$config['TMP_DIR'] = $config['BASE_PATH'] . 'tmp' . DIRECTORY_SEPARATOR;

$config['LANG'] = 'Turkish';

$protocol = (isset($_SERVER["HTTPS"]) && strtolower($_SERVER['HTTPS']) == 'on') ? "https://" : "http://";

$config['HTTP_HOST'] = $protocol . $_SERVER['SERVER_NAME'] . '/'; /// ????? cli error

if (BASEDIR !== '') {
    $config['HTTP_HOST'] .= BASEDIR . '/';
}

require_once("var.common.php");
require_once("func.common.php");
require_once("func.smarty.php");
require_once("class.module.php");


$db = new DB();
$db->vconnect();


session_start();

define('MAIN_CUR_ID', getSetting('main_cur_id'));
$config["CURTABLE"] = core::getCurrencies();


if ($_POST) {
    $_post = sanitize($_POST, true, true);
}
$_get = sanitize($_GET, true);


function __autoload($className)
{
    global $config;
    if ($className == 'DomContact') {
        $className = 'contact';
    }
    if (! class_exists($className, false)) {
        if (! file_exists($config['LIB_DIR'] . 'classes/class.' . strtolower($className) . '.php')) {
            return false;
        }
        require_once($config['LIB_DIR'] . 'classes/class.' . strtolower($className) . '.php');
    }
}



