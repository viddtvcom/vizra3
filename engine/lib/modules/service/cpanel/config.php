<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('cPanel')->width(200);
$sys["attrs"] = Setting::type('hidden')->lab('Attr List')->val('username,password,domain')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));
$sys['tmp_url'] = Setting::type('textbox')->lab('Geçici URL Link Formatı')->val(
    'http://{$server_mainip}/~{$user}'
)->width(400);
$sys['webmail_url'] = Setting::type('textbox')->lab('Webmail Link Formatı')->val(
    'http://{$server_hostname}/webmail'
)->width(400);
$sys['cpanel_url'] = Setting::type('textbox')->lab('cPanel Link Formatı')->val(
    'http://{$server_hostname}:2082/login/?user={$user}&pass={$pass}'
)->width(400);
$sys['whm_url'] = Setting::type('textbox')->lab('WHM Link Formatı')->val(
    'http://{$server_hostname}:2086/login/?user={$user}&pass={$pass}'
)->width(400);


// service
//$srvc['type'] = Setting::type('combobox')->lab('Hesap Türü')->opt('shared','Paylaşımlı')->opt('reseller','Reseller')->def('shared')->set('addon',true);


$srvc['plan'] = Setting::type('textbox')->lab('cPanel Package')->desc('')->width(150);
$srvc['cpmod'] = Setting::type('textbox')->lab('cPanel Teması')->desc('')->width(150);

$srvc['quota'] = Setting::type('textbox')->lab('##DiskSpace##')->desc('MB')->width(50)->set('addon', true)->cmd(
    'setDiskQuota'
);
$srvc['bwlimit'] = Setting::type('textbox')->lab('Aylık Trafik')->desc('MB')->width(50)->set('addon', true)->cmd(
    'setTraffic'
);
$srvc['maxpark'] = Setting::type('textbox')->lab('Maks Park')->desc('Adet')->width(50);
$srvc['maxaddon'] = Setting::type('textbox')->lab('Maks Addon')->desc('Adet')->width(50);
$srvc['maxftp'] = Setting::type('textbox')->lab('Maks FTP')->desc('Adet')->width(50);
$srvc['maxsql'] = Setting::type('textbox')->lab('Maks MySQL')->desc('Adet')->width(50);
$srvc['maxpop'] = Setting::type('textbox')->lab('Maks POP3')->desc('Adet')->width(50);
$srvc['maxsub'] = Setting::type('textbox')->lab('Maks Subdomain')->desc('Adet')->width(50);

$srvc['cgi'] = Setting::type('combobox')->lab('CGI')->opt('1', 'Var')->opt('0', 'Yok')->def('0');
$srvc['frontpage'] = Setting::type('combobox')->lab('Frontpage')->opt('1', 'Var')->opt('0', 'Yok')->def('0');
//$srvc['language'] = Setting::type('textbox')->lab('Dil Seçimi')->opt('en','İngilizce')->opt('tr','Türkçe')->def('tr');

$srvc['acllist'] = Setting::type('textbox')->lab('ACL List')->desc('')->width(80)->stype('reseller');
$srvc['account_limit'] = Setting::type('textbox')->lab('Web Sitesi')->desc('Adet')->width(50)->stype('reseller');
$srvc['diskspace_limit'] = Setting::type('textbox')->lab('Disk Alanı (Reseller)')->desc('MB')->width(50)->stype(
    'reseller'
)->set('addon', true)->cmd('setDiskQuota');
$srvc['bandwidth_limit'] = Setting::type('textbox')->lab('Aylık Trafik (Reseller)')->desc('MB')->width(50)->stype(
    'reseller'
)->set('addon', true)->cmd('setTraffic');

$srvc['enable_resource_limits'] = Setting::type('checkbox')->lab('Limit Resource')->desc('Kaynakları kısıtla')->stype(
    'reseller'
)->def('1');
$srvc['enable_overselling_bandwidth'] = Setting::type('checkbox')->lab('Oversell Bandwidth')->desc(
    'Müşterilerine toplamda limitinden fazla trafik verebilir'
)->stype('reseller')->def('1');
$srvc['enable_overselling_diskspace'] = Setting::type('checkbox')->lab('Oversell Diskspace')->desc(
    'Müşterilerine toplamda limitinden fazla alan verebilir'
)->stype('reseller')->def('1');


// serrver
$srvr['auth'] = Setting::type('combobox')->lab('Auth Metod')->opt('pass', 'Şifre')->opt('hash', 'Remote Access Key');
$srvr['server_hash'] = Setting::type('textarea')->lab('Remote Access Key')->encrypted(true)->width(250)->height(
    150
)->depends('auth', 'hash');

$srvr['ns1'] = Setting::type('textbox')->lab('NameServer 1')->width(250);
$srvr['ns2'] = Setting::type('textbox')->lab('NameServer 2')->width(250);
$srvr['ns1_ip'] = Setting::type('textbox')->lab('NameServer 1 IP')->width(250);
$srvr['ns2_ip'] = Setting::type('textbox')->lab('NameServer 2 IP')->width(250);

$srvr['reseller_hostname'] = Setting::type('textbox')->lab('Reseller Hostname')->width(250);
$srvr['reseller_username'] = Setting::type('textbox')->lab('Reseller Kullanıcı Adı')->width(250)->encrypted(true);
$srvr['reseller_password'] = Setting::type('textbox')->lab('Reseller Şifre')->width(250)->encrypted(true);
$srvr['reseller_ns1'] = Setting::type('textbox')->lab('Reseller NameServer 1')->width(250);
$srvr['reseller_ns2'] = Setting::type('textbox')->lab('Reseller NameServer 2')->width(250);
$srvr['reseller_auth'] = Setting::type('combobox')->lab('Reseller Auth Metod')->opt('pass', 'Şifre')->opt(
    'hash',
    'Remote Access Key'
);
$srvr['reseller_server_hash'] = Setting::type('textarea')->lab('Reseller Remote Access Key')->encrypted(true)->width(
    250
)->height(150)->depends('reseller_auth', 'hash');

$srvr['load_monitor'] = Setting::type('checkbox')->lab('Yük Gözlemleme')->desc('Aktif etmek için işaretleyiniz');
$srvr['cpu_count'] = Setting::type('textbox')->lab('CPU Sayısı')->desc('Sunucuda gözüken toplam CPU sayısı')->depends(
    'load_monitor',
    '1'
)->width(40)->def('1');
$srvr['critical_load'] = Setting::type('textbox')->lab('Kritik Yük')->desc(
    '% Yük bu seviye üzerine çıkarsa uyar'
)->depends('load_monitor', '1')->width(30);
$srvr['use_ssl'] = Setting::type('checkbox')->lab('SSL')->desc('SSL ile bağlan');

