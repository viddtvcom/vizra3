<?php


/* arama */
if ($_GET['act'] == 'search') {
    if (strlen($_POST['query']) < 3) {
        core::raise('En az 3 harf girmelisiniz', 'e');
    } else {
        $sql = "SELECT * FROM kb_entries WHERE body LIKE '%" . $_POST['query'] . "%'";
        $entries = $db->query($sql, SQL_KEY, 'entryID');
        $core->assign('entries', $entries);
    }

    /* makale silme */
} elseif ($_GET['act'] == 'del_entry') {
    $KB = new Kb($_GET['entryID']);
    $KB->destroy();
    core::raise('Makale sistemden silindi', 'm', '?p=220&catID=' . $KB->catID);

    /* kategori silme */
} elseif ($_GET['act'] == 'del_cat') {
    Kb::removeCategory(Kb::getCategoryTree($_GET['catID']));
    $db->query("DELETE FROM kb_cats WHERE catID = " . $_GET['catID']);
    $db->query("DELETE FROM kb_entries WHERE catID = " . $_GET['catID']);
    core::raise('Kategori ve altındaki öğeler sistemden silindi', 'm', '?p=220');

    /* Makale ekleme */
} elseif ($_GET['act'] == 'add_entry') {
    if ($_POST['action'] == 'add') {
        $KB = new Kb();
        $KB->create();
        $KB->replace($_POST);
        $KB->adminID = getAdminID();
        $KB->update();
        redirect('?p=220&catID=' . $KB->catID . '&entryID=' . $KB->entryID);
    }
    /* Makale duzenleme */
} elseif ($_GET['act'] == 'edit_entry') {
    $KB = new Kb($_GET['entryID']);
    if ($_POST['action'] == 'update') {
        if ($_POST['catID'] == 0) {
            core::raise('"Genel" kategorisine makale ekleyemezsiniz', 'e');
            $_POST['catID'] = $KB->catID;
        }
        $KB->replace($_POST)->update();
        core::raise('Makale detayları güncellendi', 'm');
        redirect('?p=220&act=edit_entry&entryID=' . $KB->entryID);
    }

    $core->assign('cat_list', kb::getCategoryList(kb::getCategoryTree()));
    $core->assign('entry', $KB);
    $_GET['catID'] = $KB->catID;

    /* Kategori ekleme */
} elseif ($_GET['act'] == 'add_cat') {
    if ($_POST['action'] == 'add') {
        $sql = "INSERT INTO kb_cats (parentID,visibility,title,description) VALUES (
                " . (int)$_POST['parentID'] . ",'" . $_POST['visibility'] . "','" . sanitize(
            $_POST['title']
        ) . "','" . sanitize($_POST['description']) . "')";
        $db->query($sql);
        core::raise('Kategori eklendi', 'm', '?p=220&catID=' . $db->lastInsertID());
    }
    $core->assign('parent', kb::getCategory($_GET['catID']));

    /* Kategori duzenleme */
} elseif ($_GET['act'] == 'edit_cat') {
    if ($_POST['action'] == 'update') {
        $parent = kb::getCategory($_POST['parentID']);
        if (($_POST['visibility'] == 'everyone' && ($parent['visibility'] == 'client' || $parent['visibility'] == 'admin'))
                || $_POST['visibility'] == 'client' && $parent['visibility'] == 'admin'
        ) {
            core::raise(
                'Bu kategoriyi ' . $parent['title'] . ' kategorisi altına alamazsınız. (Görünebilirlik uyuşmazlığı)',
                'e'
            );
        } else {
            $sql = "UPDATE kb_cats SET  
                    parentID = " . (int)$_POST['parentID'] . ",
                    visibility = '" . $_POST['visibility'] . "',
                    title = '" . sanitize($_POST['title']) . "',
                    description = '" . sanitize($_POST['description']) . "'
                    WHERE catID = " . $_GET['catID'];
            $db->query($sql);
            core::raise('Kategori güncellendi', 'm', '?p=220&act=edit_cat&catID=' . $_GET['catID']);
        }
    }
    $core->assign('cat_list', kb::getCategoryList(kb::getCategoryTree(0, $_GET['catID'])));
    $cat = kb::getCategory($_GET['catID']);
    $core->assign('cat', $cat);
    $core->assign('parent', kb::getCategory($cat['parentID']));

    /* default */
} else {
    $cats = kb::getCategories($_GET['catID']);
    if (isset($_GET['catID'])) {
        $cat = kb::getCategory($_GET['catID']);
        $core->assign('entries', kb::getEntries($_GET['catID'], $_GET['entryID']));
        $core->assign('cat', $cat);
    }
    if (isset($_GET['entryID'])) {
        $core->assign('entry', new kb($_GET['entryID']));
    }

    $core->assign('cats', $cats);


}

$core->assign('bcrumbs', kb::getBreadcrumbs($_GET['catID']));
$tpl_content = 'kb.tpl';
