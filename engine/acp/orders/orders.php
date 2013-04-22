<?php

if ($_POST['action'] == 'Sunucudan Sil') {
    if (! $_POST['selected']) {
        core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=410');
    }
    foreach ($_POST['selected'] as $orderID) {
        $Order = new Order($orderID);

        $Order->setStatus('inactive');

        core::raise($orderID . ' nolu sipariş (' . $Order->title . ') siliniyor', 'm');
    }
    redirect('?p=410');
} elseif ($_POST['action'] == 'Askıya Al') {
    if (! $_POST['selected']) {
        core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=410');
    }
    foreach ($_POST['selected'] as $orderID) {
        $Order = new Order($orderID);

        $Order->setStatus('suspended');

        core::raise($orderID . ' nolu sipariş (' . $Order->title . ') askıya alınıyor', 'm');
    }
    redirect('?p=410');
} elseif ($_POST['action'] == 'Sil') {
    if (! $_POST['selected']) {
        core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=410');
    }
    foreach ($_POST['selected'] as $orderID) {
        $Order = new Order($orderID);
        // send deleted mail
        if ($_POST['send_mail'] == 'true') {
            $EMT = new Email_template(19);
            $EMT->orderID = $Order->orderID;
            $EMT->send();
        }
        $Order->destroy();
        core::raise($orderID . ' nolu sipariş (' . $Order->title . ') sistemden silindi', 'm');
    }
    redirect('?p=410');
}

if (isset($_GET['orderID'])) {
    redirect('?p=411&orderID=' . $_GET['orderID']);
}


$joins = " INNER JOIN clients c ON c.clientID = o.clientID";
$joins .= " INNER JOIN services s ON s.serviceID = o.serviceID";
//$conditions = " AND o.parentID = 0";
$sort = "o.dateAdded DESC";

// Sorting
$asort = array(
    "ASC"  => "DESC",
    "DESC" => "ASC"
);
$xsort = explode("-", $_GET["sort"]);
$sort_dir = (isset($xsort[1])) ? $xsort[1] : "ASC";

$core->assign('sort_dir', $sort_dir);
$core->assign('asort', $asort[$sort_dir]);

// Admin Settings
$_SESSION['vadmin']->syncSetting('ordersSearch_orderStatus', $_GET['status']);
$_SESSION['vadmin']->syncSetting('ordersSearch_serviceID', $_GET['serviceID']);
$_SESSION['vadmin']->syncSetting('ordersSearch_groupID', $_GET['groupID']);
$_SESSION['vadmin']->syncSetting('ordersSearch_ending', $_GET['ending']);


if ($_GET['status'] != "all" && $_GET['status'] != '') {
    $conditions .= " AND o.status = '" . $_GET['status'] . "'";
}
if ($_GET['groupID'] != "all" && $_GET['groupID'] != "") {
    if ($_GET['serviceID'] != 'all' && $_GET['serviceID'] != '') {
        $conditions .= " AND o.serviceID = '" . $_GET['serviceID'] . "'";
    } else {
        $joins .= " INNER JOIN service_groups sg ON s.groupID = sg.groupID";
        $conditions .= " AND (s.groupID = '" . $_GET['groupID'] . "' OR sg.parentID = '" . $_GET['groupID'] . "')";
    }
}
if ($_GET['ending'] != '' && $_GET['ending'] != 'all') {
    if ($_GET['ending'] == 'expired') {
        $sort = 'o.dateEnd DESC';
        $conditions .= " AND o.dateEnd > 0 AND o.dateEnd < UNIX_TIMESTAMP()";
    } else {
        $sort = 'o.dateEnd DESC';
        $conditions .= " AND o.dateEnd > UNIX_TIMESTAMP() AND o.dateEnd < UNIX_TIMESTAMP(ADDDATE(CURDATE(),INTERVAL " . (int)$_GET['ending'] . " DAY))";
    }
}
if ($_GET['title'] != '') {
    $conditions .= " AND (o.title LIKE '%" . $_GET['title'] . "%' OR oa.value LIKE '%" . $_GET['title'] . "%')";
    $joins .= " LEFT JOIN order_attrs oa ON (oa.orderID = o.orderID)";
    $group_by = " GROUP BY o.orderID";
}


$sql = "SELECT o.*,c.name,c.type AS clientType, c.company,c.balance ,s.service_name
		FROM orders o $joins
		WHERE  1 = 1 $conditions $group_by
		ORDER BY $sort";

$pag = paging($sql, 0, 30, 'o.orderID');
$core->assign('pag', $pag);
$orders = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign("orders", $orders);
$core->assign('icons', Order::$icons);

$tpl_content = "orders";

$groups = $db->query("SELECT * FROM service_groups WHERE parentID = 1", SQL_ALL);
$core->assign('groups', $groups);



