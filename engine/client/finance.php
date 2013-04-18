<?php

if ($_post['action'] == 'declare') {
    $PYM = new Payment((int)$_post['paymentID']);
    if ($PYM->clientID == CLIENTID && $PYM->paymentStatus != 'paid') {
        $PYM->description = sanitize($_post['description']);
        $PYM->paymentStatus = 'pending-approval';
        $PYM->update();
        core::raise('Ödeme bildiriminiz alınmıştır. En kısa zaman kontrol edilip onaylanacaktır', 'm');
    }
    redirect('?p=user&s=finance&tab=payments');
}

$core->assign('tab', $_get['tab']);

$modules = Module::getModuleList('payment');
$core->assign('modules', $modules);

$module_titles = Module::getModuleTitles('payment');
$core->assign('module_titles', $module_titles);

switch ($_get['tab']) {

    case 'payments':
        $core->assign("payments", $_SESSION['vclient']->getClientPayments());
        break;

    case 'accflow':
        $sql = "SELECT ob.*,o.title, ob.dateDue AS dateAdded
                FROM order_bills ob  
                    LEFT JOIN orders o ON  (ob.orderID = o.orderID AND o.status != 'pending-payment')
                WHERE (ob.clientID = " . getClientID() . " OR o.clientID = " . getClientID() . ")
                ORDER BY dateAdded DESC";
        $bills = $db->query($sql, SQL_ALL);

        $sql = "SELECT p.* FROM payments p
                WHERE p.clientID = " . getClientID() . " AND p.paymentStatus = 'paid'";
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

        $core->assign('chart', $chart);
        break;

    case 'bills':
    default:
        /*        if ($status != "") $where = "AND ob.status = '".$status."'";
                if ($dateOffset != "") {$dateOffset = 'AND ob.dateDue < UNIX_TIMESTAMP(ADDDATE(CURDATE(),INTERVAL '.$dateOffset.' DAY))';}*/
        $sql = "SELECT ob.*,o.title FROM order_bills ob  
                    LEFT JOIN orders o ON ob.orderID = o.orderID
                WHERE (o.clientID = " . $_SESSION['vclient']->clientID . " OR ob.clientID = " . $_SESSION['vclient']->clientID . " )
                ORDER BY ob.status DESC,ob.dateDue ASC";
        $pag = paging($sql, 0, 30, 'ob.billID');
        $pag['url'] = $config['HTTP_HOST'] . '?p=user&s=finance&tab=bills';
        $core->assign('pag', $pag);
        $bills = $db->query($sql . $pag['limit'], SQL_ALL);
        $core->assign("bills", $bills);
        break;
}


//$core->assign("balance",$_SESSION['vclient']->getClientBalance()." ".core::getCurrencyById(MAIN_CUR_ID));


$tplContent = "user/finance.tpl";

function cmp($a, $b)
{
    if ($a['dateAdded'] == $b['dateAdded']) {
        return 0;
    }
    return ($a['dateAdded'] < $b['dateAdded']) ? - 1 : 1;
}

 
  
