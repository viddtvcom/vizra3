<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('WHMSonic')->width(200);
//$sys["attrs"]       = Setting::type('hidden')->lab('Attr List')->val('password')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));

// service


$srvc['max_listener'] = Setting::type('textbox')->lab('Maks Dinleyici')->width(50);
$srvc['max_bitrate'] = Setting::type('textbox')->lab('Maks Bitrate')->desc('Kbps')->width(50);
$srvc['bw'] = Setting::type('textbox')->lab('Trafik Limiti')->desc('MB')->width(50);
$srvc['port'] = Setting::type('textbox')->lab('Port')->desc('', 'Auto-Port için boş bırakın')->width(50);
$srvc['ip'] = Setting::type('textbox')->lab('IP')->desc('', 'Auto IP için boş bırakın')->width(150);
$srvc['autodj'] = Setting::type('checkbox')->lab('Auto DJ')->desc('');


// server
$srvr['use_ssl'] = Setting::type('checkbox')->lab('SSL')->desc('SSL ile bağlan');

// install
$install['username'] = Setting::type('textbox')->lab('Kullanıcı Adı')->width(100)->source('client')->validation(
    '^[a-zA-Z0-9]{4,10}$',
    'Sadece harf ve rakam, min 4, maks 10 karakter olmalı'
);
$install['password'] = Setting::type('textbox')->lab('Panel Şifresi')->encrypted(true)->source('module')->width(150);
$install['admin_pass'] = Setting::type('textbox')->lab('Admin Şifresi')->desc('')->width(150)->source(
    'module'
)->encrypted(true);
$install['radio_pass'] = Setting::type('textbox')->lab('Radyo Şifresi')->desc('')->width(150)->source(
    'module'
)->encrypted(true);
