<?php


$action_menu[] = array('Yeni Sayfa Ekle', '?p=130&act=add_page');


switch ($_GET["act"]) {

    case 'add_page':
        if ($_POST["action"] == "add") {
            $Page = new Page();
            $Page->create();
            $Page->replace($_POST)->update();

            if (! $Page->pageID) {
                core::error("page_add");
            } else {
                iredirect("page_details", $Page->pageID);
            }
        } else {
            $modules = $db->query("SELECT * FROM page_modules", SQL_ALL);
            $core->assign("select_modules", array2select($modules, "moduleID", "moduleID", "folder"));
            $tpl_content = "add_page";
        }
        break;


    case 'page_details':
        $Page = new Page($_GET["pageID"]);
        if (! $Page->pageID) {
            core::error("page_load");
        }

        if ($_POST["action"] == "update" && privcheck(2)) {
            $_POST['actions'] = implode('', array_keys($_POST['act']));
            $Page->replace($_POST)->update();
        }
        if ($_POST) {
            iredirect("page_details", $Page->pageID);
        }
        $Page->actions = preg_split('//', $Page->actions, - 1, PREG_SPLIT_NO_EMPTY);
        $core->assign("Page", $Page);

        $pages = $db->query("SELECT * FROM pages ORDER BY moduleID", SQL_ALL);
        $core->assign('pages', $pages);
        $tpl_content = "page_details";
        break;

    default:
        $pages = $db->query("SELECT * FROM pages ORDER BY pageID", SQL_ALL);
        $core->assign("pages", $pages);

        $tpl_content = "pages";
        break;
}



