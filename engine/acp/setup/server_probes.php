<?php

$action_menu[] = array('Yeni Probe Ekle', '?p=150&act=add_probe');

switch ($_GET["act"]) {
    case 'add_probe':
        if ($_POST["action"] == "add") {
            $Probe = new Probe();
            $Probe->create();
            $Probe->replace($_POST)->update();

            if ($Probe->probeID) {
                redirect("?p=150");
            } else {
                core::error("server_add");
            }
        } else {
            $servers = $db->query("SELECT * FROM servers WHERE status = 'active' ORDER BY serverName ASC", SQL_ALL);
            $core->assign('servers', $servers);

            $tpl_content = "add_probe";
        }
        break;

    case 'delProbe':
        $Probe = new Probe($_GET['probeID']);
        if ($Probe->probeID) {
            $Probe->destroy();
            core::raise('Probe silindi', 'm');
        }
    default:
        // minutely cron last run
        $lastrun = cronLastRun('minutely.php', true);
        if ($lastrun < (time() - 60)) {
            $core->assign('show_cron_note', true);
        }
        $probes = $db->query(
            "SELECT sp.*,s.serverName FROM server_probes sp INNER JOIN servers s ON s.serverID = sp.serverID",
            SQL_ALL
        );
        $core->assign("probes", $probes);

        $tpl_content = "server_probes";
        break;
}



