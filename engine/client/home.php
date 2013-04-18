<?php

$core->assign("ticketsInProgress", $_SESSION['vclient']->getClientTickets("!=", "x", 3));
$tplContent = "user/home.tpl";

// domains
$sql = "SELECT d.* FROM domains d 
            INNER JOIN orders o ON (o.orderID = d.orderID AND o.clientID = " . CLIENTID . ")
        WHERE o.status IN ('active','suspended') 
            AND d.dateExp < UNIX_TIMESTAMP(ADDDATE(CURDATE(),INTERVAL 30 DAY))";
$doms = $db->query($sql, SQL_ALL);
$core->assign('domains', $doms);


// payments
$core->assign("payments", $_SESSION['vclient']->getClientPayments(array('pending-payment', 'pending-approval')));


// orders
$sql = "SELECT * FROM orders o 
        WHERE o.clientID = " . CLIENTID . " AND o.status IN ('active','suspended') AND o.payType = 'recurring'
            AND o.dateEnd < UNIX_TIMESTAMP(ADDDATE(CURDATE(),INTERVAL 30 DAY))";
$orders = $db->query($sql, SQL_ALL);
$core->assign('orders', $orders);


// bills
$sql = "SELECT ob.*, o.title
        FROM order_bills ob  
            LEFT JOIN orders o ON ob.orderID = o.orderID
        WHERE ob.status = 'unpaid' AND (ob.clientID = " . CLIENTID . " OR o.clientID = " . CLIENTID . ")";
$bills = $db->query($sql, SQL_ALL);
$core->assign('bills', $bills);


$core->assign('icons', Order::$icons);