<?php

if (! file_exists('engine/config/config.php')) {
    header('location:install/');
}
require('engine/init.user.php');

if (getSetting('portal_status') == 'maintenance' && $_get['p'] != 'announcements') {
    $_get['p'] = 'maintenance';
}

if ($_get['p'] == 'seo') {
    $groupID = $db->query(
        "SELECT groupID FROM service_groups WHERE seolink = '" . sanitize($_get['link']) . "'",
        SQL_INIT,
        'groupID'
    );
    if ($groupID) {
        $_get['p'] = 'shop';
        if ($groupID == '10') {
            $_get['s'] = 'domain';
        } else {
            $_get['s'] = 'sf';
            $_get['a'] = 'list';
            $_get['gID'] = $groupID;
        }
    } else {
        $serviceID = $db->query(
            "SELECT serviceID FROM services WHERE seolink = '" . sanitize($_get['link']) . "'",
            SQL_INIT,
            'serviceID'
        );
        if ($serviceID) {
            $_get['p'] = 'shop';
            $_get['s'] = 'srv';
            $_get['sID'] = $serviceID;
        } else {
            redirect('/');
        }
    }
}

switch ($_get["p"]) {
    case 'shop':
        require_once("shop.php");
        break;
    case 'payment':
        secure('?p=payment&a=checkout');
        require_once("payment.php");
        break;
    case 'cart':
        require("cart.php");
        break;
    case 'user':
        require("user.php");
        break;
    case 'announcements':
        $tplContent = "announcements.tpl";
        break;
    case 'kb':
        require('kb.php');
        break;
    case 'dc':
        require('downloads.php');
        break;
    case 'maintenance':
        $tplContent = 'maintenance.tpl';
        break;
    default:
        if ($core->template_exists('home.tpl')) {
            $core->assign('sh_services', getShopServices());
            $tplContent = 'home.tpl';
        } else {
            redirect('?p=user&s=login');
        }
        break;
}


if ($_SESSION["errors"]) {
    $core->assign("errors", $_SESSION["errors"]);
    unset($_SESSION["errors"]);
}
if ($_SESSION["messages"]) {
    $core->assign("messages", $_SESSION["messages"]);
    unset($_SESSION["messages"]);
}

displayPage($tplContent);











