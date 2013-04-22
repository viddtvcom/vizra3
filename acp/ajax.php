<?php
require('../engine/init.php');
require_once("func.admin.php");
secure();
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/acp');
error_reporting(0);


if (isset($_GET['a'])) {
    $_POST['action'] = $_GET['a'];
}

switch ($_POST['action']) {

    case 'getServices':
        $sql = "SELECT serviceID,service_name FROM services s INNER JOIN service_groups sg ON sg.groupID = s.groupID
                WHERE s.addon != '1'";
        if ($_POST['groupID'] != 'all') {
            $sql .= " AND (s.groupID = " . $_POST['groupID'] . " OR sg.parentID = " . $_POST['groupID'] . ")";
        }
        $ret = $db->query($sql, SQL_ALL);
        break;

    case 'getProbes':
        $probes = $db->query("SELECT status,serverID,title FROM server_probes", SQL_ALL);
        foreach ($probes as $p) {
            $ret['probes'][] = array(
                'probe' => $p['serverID'] . '_' . strtolower($p['title']),
                'status' => ($p['status'] == 'on') ? 'status_active.png' : 'status_suspended.png'
            );
        }

        $sql = "SELECT loadavg,s.serverID
                FROM servers s 
                    INNER JOIN server_settings ss ON (ss.serverID = s.serverID AND ss.setting = 'load_monitor' AND ss.value = '1')";
        $ret['loads'] = $db->query($sql, SQL_ALL);
        break;

    case 'get_tickets':
        $_SESSION['vadmin']->syncSetting('ticketListing_status', $_POST['show_tickets']);

        if ($_POST['show_tickets'] == '2') {
            $inSQL = " AND t.adminID NOT IN ('" . getAdminID() . "','-1')";
        } elseif ($_POST['show_tickets'] == '1') {
            $inSQL = " AND t.adminID = '" . getAdminID() . "'";
        } else {
            $inSQL = " AND t.adminID = -1";
        }

        $sql = "SELECT t.*, c.name as clientName,c.type as clientType,c.company, a.adminNick, d.depTitle
                FROM tickets t 
                    INNER JOIN departments d ON t.depID = d.depID
                    LEFT JOIN admins a ON t.adminID = a.adminID
                    INNER JOIN clients c ON t.clientID = c.clientID
                WHERE t.status = '" . $_POST['status'] . "'
                    AND t.depID IN (" . implode(',', $_SESSION['vadmin']->getDeps()) . ")
                    $inSQL
                ORDER BY dateUpdated ASC";

        $tickets = $db->query($sql, SQL_ALL);
        foreach ($tickets as $k => $t) {
            $tickets[$k]['clientName'] = ($t['clientType'] == 'individual') ? $t['clientName'] : $t['company'];
            $tickets[$k]['dateAdded'] = formatDate($t['dateAdded'], 'datetime', 'short');
            $tickets[$k]['dateUpdated'] = formatDate($t['dateUpdated'], 'datetime', 'short');

            if ($tickets[$k]['adminNick'] == null) {
                $tickets[$k]['adminNick'] = '--';
            }
        }
        $ret = $tickets;
        break;

    case 'get_logs':
        $sql = "SELECT sl.* FROM logs_sys sl
                WHERE sl.dateAdded > " . (int)$_POST["offset"] . " AND  sl.dateAdded > " . (time(
        ) - 60 * 60 * 32) . " ORDER BY sl.dateAdded ASC";
        $messages = $db->query($sql, SQL_ALL);

        if ($messages) {
            foreach ($messages as $k => $m) {
                $messages[$k]['timestamp'] = $vars["WEEK_DAYS_SHORT"][date("w", $m['dateAdded'])] . " " . date(
                    "H:i:s",
                    $m['dateAdded']
                );
                $messages[$k]['message'] = linkify($m['message'], true);
                $messages[$k]['type'] = $m['type'];
            }
        }
        echo json_encode($messages);
        exit();

    case 'get_messages':
        $sql = "SELECT c.*,a.adminNick FROM chat c INNER JOIN admins a ON c.adminID = a.adminID
                WHERE c.dateAdded > " . ($_POST["offset"]) . " AND c.dateAdded > " . (time(
        ) - (60 * 60 * 32)) . " ORDER BY c.dateAdded ASC LIMIT 150";
        $messages = $db->query($sql, SQL_ALL);
        if ($messages) {
            foreach ($messages as $k => $m) {
                $messages[$k]["timestamp"] = $vars["WEEK_DAYS_SHORT"][date("w", $m["dateAdded"])] . " " . date(
                    "H:i:s",
                    $m["dateAdded"]
                );
                $messages[$k]["message"] = linkify($m["message"], true);
            }
        }
        echo json_encode($messages);
        exit();

    case 'add_message':
        $sql = "INSERT INTO chat (adminID,message,dateAdded) VALUES (" . getAdminID() . ",'" . sanitize(
            $_POST["message"]
        ) . "',UNIX_TIMESTAMP())";
        $db->query($sql);
        exit();

    case 'sendBillToClient':
        OrderBill::sendbill($_POST['billID']);
        $ret['st'] = true;
        break;
}
//debug($ret);
//vzrlog($ret['loads']);

echo json_encode($ret);
