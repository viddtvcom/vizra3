<?php

$action_menu[] = array('Yeni Servis Ekle', '?p=115&act=add_service');
$action_menu[] = array('Yeni Grup Ekle', '?p=117&act=add_group');
$action_menu[] = array('##page_120##', '?p=120');


switch ($_GET["act"]) {
    case 'move':
        core::moveRow("service_groups", "groupID", $_GET["groupID"], $_GET["dir"]);
        redirect('?p=115');
        break;
    case 'move_service':
        Service::moveRow("services", "serviceID", $_GET["serviceID"], $_GET["dir"], $_GET["groupID"]);
        redirect('?p=115');
        break;

    case 'delGroup':
        $services = $db->query(
            "SELECT serviceID FROM services WHERE groupID = " . $_GET['groupID'] . " LIMIT 1",
            SQL_INIT
        );
        if ($services) {
            core::raise(
                'Altında servis bulunan bir grubu silemezsiniz. Öncelikle bu servisleri silmeniz gerekmektedir',
                'e',
                '?p=115'
            );
        } else {
            $db->query("DELETE FROM service_groups WHERE groupID = " . $_GET['groupID']);
            core::raise('Grup silindi', 'm', '?p=115');
        }
        break;
    case 'add_service':
        if ($_POST["action"] == "add") {
            if ($_POST['service_name'] == '') {
                core::raise('Geçerli bir servis adı girmelisiniz', 'e', '?p=115&act=add_service');
            } elseif ($_POST['groupID'] == 1) {
                core::raise('Genel kategorisine servis ekleyemezsiniz', 'e', '?p=115&act=add_service');
            }
            if ($_POST["moduleID"] != '') {
                $modConfig = Module::getConfig($_POST["moduleID"]);
            }
            if ($_POST["moduleID"] != '' && ! in_array($_POST['type'], $modConfig['sys']['stypes']->value)) {
                core::raise('Seçtiğiniz Modül bu servis tipini desteklemiyor', 'e');
            } else {
                $Service = new Service();
                $Service->create();
                $_POST['seolink'] = finename($_POST['service_name']);
                $Service->replace($_POST)->update();
                $Service->bindModule($_POST["moduleID"]);
                $Service->setRowOrder();

                if ($Service->serviceID) {
                    iredirect("service_details", $Service->serviceID);
                } else {
                    core::error("service_add");
                }
            }
        }
        $select_groups = $db->query(
            "SELECT * FROM service_groups WHERE groupID NOT IN(1,10) ORDER BY rowOrder",
            SQL_ALL
        );
        $core->assign(
            "select_groups",
            array2select($select_groups, "groupID", "groupID", "group_name", $_POST['groupID'])
        );
        $core->assign('modules', Module::getModuleTitles('service'));

        $tpl_content = "add_service";
        break;

    case 'duplicateService':
        $Service = new Service($_GET['serviceID']);
        $Service->duplicate();
        core::raise('Service kopyalandı', 'm');
        redirect('?p=115');
        break;
    case 'delService':
        $check = $db->query(
            "SELECT orderID FROM orders WHERE serviceID = " . $_GET['serviceID'] . " LIMIT 1",
            SQL_INIT
        );
        if ($check) {
            core::raise('Bu servisi kullanan siparişler olduğu için sistemden silemezsiniz', 'e', 'rt');
        } else {
            $Service = new Service($_GET['serviceID']);
            if ($Service->serviceID) {
                $Service->destroy();
                core::raise($Service->service_name . ' &raquo; sistemden silindi', 'm');
            }
        }


    default:
        $sql = "SELECT groupID,group_name FROM service_groups WHERE parentID = 1 AND groupID != 10 ORDER BY rowOrder ASC";
        $groups = $db->query($sql, SQL_KEY, 'groupID');


        if ($_GET['groupID'] > 0) {
            $sql = "SELECT *,s.status AS status,s.rowOrder AS srowOrder FROM services s
                    INNER JOIN service_groups sg ON s.groupID = sg.groupID
                    WHERE s.groupID != 10 AND (sg.parentID = " . $_GET['groupID'] . " OR sg.groupID = " . $_GET['groupID'] . ")";

            $order_by = " ORDER BY sg.rowOrder,addon,s.rowOrder";
        } else {
            $sql = "SELECT *,s.status AS status,s.rowOrder AS srowOrder FROM services s
                    INNER JOIN service_groups sg ON s.groupID = sg.groupID
                    WHERE s.groupID != 10999";

            $order_by = " ORDER BY sg.rowOrder,addon,s.rowOrder,sg.groupID";
        }

        if ($_GET['srv_status'] != 'all') {
            $sql .= " AND s.status = 'active'";
        }

        if ($_GET['order_type'] != '') {
            $sql .= " AND s.addon = '" . (($_GET['order_type'] == 'addon') ? '1' : '0') . "'";
        }


        $core->assign('groups', $groups);


        $services = $db->query($sql . $order_by, SQL_MKEY, 'groupID', 'serviceID');

        foreach ($groups as $groupID => $g) {
            if ($_GET['groupID'] && $groupID != $_GET['groupID']) {
                continue;
            }
            $servgrps[$groupID] = $services[$groupID];
        }

        /*        foreach ($services as $service) {
                    $servgrps[$service['groupID']][] = $service;
                }  */

        $core->assign('servgrps', $servgrps);

        $tpl_content = "services";
        break;
}




