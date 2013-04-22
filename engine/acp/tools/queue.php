<?php


$action_menu[] = array('Hepsini Sil', '?p=615&act=del_all');

if ($_GET["act"] == 'del_all') {
    $db->query("TRUNCATE queue");
    redirect('?p=615');
} elseif ($_GET['act'] == 'delQJob') {
    $db->query("DELETE FROM queue WHERE jobID = " . $_GET['jobID']);
    core::raise('Kuyruk işlemi silindi', 'm', '?p=615');
}


$sql = "SELECT * FROM queue 
        WHERE job != 'sendMailx' AND NOT (status = 'completed' AND dateUpdated < " . (time() - 60 * 60 * 24) . ")
        ORDER BY dateAdded DESC";

$jobs = (array)$db->query($sql, SQL_KEY, 'jobID');

foreach ($jobs as $k => $j) {
    $prm2 = unserialize($j['params']);
    $prm = array();
    foreach ($prm2 as $k2 => $v2) {
        $prm[$k2] = base64_decode($v2);
    }
    $jobs[$k]['params'] = $prm;

}

$core->assign('jobs', $jobs);
$tpl_content = "queue.tpl";
            
