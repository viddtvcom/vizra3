<?php
$action_menu[] = array('Yeni Alan Ekle', '?p=180&act=add');

switch ($_GET["act"]) {

    case 'add':
        if ($_POST["action"] == "add") {
            $CS = new CustomField();
            if ($_POST['label'] != '') {

                $CS->create();
                $CS->replace($_POST)->update();

                if ($CS->attrID) {
                    core::raise('Özellik eklendi');
                    redirect('?p=180&act=details&attrID=' . $CS->attrID);
                } else {
                    core::error("service_attr_add");
                }
            } else {
                $CS->replace($_POST);
                $core->assign('CS', $CS);
                core::raise('Geçerli bir ad giriniz', 'e');
            }
        }
        $core->assign('types', CustomField::$types);
        $core->assign('functions', CustomField::getValidationFunctions());
        $tpl_content = "add_custom_field";
        break;

    case 'details':
        $CS = new CustomField($_GET['attrID']);
        if ($_POST["action"] == "update") {
            if ($_POST['encrypted'] != '1') {
                $_POST['encrypted'] = '0';
            }
            $CS->replace($_POST)->update();
            redirect('?p=180&act=details&attrID=' . $CS->attrID);
        }
        $core->assign('CS', $CS);
        $core->assign('types', CustomField::$types);
        $tpl_content = "custom_field_details";

        $core->assign('functions', CustomField::getValidationFunctions());

        $page_title .= " &raquo; <a href='?p=180&act=details&attrID=" . $CS->attrID . "'>" . $CS->label . "</a>";
        break;

    case 'del':
        $CS = new CustomField($_GET['attrID']);
        $CS->destroy();
        core::raise('Ekstra alan silindi', 'm', '?p=180');

    default:
        $sql = "SELECT * FROM attrs WHERE visibility != 'system'";
        $attrs = $db->query($sql, SQL_ALL);
        $core->assign("attrs", $attrs);
        $tpl_content = "custom_fields";
        break;
}

