<?php
$action_menu[] = array('Kuponlar', '?p=177');
$action_menu[] = array('Yeni Kupon Ekle', '?p=177&act=add');

$CPN = new Coupon($_GET['couponID']);
if (! $CPN->couponID) {
    core::error("coupon_load");
}

if ($_POST["action"] == "update") {
    if ($_POST['amount'] > 100 || $_POST['amount'] < 1) {
        core::raise(
            'İndirim Oranı 1 ile 100 arasında olmalıdır',
            'e',
            'rt'
        );
    }
    if ($_POST['code'] != $CPN->code && $db->query(
        "SELECT couponID FROM coupons WHERE code = '" . $_POST['code'] . "'",
        SQL_INIT,
        'couponID'
    )
    ) {
        core::raise('Bu kod zaten mevcut', 'e', 'rt');
    }

    $_POST['dateExpires'] = ($_POST['never_expires'] == '1') ? 0 : ($_POST['dateExpires'] ? core::str2time(
        $_POST['dateExpires']
    ) : time() + 1 * 60 * 60 * 24 * 30);
    $CPN->replace($_POST)->update();
}
if ($_POST) {
    core::raise('Kupon bilgileri güncellendi', 'm', 'rt');
    redirect('?p=178&couponID=' . $CPN->couponID);
}

$services = $db->query("SELECT * FROM services WHERE status = 'active' ORDER BY service_name", SQL_ALL);
$core->assign('services', $services);

$CPN->dateExpires = $CPN->dateExpires ? date('d-m-Y', $CPN->dateExpires) : '';
$core->assign("CPN", $CPN);
$tpl_content = "coupon_details";
//$page_title .= ' &raquo; '. $Dep->depTitle;
