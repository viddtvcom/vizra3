<?php


function secure($return = '')
{
    if (! isset($_SESSION["vclient"]->clientID) || ! is_numeric($_SESSION["vclient"]->clientID)) {
        if ($return != '') {
            core::raise('Devam etmek için lütfen sisteme girişi yapınız', 'e');
            $_SESSION['return_after_login'] = $return;
        } else {
            $_SESSION['return_after_login'] = $_SERVER['REQUEST_URI'];
        }
        redirect("index.php?p=user&s=login");
    }
    //define('CLIENTID',$_SESSION["vclient"]->clientID);
}

function getClientID()
{
    return $_SESSION["vclient"]->clientID;
}

/* @return Order */
function getClientOrder($orderID)
{
    $Order = new Order($orderID);
    if ($Order->clientID != $_SESSION["vclient"]->clientID) {
        unset($Order);
        return false;
    } else {
        return $Order;
    }
}

/* @return Domain */
function getClientDomain($domainID)
{
    $Domain = new Domain($domainID);
    if ($_SESSION["vclient"]->clientID != order::getClientIdFromOrderId($Domain->orderID)) {
        unset($Domain);
        return false;
    } else {
        return $Domain;
    }
}

/* @return Contact */
function getClientContact($contactID)
{
    $Contact = new Contact($contactID);
    if ($_SESSION["vclient"]->clientID != $Contact->clientID) {
        unset($Contact);
        return false;
    } else {
        return $Contact;
    }
}


function displayPage($tpl)
{
    if ($tpl == '') {
        return false;
    }
    global $core;

    $GLOBALS['_VLC_TYPE'] = core::_vlc_gettype();

    $core->display('head.tpl');
    $core->display($tpl);
    if ($GLOBALS['_VLC_TYPE'] != 'nobrand') {
        echo '<br /><br /><p align="center">';
        echo '<a href="http://www.vizra.com" title="Hosting Müşteri Takip Otomasyon" target="_blank">&copy; Vizra</a></p>';
    }

    $core->display('foot.tpl');

}

function getProductMenu()
{
    global $db;
    $domext_count = $db->query(
        "SELECT COUNT(serviceID) as cnt FROM domain_extensions WHERE status = 'active'",
        SQL_INIT,
        'cnt'
    );
    if (! $domext_count) {
        $inSQL = " AND s.groupID != 10";
    }
    $sql = "SELECT sg.*, s.serviceID 
            FROM service_groups sg LEFT JOIN services s ON (s.groupID = sg.groupID AND s.status = 'active')
            WHERE parentID = 1 
                    AND sg.status = 'active'
                    AND s.serviceID IS NOT NULL $inSQL
                GROUP BY sg.groupID 
                ORDER BY sg.rowOrder ASC";
    $main = $db->query($sql, SQL_ALL);

    /*    foreach($main as $key=>$value) {
            $main[$key]['entries'] = $db->query("SELECT * FROM service_groups WHERE parentID = ".$main["$key"]["groupID"]." AND status = 'active'",SQL_ALL);
        } */
    return $main;
}

function getShopServices()
{
    global $db;
    $sql = "SELECT s.*,MD5(CONCAT(s.serviceID,'-',s.dateAdded)) as avatar FROM services s
                INNER JOIN service_groups sg ON sg.groupID = s.groupID
            WHERE  sg.groupID != 10 AND s.addon != '1' AND s.status = 'active' AND s.sfOrder > 0
            ORDER BY sfOrder";
    $services = $db->query($sql, SQL_KEY, 'serviceID');

    $price_options = $db->query(
        "SELECT * FROM service_price_options WHERE `default` = '1' ORDER BY period ASC",
        SQL_MKEY,
        'serviceID'
    );

    foreach ($services as $serviceID => $data) {
        if ($data['setup_discount'] > 0) {
            $services[$serviceID]['setup'] -= $data['setup_discount'];
            $services[$serviceID]['onsale'] = true;
        }

        if (! $price_options[$serviceID]) {
            continue;
        }
        $price = $price_options[$serviceID][0];
        if ($price['discount'] > 0) {
            $price['price'] -= $price['discount'];
            $services[$serviceID]['onsale'] = true;
        }
        $price['display'] = displayPrice($price['price'], $price['period'], $data['paycurID'], 1);
        $price['display2'] = explode(' - ', $price['display']);

        $services[$serviceID]['price'] = $price;


    }

    return $services;
}

function setSelectedLanguage($lang)
{
    setcookie("selLang", $lang, time() + 60 * 60 * 24 * 365 * 10, "/");
}

function getSelectedLanguage()
{
    return isset($_COOKIE['selLang']) ? $_COOKIE['selLang'] : getSetting('portal_lang');
}

function getAnnouncements($st)
{
    if ($_SESSION['announcements'] == false) {
        global $db;
        $_SESSION['announcements'] = $db->query(
            "SELECT * FROM announcements WHERE status IN (" . $st . ") ORDER BY dateAdded DESC",
            SQL_ALL
        );
    }
    return $_SESSION['announcements'];
}
