<?php
require('../engine/init.php');
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/acp');
require_once("func.admin.php");
secure();


$sourcePath = $config["UPLOADS_DIR"] . $_GET['t'] . DIRECTORY_SEPARATOR . $_GET['f'];
require_once($config['LIB_DIR'] . '3rdparty' . DIRECTORY_SEPARATOR . 'thumbnailer' . DIRECTORY_SEPARATOR . 'ThumbLib.inc.php');

if (! file_exists($sourcePath)) {
    $sourcePath = $config["UPLOADS_DIR"] . $_GET['t'] . DIRECTORY_SEPARATOR . 'default.gif';
}
$thumb = PhpThumbFactory::create($sourcePath);
$thumb->adaptiveResize($_GET['w'], $_GET['h']);
$thumb->show();    


