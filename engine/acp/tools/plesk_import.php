<?php

$servers = $db->query("SELECT * FROM servers WHERE status = 'active' AND moduleID = 'plesk'", SQL_ALL);
$core->assign('servers', $servers);


if ($_POST['action'] == 'list') {

    $MOD = Module::getInstance('plesk');
    $MOD->setServer($_POST['serverID']);
    $ret = $MOD->listaccts();

    if ($ret['st']) {
        $XML = new XmlToArray($ret['msg']);
        $accs_raw = $XML->createArray();
        $accs_raw = $accs_raw['packet']['domain'][0]['get'][0]['result'];
        if (! isset($accs_raw[0]['data'])) {
            core::raise('Bu hesap altında kayıt bulunamadı', 'w', '?p=625');
        }
        foreach ($accs_raw as $k => $a) {
            $r['domain'] = $a['data'][0]['gen_info'][0]['name'];
            $orderID = $db->query(
                "SELECT  orderID FROM order_attrs WHERE setting = 'domain' AND value = '" . $r['domain'] . "'",
                SQL_INIT,
                'orderID'
            );
            $r['orderID'] = $orderID;
            $r['startdate'] = strtotime($a['data'][0]['gen_info'][0]['cr_date']);
            $r['user'] = $a['data'][0]['hosting'][0]['vrt_hst'][0]['ftp_login'];
            $accs[] = $r;
        }
        $core->assign('accs', $accs);

        $sql = "SELECT s.serviceID,s.service_name,spo.period FROM services s
                    INNER JOIN service_price_options spo ON spo.serviceID = s.serviceID 
                WHERE s.moduleID = 'plesk' AND s.addon != '1' AND s.type = 'shared'";
        $services = $db->query($sql, SQL_ALL);
        $core->assign('services', $services);

        $clients = $db->query("SELECT name,clientID,type,company FROM clients ORDER BY name ASC", SQL_KEY, "clientID");
        $core->assign('clients', $clients);
    }
} elseif ($_POST['action'] == 'import') {
    $MOD = Module::getInstance('plesk');
    $MOD->setServer($_POST['serverID']);
    $ret = $MOD->listaccts();
    $XML = new XmlToArray($ret['msg']);
    $accs_raw = $XML->createArray();
    $accs_raw = $accs_raw['packet']['domain'][0]['get'][0]['result'];

    foreach ($accs_raw as $k => $a) {
        $user = $a['data'][0]['hosting'][0]['vrt_hst'][0]['ftp_login'];
        $accs[$user] = $a;
    }
    /*    debug($_POST);
        debug($accs,1);*/
    foreach ($_POST['selected'] as $k => $user) {
        $serv = explode(',', $_POST['serviceID'][$user]);
        $limits = $accs[$user]['data'][0]['limits'][0];

        $attrs['domain'] = $accs[$user]['data'][0]['gen_info'][0]['name'];
        $attrs['password'] = $accs[$user]['data'][0]['hosting'][0]['vrt_hst'][0]['ftp_password'];
        $attrs['username'] = $user;
        $attrs['plesk_disk_space'] = intval($limits['disk_space'] / (1024 * 1024));
        $attrs['plesk_max_traffic'] = intval($limits['max_traffic'] / (1024 * 1024));
        $attrs['plesk_max_db'] = $limits['max_db'] == '-1' ? '' : $limits['max_db'];
        $attrs['max_mssql_db'] = $limits['mssql_db'] == '-1' ? '' : $limits['mssql_db'];
        $attrs['plesk_max_box'] = $limits['max_box'] == '-1' ? '' : $limits['max_box'];
        $attrs['plesk_max_dom_aliases'] = $limits['max_dom_aliases'] == '-1' ? '' : $limits['max_dom_aliases'];
        $attrs['plesk_max_subdom'] = $limits['max_subdom'] == '-1' ? '' : $limits['max_subdom'];

        $Order = new order();
        $Order->dateStart = strtotime($accs[$user]['data'][0]['gen_info'][0]['cr_date']);
        $Order->create($serv[0], $_POST['clientID'], $serv[1]);
        $Order->start(0, true, false, false, false);
        $Order->updateAttrs($attrs);
        $Order->setTitle();
        $Order->status = $accs[$user]['data'][0]['gen_info'][0]['status'] == '0' ? 'active' : 'suspended';
        $Order->serverID = $_POST['serverID'];

        $Order->dateAdded = $Order->dateStart;
        //$Order->createNextBill(0,'paid',0);
        $Order->update();
        core::raise($attrs['domain'] . ': import edildi', 'm');
    }

    redirect('?p=625');


}

$tpl_content = 'plesk_import.tpl';

