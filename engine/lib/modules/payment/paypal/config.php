<?php

$sys['title'] = Setting::type('textbox')->lab('Görünen Ad')->val('PayPal')->width(200);
$sys['method'] = Setting::type('hidden')->val('html')->width(200);
$sys['paypal_email'] = Setting::type('textbox')->lab('Paypal Email')->width(200)->depends('status', 'active');

$sys['auto_approve'] = Setting::type('checkbox')->lab('Otomatik Onay')->desc(
    'Başarılı PayPal ödemelerini otomatik onaylamak için tıklayınız'
);
$sys['test_mode'] = Setting::type('checkbox')->lab('Test Mod')->desc(
    'PayPal Test Modu (SandBox) kullanmak için tıklayınız'
);

