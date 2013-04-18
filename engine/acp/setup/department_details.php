<?php

$Dep = new Department($_GET['depID']);
if (! $Dep->depID) {
    core::error("dep_load");
}

if ($_POST["action"] == "update") {
    $Dep->replace($_POST)->update();
}
if ($_POST) {
    redirect('?p=176&depID=' . $Dep->depID);
}


$core->assign("Dep", $Dep);

$tpl_content = "department_details";
$page_title .= ' &raquo; ' . $Dep->depTitle;
