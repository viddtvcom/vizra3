<?php

if ($_POST['action'] == 'reload') {
    core::_vlc_checkstatus(true);
    core::raise('Lisans bilgileri yüklendi', 'm', 'rt');
}
$data = core::_vlc_getlocal();


$core->assign('licdata', $data);
$core->assign('license', ! (defined('LICENSE') == false || LICENSE == ''));


$tpl_content = 'license_info.tpl';
