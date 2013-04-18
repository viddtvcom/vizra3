<?php

$sys['title'] = Setting::type('textbox')->lab('Görünen Ad')->val('SMS')->width(200);
$sys['type'] = Setting::type('hidden')->val('sms')->width(200);


$sys['gateway'] = Setting::type('combobox')->lab('SMS Gateway')
        ->opt('clickatell', 'Clickatell')
        ->opt('smsalsat', 'SmsAlSat')
        ->opt('toplusmsyolla', 'TopluSmsYolla')
        ->opt('pusulasms', 'Pusula SMS')
        ->opt('atlassms', 'Atlas SMS');

$sys['username'] = Setting::type('textbox')->lab('Kullanıcı Adı')->width(200);
$sys['password'] = Setting::type('textbox')->lab('Şifre')->width(200)->encrypted(true);
$sys['originator'] = Setting::type('textbox')->lab('Originator')->width(200)->desc('Yoksa boş bırakınız');
$sys['param1'] = Setting::type('textbox')->lab('Parametre 1')->width(200)->desc('(Clickatell: Api ID)');
$sys['param2'] = Setting::type('textbox')->lab('Parametre 2')->width(200);
$sys['param3'] = Setting::type('textbox')->lab('Parametre 3')->width(200);



