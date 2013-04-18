<?php
require_once(dirname(__FILE__) . '/../init.php');
require_once("func.admin.php");

cronLock('minutely');

/*
*   Port Monitor
*/
try {
    $sql = "SELECT sp.* FROM server_probes sp
                    INNER JOIN servers s ON s.serverID = sp.serverID 
                WHERE s.status = 'active'";
    $probes = $db->query($sql, SQL_ALL);
    foreach ($probes as $p) {
        echo $p['probeID'] . "\n";
        $PRB = new Probe();
        $PRB->objectFromArray($p);
        $PRB->check();
    }
} catch (Exception $e) {

}

/*
*   Load Monitor
*/
try {
    $sql = "SELECT s.serverID FROM servers s
                    INNER JOIN server_settings ss ON (ss.serverID = s.serverID AND ss.setting = 'load_monitor' AND ss.value = '1')
                WHERE s.status = 'active'";
    $servers = $db->query($sql, SQL_KEY, 'serverID');
    foreach ($servers as $serverID) {
        $S = new Server($serverID);
        $ret = $S->moduleRunCmd('loadavg', array('nolog' => true));
        if ($ret['st'] != false) {
            $load = $ret['msg'];
            $S->set('loadavg', implode(':', $load));

            $_load = (float)$S->getSetting('critical_load');

            if ($_load > 0 && (float)$load[0] > $_load) {
                vzrlog('Kritik yük: ' . (float)$load[0], 'error', $S->serverName);
            }
        } else {
            $S->set('loadavg', '::');
            vzrlog('Yük kontrolü için sunucuya bağlanılamadı', 'error', $S->serverName);
        }
    }
} catch (Exception $e) {

}

/*
*   Scheduled Queue Jobs  
*/
try {
    $sql = "SELECT jobID FROM queue
                WHERE (status = 'scheduled' AND dateFire <= UNIX_TIMESTAMP())
                        OR status = 'pending-cron'";

    $jobs = $db->query($sql, SQL_KEY, 'jobID');
    foreach ($jobs as $jobID) {
        $Queue = new Queue($jobID);
        $Queue->process();
    }
} catch (Exception $e) {

}

$db->query("UPDATE crons SET dateStart = UNIX_TIMESTAMP() WHERE filename = 'minutely.php'");
cronUnlock('minutely');