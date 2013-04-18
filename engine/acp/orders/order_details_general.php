<?php

if ($_POST["action"] == "update") {
    $_POST['dateStart'] = core::str2time($_POST['dateStart']);
    if ($_POST['never_expires'] == '1') {
        $_POST['dateEnd'] = 0;
    } else {
        $_POST['dateEnd'] = core::str2time($_POST['dateEnd']);
        if ($_POST['dateEnd'] < $_POST['dateStart']) {
            unset($_POST['dateEnd']);
            unset($_POST['dateStart']);
            core::raise('Bitiş tarihi, başlangıç tarihinden küçük olamaz. Tarih bilgileri güncellenmedi', 'e');
        }
    }

    $Order->replace($_POST)->update();
    core::raise('Sipariş detayları güncellendi', 'm');

} elseif ($_POST['action'] == 'sendmail') {
    $EM = new Email_template($_POST['templateID']);
    $EM->orderID = $_GET['orderID'];
    $EM->send();
    core::raise('Email gönderildi: ' . $EM->title, 'm', '');

}


$Order->dateStart = date('d-m-Y', $Order->dateStart);
$Order->dateEnd = $Order->dateEnd ? date('d-m-Y', $Order->dateEnd) : '';

/*if ($Order->couponID) {
    $CPN = new Coupon($Order->couponID);
    if ($CPN->active) {
        $Order->price_discounted = $Order->price * (1 - $CPN->amount / 100);
    }
    $core->assign('CPN',$CPN);
}*/

$core->assign('order_emails', Email_template::getEmails(array('order', 'welcome', 'custom')));
$core->assign('coupons', $db->query("SELECT * FROM coupons ORDER BY code", SQL_ALL));