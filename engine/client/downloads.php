<?php
$_get['catID'] = intval($_get['catID']);
$_get['entryID'] = intval($_get['entryID']);


if ($_get['act'] == 'search') {
    if (strlen($_post['query']) < 3) {
        core::raise('En az 3 harf girmelisiniz', 'e');
    } else {
        $vis = array('admin');
        if (! getClientID()) {
            $vis[] = 'client';
        }
        $sql = "SELECT dcf.* FROM dc_files dcf INNER JOIN dc_cats dcc ON dcc.catID = dcf.catID 
                WHERE (dcf.description LIKE '%" . $_post['query'] . "%' OR dcf.title LIKE '%" . $_post['query'] . "%' OR dcf.origname LIKE '%" . $_post['query'] . "%')
                    AND dcc.visibility NOT IN ('" . implode("','", $vis) . "')";
        $files = $db->query($sql, SQL_KEY, 'fileID');
        $core->assign('files', $files);
    }
} else {
    $cats = Download::getCategories($_get['catID']);
    if ($_get['catID'] > 0) {
        $cat = Download::getCategory($_get['catID']);
        if ((! getClientID() && $cat['visibility'] != 'everyone') || $cat['visibility'] == 'admin') {
            core::raise('Böyle bir kategori girişi bulunamadı', 'e');
        } else {
            $core->assign('files', Download::getFiles($_get['catID'], $_get['entryID']));
            $core->assign('cat', $cat);
            $core->assign('bcrumbs', Download::getBreadcrumbs($_get['catID'], '', $config['HTTP_HOST'] . '?p=dc'));
        }
    }

    $core->assign('cats', $cats);

}
$tplContent = 'downloads.tpl';