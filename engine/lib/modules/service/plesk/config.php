<?php

/// system
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('PLESK')->width(200);
$sys["attrs"] = Setting::type('hidden')->lab('Attr List')->val('username,password,domain')->width(200);
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));
$sys['plesk_url'] = Setting::type('textbox')->lab('Plesk Link Formatı')->val('https://{$server_hostname}:8443')->width(
    400
);


/// service
$srvc['template_name'] = Setting::type('textbox')->lab('Template')->desc('')->width(150);
$srvc['disk_space'] = Setting::type('textbox')->lab('Disk Alanı')->desc('MB')->width(50)->cmd('setDiskQuota')->set(
    'addon',
    true
);
$srvc['max_traffic'] = Setting::type('textbox')->lab('Aylık Trafik')->desc('MB')->width(50)->cmd('setTraffic')->set(
    'addon',
    true
);
$srvc['max_db'] = Setting::type('textbox')->lab('Maks MySQL')->desc('Adet')->width(50)->stype('shared');
$srvc['max_mssql_db'] = Setting::type('textbox')->lab('Maks MsSQL')->desc('Adet')->width(50)->stype('shared');
$srvc['max_box'] = Setting::type('textbox')->lab('Maks POP3')->desc('Adet')->width(50)->stype('shared');
$srvc['max_subdom'] = Setting::type('textbox')->lab('Maks Subdomain')->desc('Adet')->width(50)->stype('shared');
$srvc['max_dom_aliases'] = Setting::type('textbox')->lab('Maks Park')->desc('Adet')->width(50)->stype('shared');

/*$srvc['php'] = Setting::type('combobox')->lab('PHP')->opt('1','Var')->opt('0','Yok')->def('0');
$srvc['asp'] = Setting::type('combobox')->lab('ASP')->opt('1','Var')->opt('0','Yok')->def('0');
$srvc['asp_dot_net'] = Setting::type('combobox')->lab('ASP.NET')->opt('1','Var')->opt('0','Yok')->def('0');
$srvc['cgi'] = Setting::type('combobox')->lab('CGI')->opt('1','Var')->opt('0','Yok')->def('0');
$srvc['fp'] = Setting::type('combobox')->lab('Frontpage')->opt('1','Var')->opt('0','Yok')->def('0');
$srvc['mod_perl'] = Setting::type('combobox')->lab('PERL')->opt('1','Var')->opt('0','Yok')->def('0'); */

$srvc['max_dom'] = Setting::type('textbox')->lab('Web Sitesi')->desc('Adet')->width(30)->stype('reseller');

/// server
$srvr['port'] = Setting::type('textbox')->lab('Port')->val('8443')->width(100);

$srvr['ns1'] = Setting::type('textbox')->lab('NameServer 1')->width(250);
$srvr['ns2'] = Setting::type('textbox')->lab('NameServer 2')->width(250);
$srvr['ns1_ip'] = Setting::type('textbox')->lab('NameServer 1 IP')->width(250);
$srvr['ns2_ip'] = Setting::type('textbox')->lab('NameServer 2 IP')->width(250);

$srvr['reseller_username'] = Setting::type('textbox')->lab('Reseller Kullanıcı Adı')->width(250)->encrypted(true);
$srvr['reseller_password'] = Setting::type('textbox')->lab('Reseller Şifre')->width(250)->encrypted(true);
$srvr['reseller_ns1'] = Setting::type('textbox')->lab('Reseller NameServer 1')->width(250);
$srvr['reseller_ns2'] = Setting::type('textbox')->lab('Reseller NameServer 2')->width(250);

$srvr['load_monitor'] = Setting::type('checkbox')->lab('Yük Gözlemleme')->desc(
    'Aktif etmek için işaretleyiniz (Admin yetkisi gerektirir)'
);
$srvr['critical_load'] = Setting::type('textbox')->lab('Kritik Yük')->desc(
    '% Yük bu seviye üzerine çıkarsa uyar'
)->depends('load_monitor', '1')->width(30);