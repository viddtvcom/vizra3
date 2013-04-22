<?php

$action_menu[] = array('Yeni Departman Ekle', '?p=175&act=add_department');


switch ($_GET["act"]) {

    case 'add_department':
        if ($_POST["action"] == "add") {
            $sql = "INSERT INTO departments (depTitle) VALUES ('" . $_POST['depTitle'] . "')";
            $db->query($sql);
            redirect('index.php?p=175');
        }
        $tpl_content = "add_department";
        break;

    default:
        $deps = $db->query("SELECT * FROM departments", SQL_ALL);
        $core->assign("deps", $deps);

        $tpl_content = "departments";
        break;
}



