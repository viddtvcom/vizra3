<?php

if ($_GET['act'] == 'delQJob') {
    $db->query("DELETE FROM queue WHERE jobID = " . $_GET['jobID']);
    core::raise('Kuyruk işlemi silindi', 'm', '?p=511&paymentID=' . $_GET['paymentID']);
}

$Payment = new Payment($_GET['paymentID']);
if (! $Payment->paymentID) {
    core::raise('Böyle bir ödeme kaydı bulunamadı', 'e');
    redirect('?p=510');
}
if ($_POST["action"] == "update") {
    $Payment->replace($_POST)->update();
} elseif ($_POST["action"] == "approve") {
    $Payment->approve(getAdminID(), $_POST['sendmail']);
}
if ($_POST) {
    redirect("?p=511&paymentID=" . $Payment->paymentID);
}

$core->assign('Client', new Client($Payment->clientID));
$core->assign('Approver', new Admin((int)$Payment->adminID));

$core->assign('modules', Module::getModuleList('payment'));
$core->assign('Payment', $Payment);
$tpl_content = "payment_details";


$jobs = $db->query("SELECT * FROM queue WHERE  paymentID = " . $Payment->paymentID, SQL_KEY, 'jobID');

foreach ($jobs as $jobID => $j) {
    $jobs[$jobID]['params'] = unserialize($j['params']);

}
$core->assign('jobs', $jobs);


$page_title = $Payment->paymentID . ' Nolu Ödeme Detayları';
