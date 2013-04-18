<?php
require_once("func.domain.php");

switch ($_get['s']) {

    case 'domain':
        $extensions = getDomainExtensions();
        if (empty($extensions)) {
            core::raise('Şu an sistemimizde alan adı tescil hizmeti verilmemektedir.', 'm');
            $tplContent = 'blank.tpl';
        } else {
            if ($_get["a"] == "check" || $_get["a"] == "") {
                $tplContent = "domains.tpl";
                if ($_post) {
                    if ($_post["domain"] == '') {
                        core::raise('Lütfen geçerli bir alan adı giriniz', 'e', '?p=shop&s=domain');
                    }
                    $core->assign("post", true);
                    $_domains = str_replace(array("\n", "\r\n"), ",", sanitize($_POST['domain'], true, false));
                    $_domains = explode(",", $_domains);
                    foreach ($_domains as $_domain) {
                        $_domain = trim($_domain);
                        if ($_domain == '') {
                            continue;
                        }
                        foreach ($_post['ext'] as $ext) {
                            if ($cnt ++ > 20) {
                                continue;
                            }
                            $extensions[$ext]["domain"] = substr(strtolower($_domain), 0, 80) . "." . $ext;
                            $extensions[$ext]["ext"] = $ext;
                            $extensions[$ext]['priceRegister'] = number_format($extensions[$ext]['priceRegister'], 2);
                            $results[] = $extensions[$ext];
                        }

                    }
                    //debug($results,1);
                    $core->assign("results", $results);
                    $core->assign('defaultns', array(1 => getSetting('domains_ns1'), 2 => getSetting('domains_ns2')));
                }
            } elseif ($parms[2] == "transfer") {
                $tplContent = "domain/formDomainCheckTransfer.tpl";

            }
        }

        break;

    case 'sf':
        $tplContent = "sf_category.tpl";
        $groupID = intval($_get["gID"]);
        if ($groupID == 10) {
            redirect('?p=shop&s=domain');
        }

        $sql = "SELECT s.*,MD5(CONCAT(s.serviceID,'-',s.dateAdded)) as avatar FROM services s
                    INNER JOIN service_groups sg ON sg.groupID = s.groupID
                  WHERE (s.groupID = " . $groupID . " OR sg.parentID = " . $groupID . ") AND sg.groupID != 10 AND s.addon != '1' AND s.status = 'active'
                  ORDER BY s.rowOrder ASC";
        $services = $db->query($sql, SQL_KEY, 'serviceID');

        $price_options = $db->query(
            "SELECT * FROM service_price_options WHERE `default` = '1'  ORDER BY period ASC",
            SQL_MKEY,
            'serviceID'
        );
        foreach ($services as $serviceID => $data) {
            if ($data['setup_discount'] > 0) {
                $services[$serviceID]['setup'] -= $data['setup_discount'];
                $services[$serviceID]['onsale'] = true;
            }

            if (! $price_options[$serviceID]) {
                continue;
            }
            $price = $price_options[$serviceID][0];
            if ($price['discount'] > 0) {
                $price['price'] -= $price['discount'];
                $services[$serviceID]['onsale'] = true;
            }
            $price['display'] = displayPrice($price['price'], $price['period'], $data['paycurID'], 1);
            $price['display2'] = explode(' - ', $price['display']);
            $services[$serviceID]['price'] = $price;

        }

        $group = $db->query("SELECT * FROM service_groups WHERE groupID = " . $groupID, SQL_INIT);
        $core->assign('group', $group);
        $core->assign('title', $group['group_name']);
        $core->assign("services", $services);
        break;

    case 'srv':
        $Service = new Service($_get['sID']);
        $Service->avatar = md5($Service->serviceID . '-' . $Service->dateAdded);

        $price_options = $db->query("SELECT * FROM service_price_options ORDER BY period ASC", SQL_MKEY, 'serviceID');

        if ($Service->setup_discount > 0) {
            $Service->setup -= $Service->setup_discount;
            $Service->onsale = true;
        }

        $price = $price_options[$Service->serviceID][0];
        if ($price['discount'] > 0) {
            $price['price'] -= $price['discount'];
            $Service->onsale = true;
        }
        $price['display'] = displayPrice($price['price'], $price['period'], $Service->paycurID, 2);
        $Service->price = $price;


        $core->assign('Service', $Service);
        $core->assign('title', $Service->service_name);


        $tplContent = 'sf_service.tpl';
        break;
    default:
        $tplContent = "home.tpl";
        break;
}
 
 
