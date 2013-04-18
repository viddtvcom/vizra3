<?php

$sourcePath = $config["UPLOADS_DIR"] . $_get['t'] . DIRECTORY_SEPARATOR . $_get['f'];
require_once($config['LIB_DIR'] . '3rdparty' . DIRECTORY_SEPARATOR . 'thumbnailer' . DIRECTORY_SEPARATOR . 'ThumbLib.inc.php');


if (! file_exists($sourcePath)) {
    $sourcePath = $config["UPLOADS_DIR"] . $_get['t'] . DIRECTORY_SEPARATOR . 'default.gif';
}
if (intval($_get['w'] + $_get['h']) == 0) {
    $_get['w'] = 70;
    $_get['h'] = 50;
}
$thumb = PhpThumbFactory::create($sourcePath);
$thumb->adaptiveResize($_get['w'], $_get['h']);
$thumb->show();    


