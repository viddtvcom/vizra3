<?php
/*
*   Hosting CSV Raporu
*/
if ($_POST['action'] == 'hosting') {
    if (empty($_POST['services'])) {
        core::raise('En az bir servis seçmelisiniz', 'e', '?p=710');
    }
    $sql = "SELECT c.*,o.*,oa.value FROM clients c
                INNER JOIN orders o ON c.clientID = o.clientID
                INNER JOIN order_attrs oa ON (oa.orderID = o.orderID AND oa.setting = 'domain')
            WHERE  o.status = 'active' AND oa.value != ''  
                AND o.serviceID IN (" . implode(',', $_POST['services']) . ")";
    $accounts = $db->query($sql, SQL_ALL);

    if (! $accounts) {
        core::raise('Seçtiğiniz hizmetlere bağlı aktif hosting hesabı bulunmuyor', 'e', '?p=710');
    }
    foreach ($accounts as $acc) {
        // eger sipariste domain bilgisi yoksa bu kaydi alma
        if ($acc['value'] == '') {
            continue;
        }
        $content .= $acc['value'] . ',' . $acc['name'] . ',0' . str_replace(
            ' ',
            '',
            $acc['phone']
        ) . ',' . $acc['email'] . ',' . date('d-m-Y', $acc['dateStart']) . ',' . date('d-m-Y', $acc['dateEnd']) . "\n";
    }

    $filename = date('Y-m-d', time()) . "-hostinglistesi.csv";
    header('Content-type: application/csv;');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo mb_convert_encoding($content, "ISO-8859-9", "UTF-8");
    exit();

    /*
    *   Domain CSV Raporu
    */
} elseif ($_POST['action'] == 'domain') {
    $sql = "SELECT c.*,d.* FROM clients c
                INNER JOIN orders o ON c.clientID = o.clientID
                INNER JOIN domains d ON d.orderID = o.orderID 
            WHERE o.status = 'active' AND d.status = 'active'";
    $domains = $db->query($sql, SQL_ALL);

    if (! $domains) {
        core::raise('Aktif domain siparişi bulunmuyor', 'e', '?p=710');
    }


    foreach ($domains as $dom) {
        $content .= $dom['domain'] . ',' . $dom['name'] . ',0' . str_replace(
            ' ',
            '',
            $dom['phone']
        ) . ',' . $dom['email'] . ',' . date('d-m-Y', $dom['dateReg']) . ',' . date('d-m-Y', $dom['dateExp']) . "\n";
    }

    $filename = date('Y-m-d', time()) . "-domainlistesi.csv";
    header('Content-type: application/csv;');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo mb_convert_encoding($content, "ISO-8859-9", "UTF-8");
    exit();
}


$services = $db->query(
    "SELECT * FROM services WHERE addon = '0' AND groupID != 10 ORDER BY groupID,service_name",
    SQL_ALL
);
$core->assign('services', $services);
$tpl_content = 'tib_csv.tpl';