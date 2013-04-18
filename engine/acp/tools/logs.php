<?php


$action_menu[] = array('Hepsini Sil', '?p=610&act=del_all');

switch ($_GET["act"]) {

    case 'del_all':
        $db->query('TRUNCATE logs_sys');
    default:
        $tpl_content = "logs.tpl";

}
