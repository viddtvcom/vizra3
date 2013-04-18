<?php
define('VZUSERTYPE', 'admin');

function secure()
{
    if (! isset($_SESSION["vadmin"]->adminID) || ! is_numeric($_SESSION["vadmin"]->adminID)) {
        // try to login with cookie
        $auth = false;
        if (isset($_COOKIE['_vap']) && isset($_COOKIE['_vae'])) {
            $auth = Admin::loginWithCookie();
        }
        if (! $auth) {
            redirect("login.php?p=1");
        }
    }
    define(ADMINID, $_SESSION["vadmin"]->adminID);
}

function getAdminID()
{
    return $_SESSION["vadmin"]->adminID;
}

function get_submenu()
{
    if ($_SESSION['ACP_SUBMENU'] == false) {
        global $db;
        $_SESSION['ACP_SUBMENU'] = $db->query(
            "SELECT pageID,moduleID FROM pages WHERE showOnSubmenu = '1' ORDER BY rowOrder",
            SQL_MKEY,
            'moduleID'
        );
    }
    return $_SESSION['ACP_SUBMENU'];
}

function get_page($pageID)
{
    $pageID = (int)$pageID;
    if ($_SESSION['ACP_PAGES'][$pageID] == false) {
        global $db;
        $_SESSION['ACP_PAGES'][$pageID] = $db->query("SELECT * FROM pages p WHERE pageID = " . $pageID, SQL_INIT);
    }

    return $_SESSION['ACP_PAGES'][$pageID];

}


function iredirect($where, $what)
{
    switch ($where) {
        case 'ticket_details':
            $url = "?p=210&act=view_ticket&ticketID=" . $what;
            break;
        case 'admin_details':
            $url = "?p=110&act=admin_details&adminID=" . $what;
            break;
        case 'service_details':
            $url = "?p=116&serviceID=" . $what;
            break;
        case 'service_attr_details':
            $url = "?p=120&act=attr_details&attrID=" . $what;
            break;
        case 'server_details':
            $url = "?p=125&act=server_details&serverID=" . $what;
            break;
        case 'client_details':
            $url = "?p=311&clientID=" . $what;
            break;
    }
    redirect($url);
}

function breadcrumbs($pageID)
{
    $Page = new Page($pageID);
    if ($Page->parentID) {
        $Parent = new Page($Page->parentID);
        $ret = ' &raquo; <a href="?p=' . $Parent->pageID . '">##page_' . $Parent->pageID . '##</a> &raquo;';
    }
    return $ret;
}

function cronLastRun($filename, $timestamp = false)
{
    global $db;
    $date = $db->query("SELECT dateStart FROM crons WHERE filename = '" . $filename . "'", SQL_INIT, 'dateStart');
    if ($date) {
        return $timestamp ? $date : formatDate($date, 'datetime');
    } else {
        return "Bu Cron henüz hiç çalışmadı";
    }
}

function cronLock($lock, $stale = 1)
{
    global $config;
    $lockfile = $config['TMP_DIR'] . $lock . '.lock';
    if (file_exists($lockfile)) {
        if (fileatime($lockfile) < (time() - (60 * $stale))) {
            /* bayat lock */
            unlink($lockfile);
            touch($lockfile);
            return true;
        } else {
            /* zaten çalışıyor */
            echo "zaten calisiyor..\n";
            die();
        }
    } else {
        touch($lockfile);
        return true;
    }
}

function cronUnlock($lock)
{
    global $config;
    $lockfile = $config['TMP_DIR'] . $lock . '.lock';
    unlink($lockfile);
}

function getClientTemplates()
{
    global $config;
    $dir = $config['BASE_PATH'] . 'themes' . DIRECTORY_SEPARATOR . 'client';
    if (file_exists($dir)) {
        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir() && ! $file->isDot()) {
                $ret[] = $file->getFilename();
            }
        }
    }
    return $ret;
}

function addServiceGroup($parentID, $status, $group_name)
{
    global $db;
    $maxro = $db->query("SELECT MAX(rowOrder) AS maxro FROM service_groups", SQL_INIT, 'maxro');
    $sql = "INSERT INTO service_groups (parentID,status,group_name,seolink,rowOrder) 
            VALUES ('" . $parentID . "','" . $status . "','" . $group_name . "','" . finename(
        $group_name
    ) . "'," . ($maxro + 1) . ")";
    $db->query($sql);
    return $db->lastInsertID();
}

function addService($service_name, $moduleID, $groupID, $type, $addon)
{
    global $db;
    $Service = new Service();
    $Service->create();
    $Service->type = $type;
    $Service->service_name = $service_name;
    $Service->moduleID = $moduleID;
    $Service->groupID = $groupID;
    $Service->addon = $addon;
    $Service->update();
    $Service->bindModule($_POST['moduleID']);
}

function langneutr($str)
{
    return str_replace('##', '####', $str);
}


function priv_setbit(&$val, $bits)
{
    if (is_array($bits)) {
        foreach ($bits as $bit) {
            $val = $val | (priv_leftshift32(1, $bit - 1));
        }
    } else {
        $val = $val | (priv_leftshift32(1, $bits - 1));
    }
}

function priv_clearbit(&$val, $bits)
{
    if (is_array($bits)) {
        foreach ($bits as $bit) {
            $val = $val & ~priv_leftshift32(1, $bit - 1);
        }
    } else {
        $val = $val & ~priv_leftshift32(1, $bits - 1);
    }
}

function priv_readbit($val, $bit)
{
    return ($val & priv_leftshift32(1, $bit - 1)) ? '1' : '0';
}

function priv_bitwiseprint($var, $bitlength = 32)
{
    for ($j = $bitlength; $j > 0; $j --) {
        echo priv_readbit($var, $j);
        if ($j % 4 == 1) {
            echo ' ';
        }
    }
}

function priv_check($pageID, $bit)
{
    if ($_SESSION["vadmin"]->type == "super-admin") {
        return true;
    }
    $_SESSION["vadmin"]->loadPagePrivs();

    return priv_readbit($_SESSION["vadmin"]->privs[$pageID]["priv"], $bit) == 1;
}

function privcheck($bit)
{
    if (! priv_check($_GET['p'], $bit)) {
        $msg[2] = 'Bu sayfada güncelleme yetkiniz bulunmamaktadır';
        $msg[4] = 'Bu sayfada silme yetkiniz bulunmamaktadır';
        core::raise($msg[$bit], 'e', 'rt');
    } else {
        return true;
    }
}

function priv_leftshift32($number, $steps)
{
    if ($steps < 0) {
        $steps += 32;
    }
    $binary = decbin($number) . str_repeat("0", $steps);
    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
    $binary = substr($binary, strlen($binary) - 32);
    return $binary{0} == "1" ? - (pow(2, 31) - bindec(substr($binary, 1))) : bindec($binary);
}


