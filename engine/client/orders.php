<?php
$core->assign('tab', $_get['tab']);

//$_get["oID"] = intval($_get["oID"]);

switch ($_get["a"]) {
    case 'details':
        if (! preg_match('/^\d{7}$/', $_get['oID'])) {
            core::raise(
                "Böyle bir sipariş bulunamadı",
                "e",
                '?p=user&s=orders'
            );
        }
        //$Order = new Order($_get["oID"]);
        $Order = getClientOrder($_get['oID']);

        if (! $Order) {
            core::raise("Böyle bir sipariş bulunamadı", "e");
            redirect("?p=user&s=orders");
        } elseif ($Order->parentID) {
            redirect('?p=user&s=orders&a=details&oID=' . $Order->parentID);
        }
        $Order->loadService();

        switch ($_get['tab']) {
            case 'files':
                if ($Order->status == 'active') {
                    $files = $Order->Service->getFiles();
                    $core->assign('files', $files);
                }
                break;
            case 'bills':
                $Order->getBills('all', $_get['boID']);
                $addons = $db->query(
                    "SELECT orderID,title FROM orders WHERE parentID = " . $Order->orderID,
                    SQL_KEY,
                    'orderID'
                );
                $core->assign('addons', $addons);
                break;

            case 'addons':
                if (! $Order->parentID) {
                    $Order->addonOrders = $Order->loadAddonOrders();
                    /*                    $sql = "SELECT * FROM services WHERE status = 'active' AND  addon = '1' AND groupID = ".$Order->Service->groupID;
                                        $addonServices = $db->query($sql,SQL_ALL);*/

                    $addons = $Order->Service->getAddons();
                    foreach ($addons as $k => $ao) {
                        $aob = Service::newInstance($ao['serviceID'], false)->objectFromArray($ao);
                        $aob->getPriceOptions();
                        $price = array_slice($aob->priceOptions, 0, 1, true);
                        list ($addons[$k]['period'], $addons[$k]['price']) = each($price);
                    }

                    /*if (isAdmin())

                    else
                    $core->assign('addonServices', $Order->Service->getAddons($Order->payType));*/

                    $core->assign('addonServices', $addons);
                }
                break;

            case 'attrs':
                if ($Order->Service->groupID == 10) {
                    exit();
                }
                $Order->loadAttrs()->loadAddonAttrs();

                if ($Order->serverID) {
                    $Order->Server = new Server($Order->serverID);
                    $Order->Server->loadSettings();
                }

                if ($Order->Service->moduleID) {
                    $Order->loadModuleCmds()->loadModuleLinks();
                    if ($_post["action"] == "moduleCmd" && $Order->Service->moduleID && in_array(
                        $_post["cmd"],
                        $Order->moduleUserCmds
                    )
                    ) {
                        $result = $Order->moduleQueueCmd($_post["cmd"], $_post["srv"]);
                        core::raise(
                            'İşleminiz arka planda çalışmaktadır. Lütfen 5dk içinde paket bilgilerinizi tekrar kontrol ediniz',
                            'm'
                        );
                        redirect('?p=user&s=orders&a=details&tab=attrs&oID=' . $Order->orderID);
                    }
                    $modConfig = $Order->getModuleConfig();
                }

                $AllAttrs = array_merge($Order->getAttributes(), (array)$modConfig['srvc']);
                foreach ($AllAttrs as $key => $obj) {
                    $obj->value = (! is_numeric(
                        $Order->attrs[$key]['value']
                    )) ? $Order->attrs[$key]['value'] : $Order->attrs[$key]['value'] + $Order->addon_attrs[$key]['value'];
                    if ($Order->attrs[$key]['clientCanSee'] != '1') {
                        unset($AllAttrs[$key]);
                    }
                    if (($obj->cmd != '' && ! in_array(
                        $obj->cmd,
                        $Order->moduleUserCmds
                    )) || $Order->status != 'active'
                    ) {
                        $obj->cmd = '';
                    }
                }
                $core->assign('srv', $AllAttrs);
                $core->assign('userCmds', $Order->moduleUserCmds);

                break;

            case 'domain':
                if ($Order->Service->groupID != 10) {
                    exit();
                }
                $Domain = Domain::newInstanceByOrderID($Order->orderID);

                if ($Domain->status == 'active' && $_post['action'] == 'update') {
                    if ($_post['ns1'] . $_post['ns2'] != $Domain->ns1 . $Domain->ns2) {
                        $ret = $Domain->setDNS($_post['ns1'], $_post['ns2']);
                        if (! $ret['st']) {
                            core::raise($ret['msg'], 'e');
                        } else {
                            core::raise('İşlem başarı ile tamamlandı', 'm');
                        }
                    }
                    if ($_post['contacts']) {
                        $ret = $Domain->setDomainContacts(Contact::convertRegistrarData($_post['contacts']));
                        if (! $ret['st']) {
                            core::raise($ret['msg'], 'e');
                        } else {
                            core::raise('Alan adı kontak bilgileri güncellendi', 'm');
                        }
                    }

                }
                if ($Domain->hasModule) {
                    $contact_types = $Domain->getContactTypes();
                    $sql = "SELECT * FROM client_contacts cc
                                INNER JOIN domain_contacts dc ON (dc.contactID = cc.contactID) 
                            WHERE (cc.clientID = " . CLIENTID . ")  AND dc.domainID = " . $Domain->domainID;
                    $contact_details = $db->query($sql, SQL_ALL, 'type');

                    $core->assign('contact_details', $contact_details);
                    $core->assign('contact_types', $contact_types);
                    $core->assign('contacts', Contact::getClientContacts(CLIENTID));
                }
                $core->assign("Dom", $Domain);
                break;
        }
        if ($Order->parentID) {
            $core->assign('Parent', new Order($Order->parentID));
        }
        $core->assign("Order", $Order);
        $core->assign('icons', Order::$icons);
        $tplContent = "user/order_details.tpl";


        break;

    default:
        $core->assign("orders", $_SESSION['vclient']->getClientOrders($_get['t']));
        $core->assign('icons', Order::$icons);
        $tplContent = "user/orders.tpl";
        break;
}
 
  