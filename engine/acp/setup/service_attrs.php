<?php
$action_menu[] = array('Yeni Özellik Ekle', '?p=120&act=add');

switch ($_GET["act"]) {

    case 'add':
        if ($_POST["action"] == "addSetting") {
            if ($_POST['setting'] == '' || preg_match('/[^A-Za-z0-9_]/', $_POST['setting'])
                    || $db->query(
                        "SELECT settingID FROM service_attr_types WHERE setting = '" . $_POST['setting'] . "'",
                        SQL_INIT,
                        'settingID'
                    )
            ) {
                core::raise('Geçersiz ID', 'e');
                $core->assign('data', $_POST);
            } else {
                $Setting = new Setting();
                $Setting->create();
                $Setting->replace($_POST)->update();

                if ($Setting->settingID) {
                    core::raise('Özellik eklendi');
                    redirect('?p=120&act=details&settingID=' . $Setting->settingID);
                } else {
                    core::error("service_attr_add");
                }
            }
        }
        $core->assign('types', Setting::$types);
        $core->iassign("service_groups", $_POST['groupID']);
        $tpl_content = "add_attr";
        break;

    case 'details':
        $Setting = new Setting($_GET['settingID']);
        if ($_POST["action"] == "update") {
            if ($_POST['encrypted'] != '1') {
                $_POST['encrypted'] = '0';
            }
            $Setting->replace($_POST)->update();
            redirect('?p=120&act=details&settingID=' . $Setting->settingID);
        }
        $core->assign('Setting', $Setting);
        $core->assign('types', Setting::$types);
        $core->iassign("service_groups", $Setting->groupID);
        $tpl_content = "service_attr_details";

        $page_title .= " &raquo; <a href='?p=120&act=details&settingID=" . $Setting->settingID . "'>" . $Setting->label . "</a>";
        break;

    case 'del':
        privcheck(4);
        $db->query("DELETE FROM service_attr_types WHERE setting = '" . $_GET['setting'] . "' AND settingID > 100");
        $db->query("DELETE FROM service_attrs WHERE setting = '" . $_GET['attrID'] . "'");
        $db->query("DELETE FROM order_attrs WHERE setting = '" . $_GET['attrID'] . "'");

    default:
        $sql = "SELECT * FROM service_attr_types sat LEFT JOIN service_groups sg ON sg.groupID = sat.groupID WHERE moduleID = ''";
        if ($_GET['groupID'] > 0) {
            $sql .= " AND sat.groupID = " . $_GET['groupID'];
        }
        $attrs = $db->query($sql, SQL_ALL);
        $core->assign("attrs", $attrs);
        $groups = $db->query("SELECT * FROM service_groups WHERE parentID > 0", SQL_ALL);
        $core->assign('groups', $groups);
        $tpl_content = "service_attrs";
        break;
}

