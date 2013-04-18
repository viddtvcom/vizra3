<?php

/// system

$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('DirectI')->width(200);
$sys['username'] = Setting::type('textbox')->lab('DirectI ##Username##')->width(200)->depends('status', 'active');
$sys['password'] = Setting::type('textbox')->lab('DirectI ##Password##')->width(200)->encrypted(true)->depends(
    'status',
    'active'
);
$sys['parentID'] = Setting::type('textbox')->lab('Parent ID')->width(200)->depends('status', 'active');
$sys['account_method'] = Setting::type('combobox')->lab('Hesap Tipi')->width(200)->opt('seperate', 'Ayrı')->opt(
    'allinone',
    'Hepsi bir arada'
);


$sys['customerID'] = Setting::type('textbox')->lab('Customer ID')->width(200)->depends('status', 'active')->depends(
    'account_method',
    'allinone'
);

$sys['service_url'] = Setting::type('textbox')->lab('Service URL')->width(400)->depends('status', 'active');

$notes['setting'] = linkify(
    'http://forum.vizra.net/showthread.php?t=647',
    true,
    'Ayarların açıklamaları için tıklayınız'
);