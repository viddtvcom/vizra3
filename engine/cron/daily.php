<?php
require_once(dirname(__FILE__) . '/../init.php');
require_once("func.admin.php");
if ($argv[1] == 'test') {
    $test = true;
}

ob_start();

/*
*       Domain Reminders
*/
if (getSetting('domains_remenabled') == '1') {
    for ($i = 1; $i < 5; $i ++) {
        $date = getSetting('domains_rem' . $i);
        if ($date > 0) {
            $dates[] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $date, date('Y')));
        }
    }
    $dates[] = date('Y-m-d');

    $sql = "SELECT d.domainID, d.domain, d.orderID FROM domains d
                INNER JOIN orders o ON (o.orderID = d.orderID AND o.status IN ('active','suspended'))
            WHERE DATE(FROM_UNIXTIME(d.dateExp)) IN ('" . implode("','", $dates) . "')";
    $doms = $db->query($sql, SQL_ALL);
    foreach ($doms as $d) {
        echo "Domain hatirlatma maili gönderildi: " . $d['domain'] . " / " . order_link($d['orderID']) . "<br>";
        if (! $test) {
            $EMT = new Email_template(11);
            $EMT->domainID = $d['domainID'];
            $ret = $EMT->send();
        }
        $cnt_domremind ++;
    }
}


/*
*       Bill Generator
*/

$interval = getSetting('payments_billgen');
if ($interval) {

    //
    // Askidaki hesaplar icin de borc kaydi olusturulacak mi?
    //
    $order_status_stack = (getSetting('automation_suspend_bills') == '1') ? "'active', 'suspended'" : "'active'";

    //
    // odenmemis faturasi olmayan ve
    // bitis tarihi bugun+15'ten kücük olan siparisler
    $sql = "SELECT o.orderID
            FROM orders o
            	INNER JOIN services s ON s.serviceID = o.serviceID
                LEFT JOIN order_bills ob ON (ob.orderID = o.orderID AND ob.status = 'unpaid' AND ob.type = 'recurring')
            WHERE ob.billID IS NULL
                AND o.dateEnd < UNIX_TIMESTAMP(ADDDATE(CURDATE(), INTERVAL $interval DAY))
                AND o.payType = 'recurring'
                AND s.groupID != 10
                AND o.status IN (" . $order_status_stack . ")";
    $orders = $db->query($sql, SQL_KEY, 'orderID');
    foreach ($orders as $orderID) {
        $Order = new Order($orderID);

        echo "Borc kaydi olusturuluyor, orderID: " . order_link($orderID) . "<br>";

        if (! $test) {
            $Order->createNextBill(0, 'unpaid');
        }
        $cnt_genbill ++;
    }
    //
    //   En son ödenmemiş faturasının bitiş tarihi, bugun+15'den kucuk. 2. bir odenmemis olusturulmasi gerekiyor
    //
    $sql = "SELECT ob.orderID, MAX(ob.dateEnd)
            FROM order_bills ob INNER JOIN orders o ON (o.orderID = ob.orderID AND o.status IN (" . $order_status_stack . "))
            WHERE ob.status = 'unpaid' AND ob.type = 'recurring'
            GROUP BY ob.orderID
            HAVING MAX(ob.dateEnd) < UNIX_TIMESTAMP(ADDDATE(CURDATE(), INTERVAL $interval DAY))";

    $orders = $db->query($sql, SQL_KEY, 'orderID');
    foreach ($orders as $orderID => $dateEnd) {
        $Order = new Order($orderID);
        echo "Borc kaydi olusturuluyor, orderID: " . order_link($orderID) . ", Bitis Tarihi: " . formatDate(
            $dateEnd
        ) . "<br>";
        if (! $test) {
            $Order->createNextBill(0, 'unpaid', $dateEnd);
        }
        $cnt_genbill ++;
    }
}


/*
*       Auto Suspend
*/
if (getSetting('automation_suspend_enabled') == '1') {
    $suspend_by_balance = getSetting('automation_suspend_by_balance');
    $interval = (int)getSetting('automation_suspend_days');
    $sql = "SELECT DISTINCT(o.orderID) FROM orders o
                INNER JOIN order_bills ob ON (ob.orderID = o.orderID AND ob.status = 'unpaid')
                INNER JOIN clients c ON c.clientID = o.clientID 
            WHERE
                o.status = 'active'
                AND o.autoSuspend = '1' 
                AND c.autoSuspend = '1'
                AND UNIX_TIMESTAMP() > UNIX_TIMESTAMP(ADDDATE(FROM_UNIXTIME(ob.dateDue), INTERVAL $interval DAY))
            ";
    // AND ob.dateDue < UNIX_TIMESTAMP() AND ob.dateDue < UNIX_TIMESTAMP(ADDDATE(CURDATE(), INTERVAL $interval DAY))

    $orders = $db->query($sql, SQL_KEY, 'orderID');
    foreach ($orders as $orderID) {
        $Order = new Order($orderID);

        if ($suspend_by_balance == '1') {
            $Client = new Client($Order->clientID);
            $balance = $Client->getBalance();

            if ($balance > 0) {
                continue;
            }
        }

        if (! $test) {
            $Order->setStatus('suspended');
        }
        echo order_link($orderID) . " " . $Order->title . " askiya alindi<br>";
        $cnt_suspend ++;
    }
}

