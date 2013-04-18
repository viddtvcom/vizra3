<?php

/*
*   Borclar
*/

$sql = "SELECT ob.*,o.title, ob.dateDue AS dateAdded
        FROM order_bills ob  
            LEFT JOIN orders o ON  (ob.orderID = o.orderID AND o.status != 'pending-payment')
        WHERE (ob.clientID = " . $Client->clientID . " OR o.clientID = " . $Client->clientID . ")
        ORDER BY dateAdded DESC";
$bills = $db->query($sql, SQL_ALL);

/*
*   Ödemeler
*/

$sql = "SELECT p.* FROM payments p
        WHERE p.clientID = " . $Client->clientID . " AND p.paymentStatus = 'paid'";
$payments = $db->query($sql, SQL_ALL);

$module_titles = Module::getModuleTitles('payment');

$all = array_merge($bills, $payments);
usort($all, 'accflow_cmp');
$total = 0;

foreach ($all as $a) {

    $item['amount'] = $a['amount'];
    $item['amount2'] = $a['xamount'];


    if ($a['billID'] > 0) {
        $item['id'] = $a['billID'];
        $item['type'] = 'bill';
        $item['description'] = ($a['clientID']) ? $a['description'] : $a['title'];
        $total -= $a['xamount'];
        $item['timestamp'] = $a['dateDue'];
    } else {
        $item['id'] = $a['paymentID'];
        $item['type'] = 'payment';
        $item['description'] = $a['moduleID'];
        $total += $a['xamount'];
        $item['description'] = $module_titles[$a['moduleID']];
        $item['timestamp'] = $a['datePayed'];
    }

    $item['paycurID'] = $a['paycurID'];
    $item['balance'] = $total;
    $chart[] = $item;
}


/*if ($cur_error) {
    core::raise('Bazı tarihlere ait kur bilgisi bulunamadı. Kur Güncelleyici ile güncellemek için ilgili satırlara tıklayınız','w');
}*/
$core->assign('chart', $chart);

