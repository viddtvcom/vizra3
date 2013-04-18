<?php

$action_menu[] = array('##SetupEmailTemplates%AddNewTemplate##', '?p=135&act=add_template');


switch ($_GET["act"]) {

    case 'details':
        $Tpl = new Email_template($_GET["templateID"]);
        if (! $Tpl->templateID) {
            core::error("email_template_load");
        }

        if ($_POST["action"] == "update") {
            $Tpl->replace($_POST)->update();
            core::raise('Şablon bilgileri güncellendi', 'm', 'rt');
        }
        $core->assign("Tpl", $Tpl);
        $tpl_content = "email_template_details";
        require('var.email_templates.php');
        $core->assign('vars', $tempvars);

        $page_title .= " &raquo; <a href='?p=135&act=details&templateID=" . $Tpl->templateID . "'>" . $Tpl->title . "</a>";
        break;

    case 'add_template':
        if ($_POST["action"] == "add") {
            $Tpl = new Email_template();
            $Tpl->create();
            $Tpl->replace($_POST)->update();

            if ($Tpl->templateID) {
                jsredirect("index.php?p=135&act=details&templateID=" . $Tpl->templateID);
            } else {
                core::error("email_template_add");
            }
        } else {
            $types = $db->query("SELECT DISTINCT type FROM settings_email_templates", SQL_ALL);
            $types = array('welcome', 'custom');
            $core->assign('types', $types);
            $tpl_content = "add_email_template";
        }
        break;


    default:
        $templateTypes = $db->query("SELECT DISTINCT type FROM settings_email_templates", SQL_ALL, 'type');
        foreach ($templateTypes as $type => $item) {
            $templates[$type] = $db->query(
                "SELECT * FROM settings_email_templates WHERE type = '" . $type . "'",
                SQL_ALL
            );
        }
        $core->assign("templates", $templates);
        $tpl_content = "email_templates";
        break;
}



