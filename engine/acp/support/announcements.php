<?php

if ($_POST['action'] == 'add') {
    unset($_SESSION['announcements']);
    $sql = "INSERT INTO announcements (adminID,status,title,body,dateAdded) VALUES (
            " . ADMINID . ",'active','" . sanitize($_POST['title']) . "','" . sanitize(
        $_POST['body'],
        false
    ) . "',UNIX_TIMESTAMP())";
    $db->query($sql);
    core::raise('Duyuru eklendi', 'm', '?p=225');
} elseif ($_POST['action'] == 'update') {
    unset($_SESSION['announcements']);
    $sql = "UPDATE announcements SET status = '" . $_POST['status'] . "',
                                     title = '" . sanitize($_POST['title']) . "',
                                     body = '" . sanitize($_POST['body'], false) . "' WHERE recID = " . $_GET['recID'];
    $db->query($sql);
    core::raise('Duyuru güncellendi', 'm', '?p=225&act=edit&recID=' . $_GET['recID']);
} elseif ($_GET['act'] == 'del') {
    unset($_SESSION['announcements']);
    $db->query("DELETE FROM announcements WHERE recID = " . $_GET['recID']);
    core::raise('Duyuru silindi', 'm', '?p=225');
}

$action_menu[] = array('Yeni Duyuru Ekle', '?p=225&act=add');

if ($_GET['act'] == 'add') {
    $tpl_content = 'add_announcement.tpl';
} elseif ($_GET['act'] == 'edit') {
    $rec = $db->query("SELECT * FROM announcements WHERE recID = " . $_GET['recID'], SQL_INIT);
    $core->assign('rec', $rec);
    $tpl_content = 'announcement_details.tpl';
} else {
    $recs = $db->query(
        "SELECT a.*, ad.adminName FROM announcements a INNER JOIN admins ad ON ad.adminID = a.adminID  ORDER BY a.dateAdded DESC",
        SQL_ALL
    );
    $core->assign('recs', $recs);
    $tpl_content = 'announcements.tpl';
}
  

  

