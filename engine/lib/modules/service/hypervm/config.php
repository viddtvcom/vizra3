<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('HyperVM')->width(200);
$sys["attrs"] = Setting::type('hidden')->lab('Attr List')->val('password')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('service'));

// service
$srvc['v-num_ipaddress_f'] = Setting::type('textbox')->lab('IP Adedi')->desc('')->width(50);
$srvc['v-ostemplate'] = Setting::type('server')->lab('OS Template')->desc('')->width(150);
$srvc['v-syncserver'] = Setting::type('server')->lab('Sync Server')->desc('')->width(150);
$srvc['v-plan_name'] = Setting::type('server')->lab('Plan')->desc('')->width(150);
$srvc['ip_addresses'] = Setting::type('textarea')->lab('IP Adresleri')->desc('')->width(150)->height(80);

// server
$srvr['use_ssl'] = Setting::type('checkbox')->lab('SSL')->desc('SSL ile bağlan');
$srvr['port'] = Setting::type('combobox')->lab('Port')->desc('')->opt('7777', '7777')->opt('7778', '7778')->opt(
    '8887',
    '8887'
)->opt('8888', '8888');
$srvr['v-type'] = Setting::type('combobox')->lab('V Type')->desc('')->opt('openvz', 'OpenVZ')->opt('xen', 'Xen');
$srvr['v-ostemplate'] = Setting::type('hidden');
$srvr['v-syncserver'] = Setting::type('hidden');
$srvr['v-plan_name'] = Setting::type('hidden');

//install

$install['vmname'] = Setting::type('textbox')->lab('Sanal Sunucu Adı')->width(150)->source('client')->validation(
    '^[a-zA-Z0-9]{4,10}$',
    'Sadece harf ve rakam, min 4, maks 10 karakter olmalı'
);
