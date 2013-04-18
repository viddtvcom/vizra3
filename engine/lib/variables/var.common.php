<?php

$vars['PAGE_MODULES'] = array(
    1 => 'setup',
    2 => 'support',
    3 => 'clients',
    4 => 'orders',
    5 => 'finance',
    6 => 'tools',
    7 => 'reports'
);

$vars["WEEK_DAYS_SHORT"] = array(
    "0" => "Paz",
    "1" => "Pzt",
    "2" => "Sal",
    "3" => "Çar",
    "4" => "Per",
    "5" => "Cum",
    "6" => "Cmt"
);

$vars["BILLING_CYCLES"] = array(1, 3, 6, 12, 24, 36, 48, 60);

$vars["PRIORITY_OPTIONS"] = array(1 => "Critical", 2 => "Urgent", 3 => "Normal", 4 => "Average", 5 => "Low");

$vars['ORDER_STATUS_TYPES'] = array(
    'pending-payment',
    'pending-provision',
    'active',
    'suspended',
    'deleted',
    'inactive'
);

$vars['ORDER_PAY_TYPES'] = array('free', 'onetime', 'recurring');

$vars['TICKET_STATUS_TYPES'] = array('closed', 'new', 'client-responded', 'awaiting-reply', 'investigating');

$vars['CLIENT_STATUS_TYPES'] = array('active', 'pending', 'suspended', 'inactive');

$vars['DOMAIN_STATUS_TYPES'] = array('pending', 'active', 'expired', 'deleted', 'intransfer');

//$vars["STATUS_TYPES_CLIENT"]    = array("active" => "Aktif", "pending" => "Onay Bekliyor", "suspended" => "Askıda", "inactive" => "Kapalı");

$vars['SERVICE_TYPES'] = array(
    'shared'   => 'Paylaşımlı Hosting',
    'reseller' => 'Reseller Hosting',
    'service'  => 'Servis',
    'product'  => 'Ürün'
);


$arr_months_short = array(
    "1"  => "Oca",
    "2"  => "Şub",
    "3"  => "Mar",
    "4"  => "Nis",
    "5"  => "May",
    "6"  => "Haz",
    "7"  => "Tem",
    "8"  => "Ağu",
    "9"  => "Eyl",
    "10" => "Eki",
    "11" => "Kas",
    "12" => "Ara"
);

$arr_months = array(
    "1"  => "Ocak",
    "2"  => "Şubat",
    "3"  => "Mart",
    "4"  => "Nisan",
    "5"  => "Mayıs",
    "6"  => "Haziran",
    "7"  => "Temmuz",
    "8"  => "Ağustos",
    "9"  => "Eylül",
    "10" => "Ekim",
    "11" => "Kasım",
    "12" => "Aralık"
);


$vars['ADMIN_ACTIONS'] = array(
    1 => 'Görme',
    2 => 'Güncelleme',
    3 => 'Ekleme',
    4 => 'Silme',
    5 => 'Arama',
    6 => 'Şifre Görme'
);