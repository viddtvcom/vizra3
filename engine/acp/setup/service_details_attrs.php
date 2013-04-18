<?php
$attrs = $db->query(
    "SELECT setting,value,clientCanSee FROM service_attrs WHERE serviceID = " . $Service->serviceID,
    SQL_KEY,
    'setting'
);
$core->assign('attrs', $attrs);


if ($_POST['action'] == 'updateModuleSettings') {

    //debug($_POST,1);

    // module change
    if ($_POST['moduleID'] != $Service->moduleID) {
        $Service->replace($_POST)->update();
        if ($_POST['moduleID'] == '') {
            $db->query("DELETE FROM service_attrs WHERE source != 'custom' AND serviceID = " . $Service->serviceID);
        } else {
            $Service->bindModule($_POST['moduleID']);
        }
        redirect('?p=116&tab=attrs&serviceID=' . $Service->serviceID);
    }
    $Service->replace($_POST)->update();
    $modConfig = $Service->getModuleConfig();
    $modsets = array_merge($Service->getAttributes(), (array)$modConfig['srvc']);
    foreach ($modsets as $key => $val) {
        if ($val->stype && $Service->type != $val->stype) {
            continue;
        }
        if ($Service->addon == '1' && ! $val->addon) {
            continue;
        }
        $sql = "INSERT INTO service_attrs (serviceID,source,setting,value,clientCanSee) 
                    VALUES (" . $Service->serviceID . ",'" . $_POST['src'][$key] . "','" . $key . "','" . $_POST['srv'][$key] . "','" . $_POST['ccs'][$key] . "')
                ON DUPLICATE KEY UPDATE value = '" . $_POST['srv'][$key] . "', clientCanSee = '" . $_POST['ccs'][$key] . "'";
        $db->query($sql);
    }
    core::raise('Servis Özellikleri güncellendi', 'm');
    redirect('?p=116&tab=attrs&serviceID=' . $Service->serviceID);

} elseif ($_POST['action'] == 'addAttr') {
    $Service->addAttr($_POST['setting']);
    redirect('?p=116&tab=attrs&serviceID=' . $Service->serviceID);

} elseif ($_GET['act'] == 'delAttr') {
    $Service->delAttr($_GET['key']);
    redirect('?p=116&tab=attrs&serviceID=' . $Service->serviceID);

}

$stype = ($Service->domain) ? 'domain' : 'service';
$core->assign("modules", Module::getModuleList($stype));

$core->assign(
    "select_provisionTypes",
    array2select(service::$provisionTypes, "provisionType", - 1, "", $Service->provisionType)
);

$servers = $db->query(
    "SELECT * FROM servers WHERE status = 'active' AND moduleID = '" . $Service->moduleID . "'",
    SQL_ALL
);
$core->assign('servers', $servers);

$modConfig = $Service->getModuleConfig();

$Module = Module::getInstance($Service->moduleID);

if ($Module->moduleID != false) {
    if ($Module->get('status') != 'active') {
        $core->assign('module_inactive', true);
    }
}

$custom_attrs = $Service->getAttributes();
$all_attrs = $db->query(
    "SELECT * FROM service_attrs WHERE serviceID = " . $Service->serviceID . " ORDER BY source DESC, attrID ASC",
    SQL_KEY,
    'setting'
);

foreach ($all_attrs as $key => $data) {
    if ($data['source'] == 'module') {
        $ret[$key] = $modConfig['srvc'][$key];
        $ret[$key]->valueBy = 'service';
    } else {
        $ret[$key] = $custom_attrs[$key];
    }
    if ($ret[$key]->depends) {
        foreach ($ret[$key]->depends as $k => $v) {
            $ret[$Service->moduleID . '_' . $k]->controller = true;
            $ret[$key]->class .= ' ' . $Service->moduleID . '_' . $k . ' ' . $v;

            if ($all_attrs[$Service->moduleID . '_' . $k]['value'] != $v) {
                //unset($ret[$key]);
                //break;
            }
        }
    }
    if ($ret[$key]->type == 'server' && $Service->serverID) {
        $ret[$key]->build(
            array('serverID' => $Service->serverID, 'setting' => str_replace($Service->moduleID . '_', '', $key))
        );
    }
}
//debug($ret);
$core->assign('srv', $ret);
$core->assign('custom_attrs', $custom_attrs);


if ($Service->addon == '1' && $Service->moduleID) {
    Module::loadModuleFile($Service->moduleID);
    $allcmds = get_class_methods('mod_' . $Service->moduleID);
    foreach ($allcmds as $k => $cmd) {
        if (substr($cmd, 0, 4) == 'cmd_') {
            $cmds[] = str_replace('cmd_', '', $cmd);
        }
    }
    $core->assign('cmds', $cmds);
}


$core->assign('attr_types', $Service->getAttributeTypes());




//debug($modConfig['srvc'],1);

