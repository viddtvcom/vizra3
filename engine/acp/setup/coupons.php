<?php
$action_menu[] = array('Kuponlar', '?p=177');
$action_menu[] = array('Yeni Kupon Ekle', '?p=177&act=add');


switch ($_GET["act"]) {

    case 'add':
        if ($_POST["action"] == "add") {
            if ($_POST['amount'] > 100 || $_POST['amount'] < 1) {
                core::raise(
                    'İndirim Oranı 1 ile 100 arasında olmalıdır',
                    'e',
                    'rt'
                );
            }
            if ($db->query(
                "SELECT couponID FROM coupons WHERE code = '" . $_POST['code'] . "'",
                SQL_INIT,
                'couponID'
            )
            ) {
                core::raise('Bu kod zaten mevcut', 'e', 'rt');
            }
            $_POST['dateExpires'] = $_POST['dateExpires'] ? core::str2time($_POST['dateExpires']) : 0;

            $CPN = new Coupon();
            $CPN->create();
            $CPN->replace($_POST)->update();
            redirect('index.php?p=177&couponID=' . $CPN->couponID);
        }
        $services = $db->query("SELECT * FROM services WHERE status = 'active' ORDER BY service_name", SQL_ALL);
        $core->assign('services', $services);
        $tpl_content = "add_coupon";
        break;
    case 'del':
        $CPN = new Coupon($_GET['couponID']);
        $CPN->destroy();
        core::raise('Kupon sistemden silindi', 'm', 'rt');
        break;
    default:
        $coupons = $db->query("SELECT * FROM coupons", SQL_ALL);
        $core->assign("coupons", $coupons);

        $tpl_content = "coupons";
        break;
}



