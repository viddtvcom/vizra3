<?php

$sys["title"] = Setting::type('textbox')->lab('Görünen Ad')->val('Banka Havalesi')->width(200);
$sys["method"] = Setting::type('hidden')->val('html')->width(200);
$sys['instructions'] = Setting::type('textarea')->lab('Açıklama');