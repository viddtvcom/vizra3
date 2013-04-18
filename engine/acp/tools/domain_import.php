<?php



if ($_POST) {
    $pg = isset($_POST['pg']) ? $_POST['pg'] : 1;
    $core->assign('pg', $pg);
    $MOD = Module::getInstance('directi');
    $result = $MOD->listDomains($pg, 10);
    if ($result['recsindb'] > $result['recsonpage']) {
        $pages = ceil($result['recsindb'] / 10);
        $core->assign('pages', $pages);
    }
    foreach ($result as $k => $d) {
        if (! is_array($d) || $d['entity.currentstatus'] != 'Active') {
            continue;
        }

        $dom['dateReg'] = $d['orders.creationtime'];
        $dom['dateExp'] = $d['orders.endtime'];
        $dom['dicustomerID'] = $d['entity.customerid'];
        $dom['orderID'] = $db->query(
            "SELECT orderID FROM domains WHERE domain = '" . $d['entity.description'] . "'",
            SQL_INIT,
            'orderID'
        );

        $domains[$d['entity.description']] = $dom;
    }

    //debug($result);
    $method = $MOD->get('account_method');
    $core->assign('method', $method);
}

if ($_POST['action'] == 'list') {
    if ($result['recsonpage'] > 0) {
        $core->assign('domains', $domains);
    } else {
        core::raise('Bu hesapta bir domain kaydı bulunamadı', 'e');
    }
    $clients = $db->query(
        "SELECT name,clientID,type,company FROM clients WHERE status = 'active' ORDER BY name ASC",
        SQL_KEY,
        "clientID"
    );
    $core->assign('clients', $clients);

} elseif ($_POST['action'] == 'import') {
    $Client = new Client($_POST['clientID']);

    if ($method == 'seperate') {
        $dicustomerID = $Client->getExtra('directi_customerID', true);
        if (! $dicustomerID) {
            $ret = $MOD->addCustomer($Client);
            if (! $ret['st']) {
                core::raise('Müşteri DirectI sistemine eklenemedi:' . $ret['msg'], 'e', '');
            }
            $dicustomerID = $ret['customerID'];
            $Client->setExtra('directi_customerID', $dicustomerID);
        }
    }


    foreach ($_POST['selected'] as $k => $domain) {

        $extdata = Domain::getExtensionData(Domain::getExtensionFromDomain($domain));
        if ($extdata['moduleID'] != 'directi') {
            core::raise(
                $domain . ': Bu domainin uzantısı DirectI Modülü tarafından yönetilmiyor. Domain uzantı ayarlarınızı kontrol ediniz',
                'e'
            );
            continue;
        }

        if ($method == 'seperate' && $domains[$domain]['customerID'] != $dicustomerID) {
            $ret = $MOD->moveWebsite($domain, $dicustomerID);
            if ($ret['st']) {
                core::raise($domain . ': DirectIda müşteri hesabına taşındı', 'm');
            } else {
                core::raise($domain . ': DirectIda müşteri hesabına TAŞINAMADI:' . $ret['msg'], 'e');
            }
        } else {
            $Client->getDefaultContact();
        }


        $Domain = new Domain();
        $Domain->create($domain);

        $Order = new order();
        $Order->create($Domain->extensionData["serviceID"], $Client->clientID, 12, $Domain);
        $Order->status = 'active';
        $Order->setTitle();
        $Order->dateStart = $domains[$domain]['dateReg'];
        $Order->dateEnd = $domains[$domain]['dateExp'];

        $Domain->orderID = $Order->orderID;
        $Domain->update();
        $Domain->refresh();

        $Order->update();


        core::raise($domain . ': sisteme import edildi', 'm');
    }

    redirect('?p=630');

}

$tpl_content = 'domain_import.tpl';

