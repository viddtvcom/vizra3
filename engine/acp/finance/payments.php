<?php

if ($_POST['action'] == 'Sil') {
    if (! $_POST['selected']) {
        core::raise('En az bir ödeme seçmelisiniz', 'e', '?p=510');
    }
    foreach ($_POST['selected'] as $paymentID) {
        // send deleted mail
        if ($_POST['send_mail'] == 'true') {
            $EMT = new Email_template(13);
            $EMT->paymentID = $paymentID;
            $EMT->send();
        }
        Payment::destroy($paymentID);
        core::raise($paymentID . ' nolu ödeme sistemden silindi', 'm');
    }
    redirect('?p=510');
}

$joins = "INNER JOIN clients c ON c.clientID = p.clientID";
$sort = "p.dateAdded DESC";


$_SESSION['vadmin']->syncSetting('paymentSearch_status', &$_GET['paymentStatus']);
$_SESSION['vadmin']->syncSetting('paymentSearch_moduleID', &$_GET['moduleID']);


if ($_GET['paymentID'] != '') {
    redirect('?p=511&paymentID=' . $_GET['paymentID']);
}

if ($_GET['moduleID'] != 'all') {
    $conditions .= " AND p.moduleID = '" . $_GET['moduleID'] . "'";
}

if ($_GET['paymentStatus'] == '') {
    $_GET['paymentStatus'] = 'all';
}
if ($_GET['paymentStatus'] != 'all') {
    $conditions .= " AND p.paymentStatus = '" . $_GET['paymentStatus'] . "'";
    //$_SESSION['vadmin']->setSetting('financeSearchPaymentStatus',$_GET['paymentStatus']);
}


$sql = "SELECT p.*,c.name,c.type AS clientType, c.company FROM payments p $joins WHERE 1=1 $conditions ORDER BY $sort";


$pag = paging($sql, 0, 20, 'p.paymentID');
$core->assign('pag', $pag);
$payments = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign('payments', $payments);
$core->assign('modules', Module::getModuleList('payment'));
$core->assign('titles', Module::getModuleTitles('payment'));

$tpl_content = 'payments';
