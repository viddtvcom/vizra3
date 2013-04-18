<?php

if ($_POST["action"] == "update") {
    if ($_POST['status'] != $_POST['_set']['status']) {
        $MOD = Module::getInstance($_GET['moduleID']);
        $MOD->setStatus($_POST['_set']['status']);
    }
    $modConfig = Module::getConfig($_GET['moduleID']);
    foreach ($modConfig['sys'] as $setting => $obj) {
        if ($obj->type == 'hidden') {
            continue;
        }
        $val = ($obj->encrypted == '1') ? core::encrypt($_POST['_set'][$setting]) : $_POST['_set'][$setting];
        $sql = "INSERT INTO settings_modules (moduleID,module_type,setting,value,encrypted) 
                VALUES ('" . $_GET['moduleID'] . "','" . $_GET['type'] . "','" . $setting . "','" . $val . "','" . $obj->encrypted . "')
                ON DUPLICATE KEY UPDATE value = '" . $val . "', module_type = '" . $_GET['type'] . "'";
        $db->query($sql);
    }
    core::raise('Modül ayarları güncellendi', 'm');
    redirect('index.php?p=170&type=' . $_GET['type'] . '&act=settings&moduleID=' . $_GET['moduleID']);

} elseif ($_POST["action"] == "moduleOperation") {
    $MOD = Module::getInstance($_GET['moduleID']);
    $MOD->$_POST["moduleCmd"]();
    core::raise($_POST["moduleCmd"] . ' > komut çalıştırıldı', 'm');
}

if ($_GET["act"] == "settings") {
    if (DEMO && $_GET['moduleID'] == 'directi') {
        core::raise('Demo modunda iken bu modül ayarlarını görüntüleyemezsiniz', 'e');
        redirect('?p=170');
    }
    $modConfig = Module::getConfig($_GET['moduleID']);
    if (! $modConfig) {
        core::raise('Modül bulunamadı', 'e', '?p=170');
    }
    $settings = $db->query(
        "SELECT * FROM settings_modules WHERE moduleID = '" . $_GET['moduleID'] . "'",
        SQL_KEY,
        'setting'
    );
    foreach ($modConfig['sys'] as $setting => $obj) {
        if ($obj->type == 'hidden') {
            unset($modConfig['sys'][$setting]);
            continue;
        }
        $obj->value = ($settings[$setting]['value'] != '') ? $settings[$setting]['value'] : $obj->value;
        if ($obj->encrypted == '1') {
            $obj->value = core::decrypt($obj->value);
        }
        if ($obj->depends) {
            foreach ($obj->depends as $k => $v) {
                if ($settings[$k]['value'] != $v) {
                    unset($modConfig['sys'][$setting]);
                    break;
                }
            }
        }
    }
    $core->assign('modConfig', $modConfig);
    $tpl_content = 'module_settings';

    $page_title = $modConfig['sys']['title']->value;
    $page_icon = 'gear.png';

    $MOD = Module::getInstance($_GET['moduleID']);
    if (method_exists($MOD, 'getCmds')) {
        $moduleCmds = $MOD->getCmds();
        $moduleCmds = $moduleCmds['module'];
        $core->assign('moduleCmds', $moduleCmds);
    }

} else {

    foreach (Module::$types as $type) {
        $modules[$type] = Module::getModuleList($type);
    }
    $core->assign('modules', $modules);

    $status = $db->query("SELECT moduleID,value FROM settings_modules WHERE setting = 'status'", SQL_KEY, 'moduleID');
    $core->assign('status', $status);
    $tpl_content = "modules";
}





