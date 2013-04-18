<?php

if ($_GET['act'] == 'delClient' && privcheck(4)) {
    $Cl = new Client($_GET['clientID']);
    if ($Cl->clientID) {
        $Cl->destroy();
    }
    core::raise($Cl->name . ', müşteri bilgileri sistemden silindi', 'm', '?p=310');
}

if (isset($_GET['clientID'])) {
    redirect('?p=311&clientID=' . trim($_GET['clientID']));
}

$sort = "c.dateAdded DESC";

// Admin Settings
$_SESSION['vadmin']->syncSetting('clientsSearch_clientStatus', &$_GET['status']);


if (isset($_GET['name_email'])) {
    $conditions .= " AND (c.email LIKE '" . $_GET['name_email'] . "'
                            OR c.name LIKE '%" . $_GET['name_email'] . "%'
                            OR c.company LIKE '%" . $_GET['name_email'] . "%')";
}
if (isset($_GET['status']) && $_GET['status'] != 'all') {
    $conditions .= " AND c.status = '" . $_GET['status'] . "'";
}

$sql = "SELECT * FROM clients c WHERE 1=1 $conditions ORDER BY $sort";

$pag = paging($sql, 0, 20, 'c.clientID');
$core->assign('pag', $pag);
$clients = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign("clients", $clients);
$tpl_content = "clients";

