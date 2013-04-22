<?php

$servers = $db->query("SELECT * FROM servers WHERE status = 'active' AND moduleID = 'cpanel'", SQL_ALL);
$core->assign('servers', $servers);


if ($_POST['action'] == 'list') {

    $MOD = Module::getInstance('cpanel');
    $MOD->setServer($_POST['serverID']);
    $ret = $MOD->listaccts($_POST['owner']);


    if ($ret['st']) {
        $XML = new XmlToArray($ret['raw']);
        $accs = $XML->createArray();
        $accs = $accs['listaccts']['acct'];

        foreach ($accs as $k => $a) {
            $sql = "SELECT  oa.orderID
					FROM order_attrs oa
						INNER JOIN orders o ON o.orderID = oa.orderID
					WHERE oa.setting = 'domain'
						AND o.status IN ('active', 'suspended')
						AND oa.value = '" . $a['domain'] . "'";
            $orderID = $db->query($sql, SQL_INIT, 'orderID');
            $accs[$k]['orderID'] = $orderID;

            $dns_a = dns_get_record($a['domain'], DNS_A);
            $accs[$k]['ip'] = $dns_a[0]['ip'];
        }
        $core->assign('accs', $accs);

        $sql = "SELECT s.serviceID,s.service_name,spo.period FROM services s
                    INNER JOIN service_price_options spo ON spo.serviceID = s.serviceID
                WHERE s.moduleID = 'cpanel' AND s.addon != '1' AND s.type = 'shared'";
        $services = $db->query($sql, SQL_ALL);
        $core->assign('services', $services);

        $clients = $db->query("SELECT name,clientID,type,company FROM clients ORDER BY name ASC", SQL_KEY, "clientID");
        $core->assign('clients', $clients);
    }
} elseif ($_POST['action'] == 'import') {
    $MOD = Module::getInstance('cpanel');
    $MOD->setServer($_POST['serverID']);
    $ret = $MOD->listaccts();

    $XML = new XmlToArray($ret['raw']);
    $accs = $XML->createArray();
    $accs = $accs['listaccts']['acct'];
    $accs = array_rekey($accs, 'user');

    /*    debug($_POST);
        debug($accs,1);   */
    foreach ($_POST['selected'] as $k => $user) {
        $p = $accs[$user];
        $bw = $MOD->showbw($user);
        $serv = explode(',', $_POST['serviceID'][$user]);

        $attrs['domain'] = $p['domain'];
        $attrs['username'] = $user;
        $attrs['cpanel_quota'] = rtrim($p['disklimit'], 'M');
        $attrs['cpanel_bwlimit'] = $bw['limit'];
        $attrs['cpanel_acc_type'] = 'shared';
        $attrs['cpanel_maxsql'] = $p['maxsql'] == 'unlimited' ? '' : $p['maxsql'];
        $attrs['cpanel_maxpop'] = $p['maxpop'] == 'unlimited' ? '' : $p['maxpop'];
        $attrs['cpanel_maxftp'] = $p['maxftp'] == 'unlimited' ? '' : $p['maxftp'];
        $attrs['cpanel_maxsub'] = $p['maxsub'] == 'unlimited' ? '' : $p['maxsub'];
        $attrs['cpanel_plan'] = $p['plan'];

        $Order = new order();
        $Order->dateStart = $p['unix_startdate'] - 60 * 60 * 24 * 200;
        $Order->create($serv[0], $_POST['clientID'], $serv[1]);
        $Order->start(0, true, false, false, false);
        $Order->updateAttrs($attrs);
        $Order->setTitle();
        $Order->status = $p['suspended'] == '0' ? 'active' : 'suspended';
        $Order->serverID = $_POST['serverID'];

        $Order->dateAdded = $p['unix_startdate'];
        //$Order->createNextBill(0,'paid',0);
        $Order->update();

        core::raise($attrs['domain'] . ': import edildi', 'm');
    }

    redirect('?p=620');

}

$tpl_content = 'whm_import.tpl';