/*
*       Auto Terminate
*/
if (getSetting('automation_terminate_enabled') == '1') {
    $interval = (int)getSetting('automation_terminate_days');
    $sql = "SELECT DISTINCT(o.orderID) FROM orders o
                INNER JOIN order_bills ob ON (ob.orderID = o.orderID AND ob.status = 'unpaid')
            WHERE
                o.status IN ('active', 'suspended')
                AND o.autoSuspend = '1'
                AND c.autoSuspend = '1'
                AND UNIX_TIMESTAMP() > UNIX_TIMESTAMP(ADDDATE(FROM_UNIXTIME(ob.dateDue), INTERVAL $interval DAY))
            ";

    // AND ob.dateDue < UNIX_TIMESTAMP() AND ob.dateDue < UNIX_TIMESTAMP(ADDDATE(CURDATE(), INTERVAL $interval DAY))

    $orders = $db->query($sql, SQL_KEY, 'orderID');
    foreach ($orders as $orderID) {
        $Order = new Order($orderID);
        if (! $test) {
            $Order->setStatus('inactive');
        }
        echo order_link($orderID) . " " . $Order->title . " siparis kapatildi<br>";
        $cnt_terminate ++;
    }
}


/*
*       Auto Close
*/
if (getSetting('automation_autoclose_enabled') == '1') {
    $sql = "SELECT DISTINCT(o.orderID) FROM orders o
            WHERE o.status = 'active' AND o.payType IN ('onetime','free')
                AND o.dateEnd > 0 AND o.dateEnd < UNIX_TIMESTAMP()";
    $orders = $db->query($sql, SQL_KEY, 'orderID');
    foreach ($orders as $orderID) {
        $Order = new Order($orderID);
        if (! $test) {
            $Order->setStatus('inactive');
        }
        echo order_link($orderID) . " " . $Order->title . " siparis kapatildi<br>";
        $cnt_closed ++;
    }
}


/*
*       Payment Reminders
*/
if (getSetting('payments_remenabled') == '1') {
    for ($i = 1; $i < 5; $i ++) {
        $date = getSetting('payments_rem' . $i);
        if ($date > 0) {
            $dates[] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $date, date('Y')));
        }
    }
    // son ödeme tarihi secili tarihler icinde olan veya son odeme tarihi gecip de hic mail gonderilmemis
    $sql = "SELECT billID,ob.orderID FROM order_bills ob
                INNER JOIN orders o ON (o.orderID = ob.orderID AND o.status NOT IN ('deleted','inactive')) 
            WHERE ob.status = 'unpaid' AND 
                ( DATE(FROM_UNIXTIME(ob.dateDue)) IN ('" . implode("','", $dates) . "')
                    OR (ob.dateDue < UNIX_TIMESTAMP() AND ob.mail_count = 0))";
    $bills = $db->query($sql, SQL_ALL);
    foreach ($bills as $b) {
        if (! $test) {
            $OB = new OrderBill($b['billID']);
            $OB->sendmail();
        }
        echo "Odeme hatirlatma, orderID: " . order_link($b['orderID']) . "<br>";
        $cnt_payremind ++;
    }
}


$body = (int)$cnt_domremind . " adet alan adı hatırlatma maili gönderildi<br>";

if ($cnt_genbill) {
    $body .= "<br>" . $cnt_genbill . " adet sipariş için fatura oluşturuldu<br>";
}
if ($cnt_payremind) {
    $body .= "<br>" . $cnt_payremind . " adet ödeme hatırlatma maili gönderildi<br><br>";
}
if ($cnt_suspend) {
    $body .= (int)$cnt_suspend . " adet hesap askıya alındı<br><br>";
}
if ($cnt_terminate) {
    $body .= (int)$cnt_terminate . " adet sipariş kapatıldı<br><br>";
}
if ($cnt_closed) {
    $body .= (int)$cnt_closed . " adet süresi dolan sipariş (ücretsiz veya tek seferlik) kapatıldı<br>";
}

echo $body;

$body = ob_get_contents();
ob_end_clean();

echo strip_tags(str_replace('<br>', "\n", $body));

$send_to = isset($argv[2]) ? $argv[2] : getSetting('notify_notifymail');

core::mailsender('Günlük CRON işlem raporu', $send_to, $body);

$db->query("UPDATE crons SET dateStart = UNIX_TIMESTAMP() WHERE filename = 'daily.php'");


function order_link($orderID)
{
    $link = '<a href="http://' . BASEHOST . '/' . BASEDIR . '/acp/?p=311&tab=orders&orderID=' . $orderID . '">' . $orderID . '</a>';

    return $link;
}
