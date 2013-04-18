<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('DirectAdmin')->width(200);
$sys["attrs"] = Setting::type('hidden')->lab('Attr List')->val('username,password,domain')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));

// service
$srvc['package'] = Setting::type('textbox')->lab('Package')->desc('')->width(150);
$srvc['quota'] = Setting::type('textbox')->lab('Disk Alanı')->desc('MB')->width(50)->set('addon', true)->cmd(
    'setDiskQuota'
);
$srvc['bandwidth'] = Setting::type('textbox')->lab('Aylık Trafik')->desc('MB')->width(50)->set('addon', true)->cmd(
    'setTraffic'
);
$srvc['vdomains'] = Setting::type('textbox')->lab('Web Sitesi')->desc('Adet')->width(50)->set('addon', true)->cmd(
    'setDomainLimit'
);


// serrver
$srvr['port'] = Setting::type('textbox')->lab('Port')->val('2222')->width(100);
$srvr['use_ssl'] = Setting::type('checkbox')->lab('SSL')->desc('SSL ile bağlan');

$srvr['ns1'] = Setting::type('textbox')->lab('NameServer 1')->width(250);
$srvr['ns2'] = Setting::type('textbox')->lab('NameServer 2')->width(250);
$srvr['ns1_ip'] = Setting::type('textbox')->lab('NameServer 1 IP')->width(250);
$srvr['ns2_ip'] = Setting::type('textbox')->lab('NameServer 2 IP')->width(250);

$srvr['reseller_username'] = Setting::type('textbox')->lab('Reseller Kullanıcı Adı')->width(250)->encrypted(true);
$srvr['reseller_password'] = Setting::type('textbox')->lab('Reseller Şifre')->width(250)->encrypted(true);
$srvr['reseller_ns1'] = Setting::type('textbox')->lab('Reseller NameServer 1')->width(250);
$srvr['reseller_ns2'] = Setting::type('textbox')->lab('Reseller NameServer 2')->width(250);


