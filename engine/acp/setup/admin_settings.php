<?php

if ($_POST['action'] == 'uploadAvatar') {
    $ret = core::uploadImage($_FILES["avatar"]["tmp_name"], $_SESSION["vadmin"]->getAvatarName(), 'avatar');
    echo json_encode($ret);
    exit();

} elseif ($_POST["action"] == "update") {
    $settings = $db->query("SELECT * FROM admin_setting_types WHERE grp != 'hidden'", SQL_ALL, "settingID");
    foreach ($settings as $k => $v) {

        $sql = "INSERT INTO admin_settings (settingID,adminID,value) VALUES ('" . $k . "'," . getAdminID(
        ) . ",'" . $_POST['values'][$k] . "')
                ON DUPLICATE KEY UPDATE value = '" . $_POST['values'][$k] . "'";

        $db->query($sql);
    }
    redirect('login.php?p=1');
} elseif ($_POST['action'] == 'addQrep') {
    $db->query("INSERT INTO admin_qreps (adminID,reply) VALUES (" . ADMINID . ",'" . $_POST['reply'] . "')");
    redirect('?p=112&tab=qreps');
} elseif ($_GET['act'] == 'delQrep') {
    $db->query("DELETE FROM admin_qreps WHERE qrepID = " . $_GET['qrepID'] . " AND adminID = " . ADMINID);
    redirect('?p=112&tab=qreps');
}


switch ($_GET['tab']) {
    case 'qreps':
        $core->assign('qreps', $_SESSION['vadmin']->getQreps());
        break;

    default:
        $groups = (array)$db->query("SELECT DISTINCT grp FROM admin_setting_types WHERE grp != 'hidden'", SQL_ALL);
        foreach ($groups as $g) {
            $sql = "SELECT *,ast.settingID FROM admin_setting_types ast 
                        LEFT JOIN admin_settings ase ON (ase.settingID = ast.settingID AND ase.adminID = " . getAdminID(
            ) . ")
                    WHERE grp = '" . $g["grp"] . "'  ORDER BY rowOrder ASC";

            $items = $db->query($sql, SQL_ALL);

            foreach ($items as $k => $v) {
                if ($v['type'] == 'combobox') {
                    $values = explode("\r\n", $v["values"]);
                    $items[$k]["values"] = array();
                    foreach ($values as $k1 => $v1) {
                        $val2 = explode(",", $v1);
                        $items[$k]["values"][$val2[0]] = $val2[1];
                    }
                }
                //$items[$k]["selected"] = ($items[$k]["encrypted"] == "1") ? core::decrypt($items[$k]["selected"]) : $items[$k]["selected"];    
            }

            $settings[$g["grp"]] = $items;
        }

        $core->assign("settings", $settings);
}
$tpl_content = "admin_settings";

$page_title .= ' &raquo; ' . $_SESSION['vadmin']->adminName;




