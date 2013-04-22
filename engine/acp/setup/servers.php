<?php
$action_menu[] = array('Yeni Sunucu Ekle', '?p=125&act=add_server');
$action_menu[] = array('##page_150##', '?p=150');


switch ($_GET["act"]) {

    case 'del':
        $Server = new Server($_GET['serverID']);
        if (! $Server->serverID) {
            core::raise('Sunucu bulunamadı', 'e', '?p=125');
        } else {
            $Server->destroy();
            core::raise($Server->serverName . ': Sunucu sistemden silindi', 'm', '?p=125');
        }

        break;

    case 'add_server':
        if ($_POST["action"] == "add") {
            $Server = new Server();
            $Server->create();
            $Server->replace($_POST)->update();

            if ($Server->serverID) {
                iredirect("server_details", $Server->serverID);
            } else {
                core::error("server_add");
            }
        } else {
            $core->assign("modules", Module::getActiveModules('service'));
            $tpl_content = "add_server";
        }
        break;


    case 'server_details':
        $Server = new Server($_GET["serverID"]);
        if (! $Server->serverID) {
            core::error("server_load");
        }

        if ($_POST["action"] == "update") {
            $Server->replace($_POST)->update();
            if ($Server->moduleID != '') {
                $modConfig = $Server->getModuleConfig();
                foreach ((array)$modConfig['srvr'] as $setting => $obj) {
                    if ($obj->type == 'hidden') {
                        continue;
                    }
                    $val = ($obj->encrypted == '1') ? core::encrypt(
                        $_POST['_set'][$setting]
                    ) : $_POST['_set'][$setting];
                    $sql = "INSERT INTO server_settings (serverID,setting,value,encrypted)
                            VALUES ('" . $Server->serverID . "','" . $setting . "','" . $val . "','" . $obj->encrypted . "')
                            ON DUPLICATE KEY UPDATE value = '" . $val . "'";
                    $db->query($sql);
                }
            }
            core::raise('Sunucu bilgileri güncellendi', 'm', 'rt');

        } elseif ($_POST["action"] == "moduleOperation") {
            $result = $Server->moduleQueueCmd($_POST["moduleCmd"], $_POST["inputs"]);
            core::raise($_POST["moduleCmd"] . ' > komut çalıştırılıyor', 'm');
        }
        /*        if ($_POST) {
                    iredirect("server_details",$Server->serverID);
                }*/

        $core->assign("modules", Module::getModuleList('service'));
        $core->assign("Server", $Server);

        if ($Server->moduleID != '') {

            $modConfig = $Server->getModuleConfig();

            $settings = $db->query(
                "SELECT * FROM server_settings WHERE serverID = '" . $Server->serverID . "'",
                SQL_KEY,
                'setting'
            );
            foreach ((array)$modConfig['srvr'] as $setting => $obj) {
                if ($obj->type == 'hidden') {
                    unset($modConfig['srvr'][$setting]);
                    continue;
                }
                $obj->value = ($settings[$setting]['value'] != '') ? $settings[$setting]['value'] : $obj->value;
                if ($obj->encrypted == '1') {
                    $obj->value = core::decrypt($obj->value);
                }
                if ($obj->depends) {
                    foreach ($obj->depends as $k => $v) {
                        if ($settings[$k]['value'] != $v) {
                            unset($modConfig['srvr'][$setting]);
                            break;
                        }
                    }
                }
            }
            $core->assign('modConfig', $modConfig['srvr']);
            $MOD = Module::getInstance($Server->moduleID);
            $MOD->setServer($Server->serverID);
            $moduleCmds = $MOD->getCmds();
            $Server->moduleCmds = $moduleCmds['server'];
            /*            $MOD = Module::getInstance($Server->moduleID);
                        $MOD->setServer($Server->serverID);
                        if (method_exists($MOD,'loadavg')) {
                            $loadavg = $MOD->loadavg();
                        } */
        }

        $tpl_content = "server_details";
        $page_title = $Server->serverName;
        $page_icon = 'server.png';
        break;

    default:
        $servers = $db->query("SELECT * FROM servers ORDER BY status, serverName", SQL_ALL);
        $core->assign("servers", $servers);

        $tpl_content = "servers";
        break;
}



