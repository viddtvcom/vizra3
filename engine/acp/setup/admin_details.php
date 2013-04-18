<?php
$Admin = new Admin($_GET["adminID"]);
if (! $Admin->adminID) {
    core::error("admin_load");
}


if ($_POST['action'] == 'setPriv') {
    $pageID = substr($_POST['id'], 0, 3);
    $bit = substr($_POST['id'], - 1);
    $Admin->setPagePriv($pageID, $bit, $_POST['value']);
    echo json_encode(array('st' => true, 'id' => $_POST['id']));
    exit;
}

if ($_POST["action"] == "update") {

    if (DEMO == true && $Admin->adminID == 1) {
        core::raise('Demo modunda bu adminin bilgileri değiştiremezsiniz', 'e');
    } else {
        if ($Admin->adminEmail != $_POST['adminEmail'] && Admin::emailExists($_POST['adminEmail'])) {
            core::raise('Bu email adresi sistemde zaten kayıtlı', 'e', '?p=111&adminID=' . $Admin->adminID);

        } elseif ($Admin->type == 'super-admin' && $_POST['type'] == 'admin') {
            /* baska super admin var mi kontrol et, yoksa izin verme */
            $sa_count = $db->query(
                "SELECT COUNT(adminID) AS sa_cnt FROM admins WHERE type = 'super-admin'",
                SQL_INIT,
                'sa_cnt'
            );
            if ($sa_count < 2) {
                core::raise('Sistemde en az bir Super-Admin bulunmalıdır', 'e', '?p=111&adminID=' . $Admin->adminID);
            }
        }
        $Admin->replace($_POST)->update();
        if ($_POST['adminPassword'] != '') {
            $Admin->setPassword($_POST['adminPassword']);
        }
        core::raise('Yönetici bilgileri güncellendi', 'm');
    }
    redirect('?p=111&tab=gen&adminID=' . $Admin->adminID);

} elseif ($_POST["action"] == "update_deps") {
    $Admin->updateDeps($_POST['deps']);
    redirect('?p=111&tab=deps&adminID=' . $Admin->adminID);
}

$deps = $db->query("SELECT * FROM departments", SQL_ALL, 'depID');
$core->assign('deps', $deps);


if ($_GET['tab'] == 'privs') {
    $pages = $db->query("SELECT * FROM pages ORDER BY moduleID", SQL_ALL);
    $pages = core::array_split($pages, "moduleID");
    $core->assign("pages", $pages);
    $core->assign("privs", $Admin->getPagePrivs());
}

$core->assign("eAdmin", $Admin);
$tpl_content = "admin_details";


$page_title = $Admin->adminName;
$page_icon = 'user-black.png';
