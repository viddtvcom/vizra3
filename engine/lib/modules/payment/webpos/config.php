<?php

$sys['title'] = Setting::type('textbox')->lab('Görünen Ad')->val('Kredi Kartı WebPos')->width(200);
$sys['method'] = Setting::type('hidden')->val('cc')->width(200);
//$sys['instructions'] = Setting::type('textarea')->lab('Açıklama')->depends('status','active');

$sys['merchantID'] = Setting::type('textbox')->lab('Mağaza Kodu')->width(200)->depends('status', 'active');
$sys['username'] = Setting::type('textbox')->lab('Kullanıcı')->width(200)->depends('status', 'active');
$sys['password'] = Setting::type('textbox')->lab('Şifre')->width(200)->encrypted(true)->depends('status', 'active');
$sys['posUrl'] = Setting::type('textbox')->lab('POS Url')->width(300)->depends('status', 'active');