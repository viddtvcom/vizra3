<?php


if ($_GET['act'] == 'search') {
    if (strlen($_POST['query']) < 3) {
        core::raise('En az 3 harf girmelisiniz', 'e');
    } else {
        $sql = "SELECT * FROM dc_files WHERE (description LIKE '%" . $_POST['query'] . "%' OR title LIKE '%" . $_POST['query'] . "%' OR origname LIKE '%" . $_POST['query'] . "%')";
        $files = $db->query($sql, SQL_KEY, 'fileID');
        $core->assign('files', $files);
    }
} elseif ($_GET['act'] == 'del_file') {
    $DC = new Download($_GET['fileID']);
    $DC->destroy();
    core::raise('Dosya sistemden silindi', 'm', '?p=230&catID=' . $DC->catID);

} elseif ($_GET['act'] == 'del_cat') {
    Download::removeCategory(Download::getCategoryTree($_GET['catID']));
    Download::deleteCategoryFiles($_GET['catID']);
    $db->query("DELETE FROM dc_cats WHERE catID = " . $_GET['catID']);
    core::raise('Kategori ve altındaki öğeler sistemden silindi', 'm', '?p=230');
} elseif ($_GET['act'] == 'add_file') {
    $max = ini_get('upload_max_filesize');

    if ($_POST['action'] == 'add') {
        $DC = new Download();
        $DC->create(true);
        $DC->replace($_POST);
        $DC->adminID = getAdminID();

        if ($_POST['method'] == 'web') {
            $ret = $DC->upload_web('file');
        } elseif ($_POST['method'] == 'ftp') {
            $ret = $DC->upload_ftp($_POST['filename']);
        }
        if (! $ret['st']) {
            core::raise($ret['msg'], 'e');
        }
        $DC->update();

        redirect('?p=230&catID=' . $DC->catID . '&fileID=' . $DC->fileID);
    }

    $core->assign('max', $max);

} elseif ($_GET['act'] == 'add_cat') {
    if ($_POST['action'] == 'add') {
        $sql = "INSERT INTO dc_cats (parentID,visibility,title,description) VALUES (
                " . (int)$_POST['parentID'] . ",'" . $_POST['visibility'] . "','" . sanitize(
            $_POST['title']
        ) . "','" . sanitize($_POST['description']) . "')";
        $db->query($sql);
        core::raise('Dosya Kategorisi eklendi', 'm', '?p=230&catID=' . $db->lastInsertID());
    }
    $core->assign('parent', Download::getCategory($_GET['catID']));

} elseif ($_GET['act'] == 'edit_cat') {
    if ($_POST['action'] == 'update') {
        $parent = Download::getCategory($_POST['parentID']);
        if (($_POST['visibility'] == 'everyone' && ($parent['visibility'] == 'client' || $parent['visibility'] == 'admin'))
                || $_POST['visibility'] == 'client' && $parent['visibility'] == 'admin'
        ) {
            core::raise(
                'Bu kategoriyi ' . $parent['title'] . ' kategorisi altına alamazsınız. (Görünebilirlik uyuşmazlığı)',
                'e'
            );
        } else {
            $sql = "UPDATE dc_cats SET  
                    parentID = " . (int)$_POST['parentID'] . ",
                    visibility = '" . $_POST['visibility'] . "',
                    title = '" . sanitize($_POST['title']) . "',
                    description = '" . sanitize($_POST['description']) . "'
                    WHERE catID = " . $_GET['catID'];
            $db->query($sql);
            core::raise('Dosya Kategorisi güncellendi', 'm', '?p=230&act=edit_cat&catID=' . $_GET['catID']);
        }
    }
    $core->assign('cat_list', Download::getCategoryList(Download::getCategoryTree(0, $_GET['catID'])));
    $cat = Download::getCategory($_GET['catID']);
    $core->assign('cat', $cat);
    $core->assign('parent', Download::getCategory($cat['parentID']));
} else {
    $cats = Download::getCategories($_GET['catID']);
    if (isset($_GET['catID'])) {
        $cat = Download::getCategory($_GET['catID']);
        $core->assign('files', Download::getFiles($_GET['catID'], $_GET['fileID']));
        $core->assign('cat', $cat);
    }
    if (isset($_GET['fileID'])) {
        $core->assign('file', new Download($_GET['fileID']));
    }

    $core->assign('cats', $cats);


}
if (! is_writeable($config['DOWNLOADS_DIR'])) {
    core:: raise('Dosya yükleyebilmeniz için ' . $config['DOWNLOADS_DIR'] . ' dizinine yazma hakkı vermelisiniz!', 'e');
}
$core->assign('bcrumbs', Download::getBreadcrumbs($_GET['catID']));
$tpl_content = 'downloads.tpl';
