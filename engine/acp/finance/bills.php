<?php


$joins = " LEFT JOIN orders o ON ob.orderID = o.orderID";
$joins .= " INNER JOIN clients c ON (c.clientID = o.clientID OR c.clientID = ob.clientID)";
$sort = "ob.dateDue ASC";

// admin settings
$_SESSION['vadmin']->syncSetting('billSearch_bstatus', &$_GET['bstatus']);
$_SESSION['vadmin']->syncSetting('billSearch_ostatus', &$_GET['ostatus']);
$_SESSION['vadmin']->syncSetting('billSearch_sort', &$_GET['sort']);


if ($_GET['billID'] != '') {
    redirect('?p=516&billID=' . $_GET['billID']);
}


if ($_GET['bstatus'] != 'all') {
    $conditions .= " AND ob.status = '" . $_GET['bstatus'] . "'";
}
if ($_GET['ostatus'] != 'all') {
    $conditions .= " AND o.status = '" . $_GET['ostatus'] . "'";
}
if ($_GET['orderID'] != '') {
    $conditions .= " AND ob.orderID = '" . $_GET['orderID'] . "'";
}
if ($_GET['description'] != '') {
    $conditions .= " AND ob.description LIKE '%" . $_GET['description'] . "%'";
}

//if (!isset($_GET['sort'])) $_GET['sort'] = 7;

if ((int)$_GET['sort'] > 0) {
    if ($_GET['sort'] == '1') {
        $conditions .= " AND ob.dateDue < UNIX_TIMESTAMP()";
    } else {
        $conditions .= " AND ob.dateDue > UNIX_TIMESTAMP() AND ob.dateDue < UNIX_TIMESTAMP( ADDDATE(CURDATE(),INTERVAL " . intval(
            $_GET['sort']
        ) . " DAY ))";
    }
}

$sql = "SELECT ob.*,c.name,c.type AS clientType, c.company, o.title AS orderTitle,o.status AS orderStatus,c.clientID 
        FROM order_bills ob
        $joins
        WHERE 1=1 $conditions
        ORDER BY $sort";

$pag = paging($sql, 0, 20, 'ob.billID');
$core->assign('pag', $pag);
$bills = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign('bills', $bills);

$core->assign('icons', Order::$icons);


$tpl_content = 'bills';
