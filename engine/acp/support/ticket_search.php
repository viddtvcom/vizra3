<?php

if (isset($_GET['ticketID'])) {
    redirect('?p=212&ticketID=' . $_GET['ticketID']);
}

// admin settings
$_SESSION['vadmin']->syncSetting('ticketSearch_status', &$_GET['status']);

$sort = " t.dateUpdated DESC";
$joins = "INNER JOIN departments d ON t.depID = d.depID
          LEFT JOIN admins a ON t.adminID = a.adminID
          INNER JOIN clients c ON t.clientID = c.clientID";

if (isset($_GET['subject'])) {
    $cond .= " AND t.subject LIKE '%" . $_GET['subject'] . "%'";
}
if ($_GET['status'] != 'all') {
    $cond .= " AND t.status = '" . $_GET['status'] . "'";
}
if (isset($_GET['response'])) {
    $joins .= " INNER JOIN ticket_responses tr ON (tr.ticketID = t.ticketID AND tr.response LIKE '%" . $_GET['response'] . "%')";
    $groupby = " GROUP BY tr.ticketID";
}

$sql = "SELECT t.*, c.name as clientName, a.adminNick, d.depTitle 
        FROM tickets t  $joins  
        WHERE 1=1 $cond $groupby ORDER BY $sort";


$pag = paging($sql, 0, 20, 't.ticketID');
$core->assign('pag', $pag);
$tickets = $db->query($sql . $pag['limit'], SQL_ALL);
$core->assign('tickets', $tickets);

$tpl_content = 'ticket_search.tpl';
