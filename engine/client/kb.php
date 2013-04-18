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
        $sql = "SELECT * FROM kb_entries ke INNER JOIN kb_cats kc ON kc.catID = ke.catID 
                WHERE body LIKE '%" . $_post['query'] . "%' AND kc.visibility NOT IN ('" . implode("','", $vis) . "')";
        $entries = $db->query($sql, SQL_KEY, 'entryID');
        $core->assign('entries', $entries);
    }
} else {
    $cats = kb::getCategories($_get['catID']);
    if ($_get['catID'] > 0) {
        $cat = kb::getCategory($_get['catID']);
        if ((! getClientID() && $cat['visibility'] != 'everyone') || $cat['visibility'] == 'admin') {
            core::raise('Böyle bir kategori girişi bulunamadı', 'e');
        } else {
            $core->assign('entries', kb::getEntries($_get['catID'], $_get['entryID']));
            $core->assign('cat', $cat);
            $core->assign('bcrumbs', kb::getBreadcrumbs($_get['catID'], '', $config['HTTP_HOST'] . '?p=kb'));
        }
    }
    if ($_get['entryID'] > 0) {
        $entry = new kb($_get['entryID']);
        if ((! getClientID() && $entry->parent['visibility'] != 'everyone') || $entry->parent['visibility'] == 'admin'
        ) {
            core::raise('Böyle bir makale girişi bulunamadı', 'e');
        } else {
            $core->assign('entry', $entry);
            if (! in_array($entry->entryID, (array)$_SESSION['kb_viewed'])) {
                $entry->viewed();
                $_SESSION['kb_viewed'][] = $entry->entryID;
            }
        }
    }

    $core->assign('cats', $cats);

}
$tplContent = 'kb.tpl';