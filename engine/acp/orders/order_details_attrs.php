<?php

if ($_POST['action'] == 'setModset') {
    $val = ($_POST['value'] == 'true') ? '1' : '0';
    $db->query(
        "UPDATE order_attrs SET clientCanSee = '" . $val . "' WHERE orderID = " . $Order->orderID . " AND setting = '" . $_POST['setting'] . "'"
    );
    echo json_encode(array('st' => true, 'id' => $_POST['id']));
    exit();

} elseif ($_POST["action"] == "updateModuleSettings") {
    foreach ($_POST['srv'] as $set => $val) {
        $isEncrypted = $db->query(
            "SELECT encrypted FROM service_attr_types WHERE setting = '" . $set . "'",
            SQL_INIT,
            'encrypted'
        );
        if ($isEncrypted == '1') {
            $val = core::encrypt($val);
        }

        $sql = "SELECT SUM(value) AS aosum FROM order_attrs oa 
                    INNER JOIN orders o ON (o.orderID = oa.orderID AND o.parentID = " . $Order->orderID . ")
                WHERE setting = '" . $set . "' AND o.status = 'active'";
        // addon sum
        $aosum = $db->query($sql, SQL_INIT, 'aosum');
        if (intval($aosum) > 0) {
            $val = $val - $aosum;
        }

        // update
        $db->query(
            "UPDATE order_attrs SET value = '" . $val . "' WHERE setting = '" . $set . "' AND orderID = " . $Order->orderID
        );
    }
    $Order->setTitle();

} elseif ($_POST["action"] == "update_attrs") {

    $Order->set('serverID', $_POST['serverID']);

} elseif ($_POST["action"] == "moduleOperation" && $Order->Service->groupID != 10) {
    if (false && ! $Order->serverID) {
        core::raise('Sunucu seçilmemiş', 'e', 'rt');
    } else {
        $result = $Order->moduleRunCmd($_POST["moduleCmd"], $_POST["inputs"]);
        $lang_cmd = lang('ModuleCmd_' . $_POST["moduleCmd"]);
        if ($result['st'] == true) {
            core::raise($lang_cmd . ' > Başarılı', 'm', 'rt');
        } else {
            core::raise($lang_cmd . ' > Hata: ' . $result['msg'], 'e', 'rt');
        }
    }

} elseif ($_POST["action"] == "moduleCmd" && $Order->Service->groupID != 10) {
    $result = $Order->moduleRunCmd($_POST["cmd"], $_POST['srv']);
    if ($result['st'] == true) {
        core::raise($_POST["cmd"] . ' > işlem başarılı', 'm', 'rt');
    } else {
        core::raise($result['msg'], 'e', 'rt');
    }

}


$Order->loadAttrs();
$Order->loadAddonAttrs();


if ($Order->Service->moduleID) {
    $Order->loadModuleCmds()->loadModuleLinks();
    $modConfig = $Order->getModuleConfig();
    $attrs = array_merge($Order->Service->getAttributes(), (array)$modConfig['srvc']);

} else {
    $attrs = $Order->Service->getAttributes();

}


foreach ($attrs as $key => $obj) {
    // service type check
    if ($obj->stype == 'reseller' && $Order->Service->type != 'reseller'
            || $obj->stype == 'shared' && $Order->Service->type != 'shared'
    ) {
        unset($attrs[$key]);
        continue;
    }
    $obj->value = (! is_numeric(
        $Order->attrs[$key]['value']
    )) ? $Order->attrs[$key]['value'] : ($Order->attrs[$key]['value'] + $Order->addon_attrs[$key]['value']);

    // client-side visibility
    $obj->clientCanSee = $Order->attrs[$key]['clientCanSee'];

    // dependency check
    if ($obj->depends) {
        foreach ($obj->depends as $k => $v) {
            if ($Order->attrs[$Order->Service->moduleID . '_' . $k]['value'] != $v) {
                unset($attrs[$key]);
                break;
            }
        }
    }
}
$core->assign('srv', $attrs);

$servers = $db->query("SELECT * FROM servers WHERE moduleID = '" . $Order->Service->moduleID . "'", SQL_ALL);
$core->assign('servers', $servers);
