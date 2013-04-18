<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('HELM3')->width(200);
$sys["attrs"] = Setting::type('hidden')->lab('Attr List')->val('domain,username,password')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));

// service
$srvc['CustomerPlanId'] = Setting::type('textbox')->lab('Helm Plan ID')->desc('')->width(150);
$srvc['PackageName'] = Setting::type('textbox')->lab('Helm Package Name')->desc('')->width(150);
$srvc['website'] = Setting::type('textbox')->lab('Web Sitesi')->desc('')->width(150)->stype('reseller');


$srvc['webspace'] = Setting::type('textbox')->lab('Disk Alanı')->desc('MB')->width(50);
$srvc['traffic'] = Setting::type('textbox')->lab('Aylık Trafik')->desc('MB')->width(50);

$srvc['mssql'] = Setting::type('textbox')->lab('MSSQL')->desc('Adet')->width(50);
$srvc['mysql'] = Setting::type('textbox')->lab('MySQL')->desc('Adet')->width(50);
$srvc['pop3'] = Setting::type('textbox')->lab('POP3')->desc('Adet')->width(50);
$srvc['subdomain'] = Setting::type('textbox')->lab('Subdomain')->desc('Adet')->width(50);


$srvc['web_ip'] = Setting::type('textbox')->lab('Web IP')->width(100)->stype('shared');
$srvc['ftp_ip'] = Setting::type('textbox')->lab('FTP IP')->width(100)->stype('shared');
$srvc['tmp_url'] = Setting::type('textbox')->lab('TMP URL')->width(100)->stype('shared');

// server
$srvr['ns1'] = Setting::type('textbox')->lab('NameServer 1')->width(250);
$srvr['ns2'] = Setting::type('textbox')->lab('NameServer 2')->width(250);
$srvr['ns1_ip'] = Setting::type('textbox')->lab('NameServer 1 IP')->width(250);
$srvr['ns2_ip'] = Setting::type('textbox')->lab('NameServer 2 IP')->width(250);

$srvr['reseller_hostname'] = Setting::type('textbox')->lab('Reseller Hostname')->width(250);
$srvr['reseller_username'] = Setting::type('textbox')->lab('Reseller Kullanıcı Adı')->width(250)->encrypted(true);
$srvr['reseller_password'] = Setting::type('textbox')->lab('Reseller Şifre')->width(250)->encrypted(true);
$srvr['reseller_ns1'] = Setting::type('textbox')->lab('Reseller NameServer 1')->width(250);
$srvr['reseller_ns2'] = Setting::type('textbox')->lab('Reseller NameServer 2')->width(250);


//install

