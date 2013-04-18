<?php

/**
 *   Vizra Servis Modülü, Örnek config dosyası
 *
 *
 *   cPanel modul dosyasindan sadelestirilerek
 *
 */


/**
 *   Sistem - Modül Özellikleri
 */
$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')
        ->val('cPanel')
        ->width(200);

$sys["attrs"] = Setting::type('hidden')->lab('Attr List')
        ->val('username,password,domain')
        ->width(200);

/**
 * servis tipleri bu ozellik ile belirlenir
 *
 * shared: paylasimli hosting
 * reseller: reseller hosting
 * service: servis
 */
$sys['stypes'] = Setting::type('hidden')->val(array('shared', 'reseller'));


$sys['webmail_url'] = Setting::type('textbox')->lab('Webmail Link Formatı')
        ->val('http://{$server_hostname}/webmail')
        ->width(400);


/**
 *   Servis - Sipariş Özellikleri
 */

$srvc['quota'] = Setting::type('textbox')->lab(
    '##DiskSpace##'
) // Farkli dil kullanimi icin ##xxx## seklinde girip lang dosyalarina giris yapiniz
        ->desc('MB')
        ->width(50)
        ->set('addon', true) // Bu servisten ek siparis olusturulursa bu ozellik gecerli olur
        ->cmd('setDiskQuota'); // setDiskQuota modül fonksiyonuna baglanti

$srvc['bwlimit'] = Setting::type('textbox')->lab('Aylık Trafik')
        ->desc('MB')
        ->width(50)
        ->set('addon', true) // Bu servisten ek siparis olusturulursa bu ozellik gecerli olur
        ->cmd('setTraffic'); // cmd_setTraffic modül fonksiyonuna baglanti

$srvc['cgi'] = Setting::type('combobox')->lab('CGI')
        ->opt('1', 'Var')
        ->opt('0', 'Yok')
        ->def('0'); // Default deger

$srvc['diskspace_limit'] = Setting::type('textbox')->lab('Disk Alanı (Reseller)')
        ->desc('MB')
        ->width(50)
        ->stype('reseller') // Sadece reseller tipi servislerde aktif olur
        ->set('addon', true)
        ->cmd('setDiskQuota');

$srvc['bandwidth_limit'] = Setting::type('textbox')->lab('Aylık Trafik (Reseller)')
        ->desc('MB')
        ->width(50)
        ->stype('reseller')
        ->set('addon', true)
        ->cmd('setTraffic');


/**
 *   Sunucu Özellikleri
 */
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

$srvr['use_ssl'] = Setting::type('checkbox')->lab('SSL')->desc('SSL ile bağlan');

