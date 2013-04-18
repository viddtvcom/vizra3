<?php

$action_menu[] = array('Yeni Grup Ekle', '?p=117&act=add_group');

switch ($_GET["act"]) {

    case 'add_group':
        if ($_POST["action"] == "add") {
            if ($_POST['group_name'] == '') {
                core::raise('Bir grup adı girmelisiniz', 'e', '?p=117&act=add_group');
            }
            $groupID = addServiceGroup($_POST['parentID'], $_POST['status'], $_POST['group_name']);
            redirect('index.php?p=117&act=group_details&groupID=' . $db->lastInsertID());
        } else {
            $groups = $db->query("SELECT * FROM service_groups WHERE parentID = 1", SQL_ALL);
            $core->assign('groups', $groups);
            $tpl_content = "add_service_group";
        }
        break;

    default:
        if ($_POST["action"] == "update") {
            $sql = "UPDATE service_groups SET group_name = '" . $_POST['group_name'] . "',seolink = '" . $_POST['seolink'] . "',
                                              description = '" . sanitize($_POST['description'], false) . "',
                                              status = '" . $_POST['status'] . "',
                                              parentID = '" . $_POST['parentID'] . "'
                    WHERE groupID = " . $_GET['groupID'];
            $db->query($sql);
            redirect('index.php?p=117&act=group_details&groupID=' . $_GET['groupID']);
        } else {
            $group = $db->query("SELECT * FROM service_groups WHERE groupID = " . $_GET['groupID'], SQL_INIT);
            $core->assign('g', $group);

            $groups = $db->query("SELECT * FROM service_groups WHERE parentID = 1", SQL_ALL);
            $core->assign('groups', $groups);

            $tpl_content = "service_group_details";
        }
}




