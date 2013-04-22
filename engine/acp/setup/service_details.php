<?php

$action_menu[] = array('Yeni Servis Ekle', '?p=115&act=add_service');

$Service = new service($_GET["serviceID"]);
if (! $Service->serviceID) {
    core::error("service_load");
}

if ($_GET['act'] == 'remPrcOpt') {
    $Service->removePriceOption($_GET['period']);
    redirect('?p=116&serviceID=' . $Service->serviceID . '&tab=payment');

} elseif ($_POST["action"] == "update") {
    if ($_POST['notifyOnOrder'] != '1') {
        $_POST['notifyOnOrderDepID'] = 0;
    }
    $Service->replace($_POST)->update();
    if ($_FILES["file"]["size"] > 0) {
        core::uploadImage(
            $_FILES["file"]["tmp_name"],
            $Service->getAvatarName(),
            'service'
        );
    }

} elseif ($_POST["action"] == "addPriceOption") {
    $Service->addPriceOption($_POST['period'], $_POST["price"]);

} elseif ($_POST["action"] == "updatePriceOptions") {
    /*
    *   Siparis ucretlerini güncelle
    */
    if ($_POST['update_orders'] == '1' && isset($_POST['update_order_type'])) {
        $sql = "SELECT * FROM service_price_options WHERE serviceID = " . $Service->serviceID . " ORDER BY period ASC";
        $price_options = $db->query($sql, SQL_KEY, "period");

        // bu hizmete ait siparisleri getir
        $orders = $db->query("SELECT * FROM orders WHERE serviceID = " . $Service->serviceID, SQL_ALL);
        foreach ($orders as $order) {
            if (! isset($price_options[$order['period']]) || ! isset($_POST['options'][$order['period']])) {
                continue;
            }

            $update = true;
            $orig_price = $price_options[$order['period']]['price'] - $price_options[$order['period']]['discount'];
            $new_price = $_POST["options"][$order['period']]['price'] - $_POST["options"][$order['period']]['discount'];

            if ($_POST['update_order_type'] == '1' && $orig_price != $order['price']) { // fiyat degismis, update etme
                $update = false;
            }

            if ($update == true) {
                $db->query(
                    "UPDATE orders SET price = " . $new_price . " WHERE orderID = " . $order['orderID'] . " LIMIT 1"
                );
                $cnt['order'] ++;

                if ($_POST['update_bill_type'] != 'none') {
                    $sql = "SELECT billID FROM order_bills WHERE orderID = " . $order['orderID'] . " AND type = 'recurring' AND status = 'unpaid'";
                    if ($_POST['update_bill_type'] == 'last') {
                        $sql .= " ORDER BY dateDue DESC LIMIT 1";
                    }
                    $bills = $db->query($sql, SQL_KEY, 'billID');
                    foreach ($bills as $billID) {
                        $OBill = new OrderBill();
                        if ($OBill->load($billID) != false) {
                            $OBill->amount($new_price);
                            $OBill->update();
                            $cnt['bill'] ++;
                        }
                    }
                }
            }
        }

        if ($cnt['order'] > 0) {
            core::raise($cnt['order'] . ' adet sipariş güncellendi', 'm');
        }
        if ($cnt['bill'] > 0) {
            core::raise($cnt['bill'] . ' adet borç kaydı güncellendi', 'm');
        }

        //debug($cnt);

    }

    foreach ((array)$_POST["options"] as $period => $data) {
        if ($data['discount'] >= $data['price']) {
            core::raise('İndirim, normal fiyattan büyük veya eşit olamaz', 'e');
            continue;
        }
        $default = $_POST['default_option'] == $period ? '1' : '0';
        $sql = "UPDATE service_price_options SET price = '" . $data['price'] . "', discount = '" . $data['discount'] . "', `default` = '" . $default . "'
                WHERE period = " . $period . " AND serviceID = " . $Service->serviceID;
        $db->query($sql);
    }

    $Service->paycurID = $_POST["paycurID"];
    if ($_POST['setup_discount'] > 0 && $_POST['setup_discount'] >= $_POST['setup']) {
        core::raise('İndirim, normal fiyattan büyük veya eşit olamaz', 'e');
    } else {
        $Service->setup = $_POST["setup"];
        $Service->setup_discount = $_POST["setup_discount"];
    }

    $expires = ($_POST['_expires_p'] == 'never') ? 'never' : (int)$_POST['_expires_a'] . $_POST['_expires_p'];


    $Service->expires = $expires;
    $Service->update();
    core::raise('Ödeme Seçenekleri güncellendi', 'm');

} elseif ($_POST['action'] == 'updateFiles') {
    $Service->set('file_cats', implode(',', (array)$_POST['selected']));
}
if ($_POST && $_POST['action'] != 'updateModuleSettings' && $_POST['action'] != 'addAttr') {
    redirect('?p=116&serviceID=' . $Service->serviceID . '&tab=' . $_GET['tab']);
}

switch ($_GET['tab']) {
    case 'payment':
        $core->assign(
            "select_currencies",
            array2select(core::get_currencies(), "paycurID", "curID", "symbol", $Service->paycurID)
        );
        $sql = "SELECT * FROM service_price_options WHERE serviceID = " . $Service->serviceID . " ORDER BY period ASC";
        $Service->priceOptions = $db->query($sql, SQL_KEY, "period");

        if ($Service->expires != 'never') {
            $Service->_expires_p = substr($Service->expires, - 1, 1);
            $Service->_expires_a = str_replace($Service->_expires_p, '', $Service->expires);
        } else {
            $Service->_expires_p = 'never';
        }
        $core->assign('cursymbol', getCurrencyById($Service->paycurID));
        break;

    case 'attrs':
        require('service_details_attrs.php');
        break;

    case 'files':
        $cats = Download::getCategoryList(Download::getCategoryTree());
        //$hidden_cats = $db->query("SELECT catID FROM dc_cats WHERE visibility = 'admin'",SQL_KEY,'catID');
        foreach ($cats as $c) {
            /*            if (in_array($c['catID'],$hidden_cats)) {
                            $ret[] = $c;
                        } */
            if ($c['catID'] == 0) {
                continue;
            }
            $ret[] = $c;
        }
        $Service->file_cats = explode(',', $Service->file_cats);
        $core->assign('cats', $ret);
        break;

    default:
        $select_groups = $db->query("SELECT * FROM service_groups WHERE parentID > 0", SQL_ALL);
        $core->assign(
            "select_groups",
            array2select($select_groups, "groupID", "groupID", "group_name", $Service->groupID)
        );
        $emails = $db->query("SELECT * FROM settings_email_templates WHERE type = 'welcome'", SQL_ALL);
        $core->assign('emails', $emails);

        $deps = $db->query("SELECT * FROM departments ORDER BY depTitle", SQL_KEY, 'depID');
        $core->assign('deps', $deps);

    //$Service->type = $vars['SERVICE_TYPES'][$Service->type];
}


$core->assign("Service", $Service);
$tpl_content = "service_details";


$page_title .= ' &raquo; ' . $Service->service_name;




