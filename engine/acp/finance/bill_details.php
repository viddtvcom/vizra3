<?php


$OrderBill = new OrderBill($_GET['billID']);
if (! $OrderBill->billID) {
    core::raise('Böyle bir borç kaydı bulunamadı.', 'e');
    redirect('?p=515');
}
if ($_POST["action"] == "update") {
    $_POST['dateDue'] = core::str2time($_POST['dateDue']);
    $_POST['dateStart'] = core::str2time($_POST['dateStart']);
    $_POST['dateEnd'] = core::str2time($_POST['dateEnd']);
    // mark as paid
    if ($OrderBill->status == 'unpaid' && $_POST['status'] == 'paid') {
        $OrderBill->pay();
        if ($_POST['updateOrderDateEnd'] == '1') {
            $OrderBill->updateOrderDateEnd();
        }
    }
    $OrderBill->replace($_POST)->update();
    echo json_encode(array('st' => true));
    exit();
}

if ($OrderBill->orderID) {
    // sipariş borcu
    $Order = new Order($OrderBill->orderID);
    $core->assign('Order', $Order);
    $clientID = $Order->clientID;
    $page_title .= ' <a href="index.php?p=411&orderID=' . $OrderBill->orderID . '">' . $Order->title . '</a> &raquo;';
} elseif ($OrderBill->clientID) {
    // genel borç
    $clientID = $OrderBill->clientID;
}
$Client = new Client($clientID);
$core->assign('Client', $Client);

$OrderBill->checkPrevious();

$OrderBill->dateDue = date('d-m-Y', $OrderBill->dateDue);
$OrderBill->dateStart = date('d-m-Y', $OrderBill->dateStart);
$OrderBill->dateEnd = date('d-m-Y', $OrderBill->dateEnd);


$core->assign('OrderBill', $OrderBill);
$tpl_content = "bill_details";


$page_title .= ' <a href="index.php?p=516&billID=' . $OrderBill->billID . '">' . $OrderBill->billID . ' Nolu Borç Detayları</a>';
