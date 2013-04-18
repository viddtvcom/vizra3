<?php
require_once($config['BASE_PATH'] . 'engine/config/settings.php');

switch ($_GET["act"]) {
    default:
        if ($_POST["action"] == "update" && privcheck(2)) {
            if (DEMO == true) {
                core::raise('Demo modunda bu ayarları güncelleyemezsiniz', 'e', '?p=140');
            }
            unset($_SESSION["settings_general"]);

            foreach ($_set as $group => $items) {
                foreach ($items as $setting => $obj) {
                    $key = $group . '_' . $setting;
                    $val = ($obj->encrypted == '1') ? core::encrypt(
                        $_POST['_set'][$group][$setting]
                    ) : $_POST['_set'][$group][$setting];
                    $cont = false;
                    if ($obj->depends) {
                        foreach ($obj->depends as $k => $v) {
                            if ($_POST['_set'][$group][$k] != $v) {
                                $cont = true;
                            }
                        }
                    }
                    if ($cont) {
                        continue;
                    }
                    $sql = "INSERT INTO settings_general (setting,value,encrypted) VALUES ('" . $key . "','" . $val . "','" . $obj->encrypted . "')
                            ON DUPLICATE KEY UPDATE value = '" . $val . "'";
                    $db->query($sql);
                }
            }

            // notification checkboxes
            foreach ((array)$_POST['_notify'] as $k => $v) {
                for ($i = 0; $i < 4; $i ++) {
                    $v2[$i] = (int)$v[$i];
                }
                setSetting('notify_' . $k, implode('', $v2));
            }
            core::raise('Ayarlar güncellendi', 'm', '?p=140');
        }

        $settings = $db->query("SELECT * FROM settings_general WHERE hidden = '0'", SQL_KEY, 'setting');
        foreach ($_set as $group => $items) {
            foreach ($items as $setting => $obj) {
                $obj->value = $settings[$group . '_' . $setting]['value'];
                if ($obj->type == 'binary') {
                    $obj->value = str_split($obj->value);
                    $_set[$group]['_binary'][$setting] = $obj;
                    unset($_set[$group][$setting]);
                }
                if ($obj->encrypted == '1') {
                    $obj->value = core::decrypt($obj->value);
                }
                if ($obj->depends) {
                    foreach ($obj->depends as $k => $v) {
                        if ($settings[$group . '_' . $k]['value'] != $v) {
                            unset($_set[$group][$setting]);
                            break;
                        }
                    }
                }

            }
        }

        // daily cron last run
        $lastrun = cronLastRun('daily.php', true);
        if ($lastrun < (time() - 60 * 60 * 24)) {
            $core->assign('show_cron_note', true);
        }


        $core->assign("settings", $_set);
        $tpl_content = "general";
        break;
}