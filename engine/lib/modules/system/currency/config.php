<?php

$sys['title'] = Setting::type('textbox')->lab('Görünen Ad')->val('Kur Güncelleyici')->width(200);
$sys['type'] = Setting::type('hidden')->val('currency')->width(200);


$sys['col'] = Setting::type('textbox')->lab('Kolon')->width(30)->desc(
    '1:Döviz Alış, 2:Döviz Satış, 3:Efektif Alış, 4:Efektif Satış'
);
$sys['offset'] = Setting::type('textbox')->lab('Ekleme')->width(50)->desc('Gireceğiniz değer kura eklenir');


$notes['setting'] = 'Kur güncelleme sisteminin otomatik olarak çalışabilmesi için, aşağıdaki cron ayarını yapmış olmanız gerekmektedir.<br />Örnekteki cron komutu saatte 1 defa çalışacak şekilde yazılmıştır.<br><input type="text" value="0  */1  * * * php ' . $config['BASE_PATH'] . 'engine/cron/currency.php > /dev/null 2>&1" style="width: 500px; margin:10px 20px;"><br />Son çalışma zamanı: ' . cronLastRun(
    'currency.php'
);

