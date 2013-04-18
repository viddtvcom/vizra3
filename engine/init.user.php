<?php
require('engine/init.php');


set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/client');
require_once("func.user.php");

$config['LANG'] = getSelectedLanguage();
$config['LANGS'] = getLanguages();
$lang = loadLangFile($config['LANG'], 'frontend');

switch ($_get["p"]) {
    case 'queue':
        require_once($config["BASE_PATH"] . "/engine/queue.php");
        exit();
    case 'ajax':
        require("ajax.php");
        exit();
    case 'image':
        require('image.php');
        exit();
}


if ($_SESSION['vclient']->clientID) {
    $st = "'active','clients-only'";
    define('CLIENTID', $_SESSION['vclient']->clientID);
} else {
    $st = "'active'";
}


if ($_post['langsel'] != '') {
    setSelectedLanguage($_post['langsel']);
    redirect($_SERVER['HTTP_REFERER']);
}


$core = new vSmarty("client");

$core->assign('mainCur', $config['CURTABLE'][MAIN_CUR_ID]["symbol"]);
$core->assign('productMenu', getProductMenu());
$core->assign('seo', getSetting('portal_seo'));

// announcements
$core->assign('announcements', getAnnouncements($st));

if ($_SESSION["vclient"]->clientID > 0) {
    $core->assign("Client", $_SESSION["vclient"]);
}
