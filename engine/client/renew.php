<?php

if ($_GET['oID']) { // GET neden büyük?
    if (! preg_match('/^\d{7}$/', $_GET['oID'])) {
        core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=user&s=orders');
    }
    $_post['selected'][] = $_GET['oID'];
}

if (empty($_post['selected'])) {
    core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=user&s=orders');
}

foreach ((array)$_post['selected'] as $orderID) {
    if (! preg_match('/^\d{7}$/', $orderID)) {
        continue;
    }

    $Order = getClientOrder($orderID);

    if (! $Order) {
        core::raise($orderID . ': böyle bir sipariş kaydı bulunamadı.', 'e');
        $error = true;
    } elseif ($Order->status != 'active' && $Order->status != 'suspended') {
        core::raise($orderID . ': Sadece aktif ve askıda olan siparişler yenilenebilir', 'e');
        $error = true;
    } elseif ($Order->payType != 'recurring') {
        core::raise($orderID . ': Bu sipariş yenilenemez', 'e');
        $error = true;
    }

    if ($error) {
        redirect('?p=user&s=orders');
    }

    $Order->loadService();
    $ord['orderID'] = $orderID;
    $ord['title'] = $Order->title;
    $ord['period'] = $Order->period;
    $ord['paycurID'] = $Order->paycurID;

    $opt = array();
    $renew_price = $Order->getRenewPrice();
    for ($x = 1; $x < 6; $x ++) {
        $opt[$x]['timestamp'] = addDate($Order->dateEnd, ($x * $Order->period), 'm');
        $opt[$x]['price'] = $x * $renew_price;

    }
    $ord['options'] = $opt;

    $orders[] = $ord;
}


$core->assign('orders', $orders);
$tplContent = 'user/renew.tpl';
