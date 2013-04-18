<?php
$Domain = Domain::newInstanceByOrderID($Order->orderID);

if ($_POST['action'] == 'update_domain_details') {
    // modul yoksa, sadece dbde update et
    if ($Domain->moduleID == '') {
        $_POST['dateReg'] = ($_POST['dateReg']) ? core::str2time($_POST['dateReg']) : 0;
        $_POST['dateExp'] = ($_POST['dateExp']) ? core::str2time($_POST['dateExp']) : 0;
        $Domain->replace($_POST)->update();
    } else {

        if ($Domain->status == 'active') {
            // Update domain status
            $Domain->status = $_POST['status'];
            // DNS Update
            if ($_POST['ns1'] . $_POST['ns2'] != $Domain->ns1 . $Domain->ns2) {
                $ret = $Domain->setDNS($_POST['ns1'], $_POST['ns2']);
                if (! $ret['st']) {
                    core::raise($ret['msg'], 'e');
                } else {
                    core::raise('Alan adı NS bilgileri güncellendi', 'm');
                }
            }
        } else {
            $Domain->replace($_POST);
        }
        $Domain->moduleID = $_POST['moduleID'];
        $Domain->update();
    }
} elseif ($_POST['action'] == 'moduleOperation') {
    if ($_POST['moduleCmd'] == 'sync') {
        $ret = $Domain->refresh();
    }
} elseif ($_POST['action'] == 'register_domain') {
    // Register domain
    $ret = $Domain->register($_POST['period'] * 12);
    if (! $ret['st']) {
        core::raise($ret['msg'], 'e');
    } else {
        core::raise('Alan adı tescil edildi', 'm');
    }
} elseif ($_POST['action'] == 'renew_domain') {
    // Renew domain
    $ret = $Domain->renew($_POST['period'] * 12);
    if (! $ret['st']) {
        core::raise($ret['msg'], 'e');
    } else {
        core::raise('Alan adı yenilendi', 'm');
    }
} elseif ($_POST['action'] == 'refresh_domain') {
    // Renew domain
    $ret = $Domain->refresh();
    if (! $ret['st']) {
        core::raise($ret['msg'], 'e');
    } else {
        core::raise('Alan adı bilgileri senkronize edildi', 'm');
    }
} elseif ($_POST['action'] == 'get_authcode') {
    // Retrieve authcode
    $ret = $Domain->getAuthCode($_POST['send2client']);
    if (! $ret['st']) {
        core::raise($ret['msg'], 'e');
    } else {
        core::raise('Alan adı transfer kodu: ' . $ret['authcode'], 'm');
        if ($_POST['send2client'] == '1') {
            core::raise('Transfer kodu müşteriye gönderildi', 'm');
        }
    }
} elseif ($_POST['action'] == 'update_domlock') {
    // Update domain lock status
    if ($_POST['domlock'] == '1') {
        $ret = $Domain->lock();
    } else {
        $ret = $Domain->unlock();
    }
    if (! $ret['st']) {
        core::raise($ret['msg'], 'e');
    } else {
        core::raise('Alan adı kilit durumu güncellendi', 'm');
    }
}

if (! $Domain->moduleID) {
    $Domain->_dateReg = $Domain->dateReg ? date('d-m-Y', $Domain->dateReg) : '';
    $Domain->_dateExp = $Domain->dateExp ? date('d-m-Y', $Domain->dateExp) : '';

}
$Domain->loadExtensionData();

$core->assign("Domain", $Domain);
$core->assign("registrars", Module::getActiveModules("domain"));