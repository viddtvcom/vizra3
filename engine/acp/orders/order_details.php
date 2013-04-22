<?php


if ($_GET['p'] == '411') {
    redirect('?p=311&tab=orders&orderID=' . $_GET["orderID"]);
}

$Order = new Order($_GET["orderID"]);
$Order->loadService();

if (! $Order->orderID) {
    core::raise('Sipariş bulunamadı', 'e', '?p=410');
}
if ($Order->parentID) {
    redirect('?p=411&tab=addons&orderID=' . $Order->parentID);
}

preg_match("/\((.*)\)/", $Order->title, $matches);

if ($matches[1]) {
    $core->assign('order_domain', $matches[1]);
}


switch ($_GET['subtab']) {
    case 'domain':
        require('domain_details.php');
        break;

    case 'bills':
        require('order_bills.php');
        break;

    case 'attrs':
        require('order_details_attrs.php');
        break;

    case 'actions':
        require('order_details_actions.php');
        break;

    default:
        require('order_details_general.php');

}

if ($_POST) {
    redirect("?p=311&tab=orders&orderID=" . $Order->orderID . "&subtab=" . $_GET['subtab']);
}

/// Addons
if (! $Order->parentID) {
    $Order->addonOrders = $Order->loadAddonOrders();
    //$core->assign('addonServices',$Order->Service->getAddons($Order->payType));
}

if ($Order->parentID) {
    $core->assign('Parent', new Order($Order->parentID));
}


$Order->loadClient();
$core->assign("Order", $Order);
$core->assign("icons", Order::$icons);
$tpl_content = "order_details";

/*$page_title .= ' <img src="'.$config["turl"].'/images/status_'.$Order->Client->status.'.png" width="11" id="middle">';
$page_title .= ' <a href="?p=311&clientID='.$Order->clientID.'">'.$Order->Client->name.'</a>';
$page_title .= ' &raquo; <a href="index.php?p=411&act=order_details&orderID='.$Order->orderID.'">'.$Order->orderID.' : '.$Order->title.'</a>';   */






