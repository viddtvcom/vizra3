<?php
require('../../engine/init.php');
require_once("func.admin.php");
secure();
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/acp');

$core = new vSmarty("acp");

if (isset($_GET['a'])) {
    $_POST['action'] = $_GET['a'];
}

switch ($_POST['action']) {

    case 'get_kb_list':

        $core->assign('entry_tree', getEntryTree(0));
        echo $core->fetch('support/kb_list.tpl');
        exit;

}


function getEntryTree($parentID = 0)
{
    global $db;
    $cats = kb::getCategories($parentID);
    $html = ($parentID) ? '<ul>' : '<ul id="entry_tree" class="filetree">';

    foreach ($cats as $catID => $c) {
        $html .= '<li><span class="folder">' . $c['title'] . '</span>';
        $children = getEntryTree($catID);
        if ($children) {
            $html .= $children;
        }
        if ($c['entries']) {
            $html .= '<ul>';
            $sql = "SELECT entryID,title FROM kb_entries WHERE catID = " . $catID . " AND entryID != " . (int)$exclude . " ORDER BY title";
            $entries = $db->query($sql, SQL_KEY, 'entryID');
            foreach ($entries as $entryID => $title) {
                $html .= '<li><span class="file" entryID="' . $entryID . '">' . $title . '</span></li>';
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}



