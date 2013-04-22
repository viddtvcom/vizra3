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

    case 'get_kb_list':
        $sql = "SELECT * FROM kb";
        $articles = $db->query($sql, SQL_ALL);
        $core->assign('articles', $articles);
        echo $core->fetch('support/kb_list.tpl');
        exit;

}

