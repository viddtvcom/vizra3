<?php
$iframe = true;

$Order = new Order($_GET["orderID"]);

if ($_POST['action'] == 'update') {
    $ret['st'] = true;
    $Order->replace($_POST)->update();
    if ($_POST['updateOnServer'] == '1') {
        $Order->loadService();
        $Parent = new Order($Order->parentID);
        $Parent->moduleQueueCmd($Order->Service->moduleCmd);
    }
    echo json_encode($ret);
    exit();
}

if (! $Order->orderID) {
    core::raise('Sipariş bulunamadı', 'e', '?p=410');
}

$Order->loadAttrs();

if ($Order->Service->moduleID) {
    $modConfig = $Order->getModuleConfig();

    foreach ($Order->attrs as $key => $obj) {
        $attrs[$key] = $modConfig['srvc'][$key];
        $attrs[$key]->value = $Order->attrs[$key]['value'];
    }
    $core->assign('srv', $attrs);
}


$core->assign("Order", $Order);
$tpl_content = "addon_details";  
