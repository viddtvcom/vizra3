<?php

$sql = "SELECT d.*,c.name FROM domains d
            INNER JOIN orders o ON d.orderID = o.orderID 
            INNER JOIN clients c ON c.clientID = o.clientID 
        WHERE 1 = 1
        ORDER BY d.dateAdded DESC";

$pag = paging($sql, 0, 20, 'd.domainID');
$core->assign('pag', $pag);
$domains = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign("domains", $domains);
$core->assign('icons', Domain::$icons);


$tpl_content = "domains";


