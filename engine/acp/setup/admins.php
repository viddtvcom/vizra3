<?php

$action_menu[] = array('Yeni Yönetici Ekle', '?p=110&act=add_admin');


switch ($_GET["act"]) {
    case 'del':
        $Admin = new Admin($_GET['adminID']);
        if (! $Admin->adminID) {
            core::raise('Yönetici bulunamadı', 'e', '?p=110');
        } elseif ($Admin->adminID == ADMINID) {
            core::raise('Kendi kaydınızı silemezsiniz', 'e', '?p=110');
        } else {
            $Admin->destroy();
            core::raise($Admin->adminName . ': Yönetici sistemden silindi', 'm', '?p=110');
        }

        break;


    case 'add_admin':
        if (DEMO == true) {
            core::raise('Demo modunda yönetici ekleyemezsiniz', 'e', 'rt');
        }
        if ($_POST["action"] == "add") {
            if ($_POST['adminName'] == '') {
                core::raise(
                    'Geçerli bir yönetici adı girmelisiniz',
                    'e',
                    '?p=110&act=add_admin'
                );
            }
            if (Admin::emailExists($_POST['adminEmail'])) {
                core::raise(
                    'Bu email adresi sistemde zaten kayıtlı',
                    'e',
                    '?p=110&act=add_admin'
                );
            }

            $Admin = new Admin();
            $Admin->create();
            $Admin->replace($_POST)->update();

            if ($Admin->adminID) {
                iredirect("admin_details", $Admin->adminID);
            } else {
                core::error("admin_add");
            }
        } else {

            $tpl_content = "add_admin";
        }
        break;


    default:
        $admins = $db->query("SELECT * FROM admins WHERE adminID > 0", SQL_ALL);
        $core->assign("admins", $admins);

        $tpl_content = "admins";
        break;
}



