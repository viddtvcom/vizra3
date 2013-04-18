<?php


if ($_POST) {

    if ($_POST['service'] == 'selected' || $_POST['server'] == 'selected') {
        $inner .= " INNER JOIN orders o ON c.clientID = o.clientID";
        if ($_POST['service'] == 'selected') {
            $where .= " AND o.serviceID IN (" . implode(',', $_POST['services']) . ")";
        }
        if ($_POST['server'] == 'selected') {
            $where .= " AND o.serverID IN (" . implode(',', $_POST['servers']) . ")";
        }
        // siparis durumu
        if ($_POST['ostatus'] != 'all') {
            $where .= " AND o.status = '" . $_POST['ostatus'] . "'";
        }
    }

    // musteri durumu
    if ($_POST['status'] != 'all') {
        $where .= " AND c.status = '" . $_POST['status'] . "'";
    }

    $sql = "SELECT c.* FROM clients c $inner WHERE 1=1 $where GROUP BY c.clientID";
    $clients = $db->query($sql, SQL_ALL);

    if ($_POST['test_email'] != '') {
        core::mailsender(
            $_POST['subject'],
            $_POST['test_email'],
            $_POST['body'],
            $_POST['from_name'],
            $_POST['from_email']
        );
        core::raise('Test maili ' . $_POST['test_email'] . ' adresine gönderildi', 'm', '?p=640');

    } else {
        $ts = time() + (int)$_POST['start_after'] * 60;
        foreach ($clients as $cl) {
            if ((int)$_POST['pause_mcount'] > 0 && (int)$_POST['pause_time']) {
                $cnt ++;
                if ($cnt % (int)$_POST['pause_mcount'] == 0) {
                    $offset = (int)$_POST['pause_time'] * 60;
                }
                $ts += $offset;
            }
            $tpl = $_POST['body'];
            $tpl = str_replace('{$vurl}', $config['HTTP_HOST'], $tpl);
            $tpl = str_replace('{$signature}', nl2br(getSetting('compinfo_mail_signature')), $tpl);
            foreach ($cl as $k => $v) {
                if ($k == 'password') {
                    continue;
                }
                if (strstr($k, 'date') && is_numeric($v)) {
                    $v = formatDate($v, 'datetime', 'long');
                }
                $tpl = str_replace('{$Client_' . $k . '}', $v, $tpl);
            }

            $data = array(
                'subject' => $_POST['subject'],
                'to'      => $cl['email'],
                'body'    => $tpl
            );

            Queue::createJob('sendmail')->setParams($data)->setDateFire($ts)->setStatus('scheduled')->update()->start();
        }

        core::raise('Mail gönderim bilgileri kaydedildi', 'm', '?p=615');
    }
}

$services = $db->query("SELECT * FROM services WHERE addon = '0' ORDER BY groupID,service_name", SQL_ALL);
$core->assign('services', $services);

$servers = $db->query("SELECT * FROM servers WHERE status = 'active' ORDER BY serverName", SQL_ALL);
$core->assign('servers', $servers);

$_cpage = 'mass_mail';
require('var.email_templates.php');
$core->assign('vars', $tempvars);


$tpl_content = 'mass_mail.tpl';
