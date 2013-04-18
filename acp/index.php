<?php
$_start = microtime(true);

require('../engine/init.php');
if (USE_SSL_ACP == '1') {
    forceSSL();
}
require_once("func.admin.php");
secure();
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/acp');
$lang = loadLangFile($config['LANG'], 'backend');


$core = new vSmarty("acp");
$iframe = true;

$page_modules = array(2 => 210, 3 => 310, 4 => 410, 5 => 510, 7 => 710, 6 => 610, 1 => 140);
$core->assign('page_modules', $page_modules);


// sanitize GET 
$_GET = sanitize($_GET, "both");

$core->assign('Admin', $_SESSION['vadmin']);

if ($_SESSION['vadmin']->settings['staticChatWindow'] . $_SESSION['vadmin']->settings['staticLogWindow'] == '' && $_GET['m'] != 'compact') {
    $iframe = false;
}

if ($_SESSION['vadmin']->settings['staticProbesColumn'] == '1') {
    $sql = "SELECT sp.*, ss.*, s.serverName,s.serverID FROM servers s  
                LEFT JOIN server_probes sp ON s.serverID = sp.serverID
                LEFT JOIN server_settings ss ON (ss.serverID = s.serverID 
                                                    AND ss.setting = 'load_monitor' 
                                                    AND ss.value = '1'
                                                    AND s.moduleID IN ('plesk','cpanel'))
            WHERE (probeID  > 0 OR setting = 'load_monitor') AND s.status = 'active'";
    $probes = (array)$db->query($sql, SQL_ALL);
    foreach ($probes as $p) {
        $prbs[$p['serverID']]['serverName'] = $p['serverName'];

        if ($p['probeID']) {
            $prbs[$p['serverID']]['probes'][] = $p;
        }

        if ($p['setting'] == 'load_monitor') {
            $prbs[$p['serverID']]['loadmon'] = true;
        }
    }
    $core->assign('right_probes', $prbs);
    $iframe = true;
}

$core->assign('submenu', get_submenu());

if ($_GET["p"] == "" && $iframe) {
    $core->assign('iframe', $iframe);
    $core->display('index.tpl');
    exit();
} else {
    if ($_GET['p'] == "") {
        $_GET['p'] = 140;
    }

}


$page = get_page($_GET['p']);

if (! in_array($_GET['p'], array(116))) {
    //$page_title .= "<a href='?p=".$page["pageID"]."'>" .lang("page_".$page["pageID"])."</a>";
    $page_title .= lang("page_" . $page["pageID"]);
}



if ($page['pageID'] != 112 && ! priv_check($page['pageID'], 1)) {
    core::raise(lang('page_' . $page["pageID"]) . ': Bu sayfayı görüntüleme yetkiniz bulunamamaktadır', 'e', '?p=112');
} elseif ($page) {
    require_once($vars['PAGE_MODULES'][$page['moduleID']] . "/" . $page["filename"]);
} else {
    die("module bulunamadi");
}

$core->assign("action_menu", $action_menu);
$core->assign("page_title", $page_title);
$core->assign("page_icon", $page_icon);
$core->assign("admin", $_SESSION["vadmin"]);
$core->assign('tab', $_GET['tab']);
$core->assign('subtab', $_GET['subtab']);
$core->assign('page', $page);

// Errors Message and Warnings
$core->assign("errors", $_SESSION["errors"]);
unset($_SESSION["errors"]);
$core->assign("messages", $_SESSION["messages"]);
unset($_SESSION["messages"]);
$core->assign('warnings', $_SESSION['warnings']);
unset($_SESSION['warnings']);


if (isset($tpl_content)) {
    $tpl_content = $vars['PAGE_MODULES'][$page['moduleID']] . "/" . $tpl_content;

    if (substr($tpl_content, - 4) != ".tpl") {
        $tpl_content .= ".tpl";
    }

    $core->assign('tpl_content', $tpl_content);

    // include tab menu if exists
    $tpl_tabmenu = str_replace('.tpl', '_menu.tpl', $tpl_content);
    if ($core->template_exists($tpl_tabmenu)) {
        $core->assign('tpl_tabmenu', $tpl_tabmenu);
    }

    $core->assign('iframe', $iframe);

    if ($iframe) {
        $core->display('iframe.tpl');
    } else {
        $core->display('index.tpl');
    }

}

$_time = microtime(true) - $_start;


