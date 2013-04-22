<?php


if ($_POST['action'] == 'list') {

    $domains = explode("\r\n", trim($_POST['domains']));


    foreach ($domains as $dom) {
        if ($dom == '') {
            continue;
        }
        $orderID = $db->query("SELECT orderID FROM domains WHERE domain = '" . $dom . "'", SQL_INIT, 'orderID');
        $_domains[$dom] = $orderID;
    }

    if (empty($_domains)) {
        core::raise('Lütfen domainleri altalta gelecek şekilde giriniz', 'e', 'rt');
    }

    $core->assign('domains', $_domains);

    $clients = $db->query(
        "SELECT name,clientID,type,company FROM clients WHERE status = 'active' ORDER BY name ASC",
        SQL_KEY,
        "clientID"
    );
    $core->assign('clients', $clients);

    $modules = Module::getActiveModules('domain');
    $core->assign('modules', array_merge(array('none' => '##None##'), $modules));

} elseif ($_POST['action'] == 'import') {

    foreach ($_POST['selected'] as $k => $domain) {
        $clientID = $_POST['clientID'][$domain];
        $moduleID = ($_POST['moduleID'][$domain] == 'none') ? '' : $_POST['moduleID'][$domain];

        $extdata = Domain::getExtensionData(Domain::getExtensionFromDomain($domain));

        $Domain = new Domain();
        $Domain->create($domain);

        $Order = new order();
        $Order->create($Domain->extensionData["serviceID"], $clientID, 12, $Domain);
        $Order->status = 'active';
        $Order->setTitle();

        $Domain->orderID = $Order->orderID;
        $Domain->moduleID = $moduleID;
        $Domain->status = 'active';
        $Domain->update();

        if ($moduleID != '') {
            $res = $Domain->refresh();
            if ($res['st'] == true) {
                $Order->dateEnd = $Domain->dateExp;
            }

        }

        $Order->update();

        core::raise($domain . ': sisteme import edildi', 'm');
    }

    redirect('?p=420');

}

$tpl_content = 'domain_import_generic.tpl';

