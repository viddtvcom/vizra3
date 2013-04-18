<?php

$sys['title'] = Setting::type('textbox')->lab('Görünen Ad')->val('2CheckOut')->width(200);
$sys['method'] = Setting::type('hidden')->val('html')->width(200);
$sys['sid'] = Setting::type('textbox')->lab('Seller ID')->width(200)->depends('status', 'active');
$sys['key'] = Setting::type('textbox')->lab('Secret Word')->width(200)->depends('status', 'active')->desc(
    'Account > Site Management'
);


$sys['auto_approve'] = Setting::type('checkbox')->lab('Otomatik Onay')->desc(
    'Başarılı 2CheckOut ödemelerini otomatik onaylamak için tıklayınız'
);

$sys['demo_mode'] = Setting::type('checkbox')->lab('Test Mod')->desc('Demo Mod altında çalışmak için tıklayınız');
