<?php

if ($_POST["action"] == "add") {
    $Order = new order();
    $Order->dateStart = core::str2time($_POST['dateStart']);

    $Service = new Service($_POST['serviceID']);
    if ($Service->groupID == 10) {
        $Domain = new Domain();
        $Domain->create($_POST['domain']);

        $Order->create($Domain->extensionData["serviceID"], $_POST["clientID"], 12, $Domain);
        $Order->start(0, (bool)$_POST['paymentStatus'], (bool)$_POST['billStatus'], false, false);

        $Domain->orderID = $Order->orderID;
        $Domain->update();
        //$Domain->refresh();         
    } else {
        $Order->create($_POST["serviceID"], $_POST["clientID"], $_POST["period"]);
        $Order->set('serverID', $_POST['serverID']);
        $Order->updateAttrs($_POST['att']);
        $Order->start(0, (bool)$_POST['paymentStatus'], (bool)$_POST['billStatus'], $_POST['provision'], false);

    }

    $Order->setTitle();

    if ($Order->orderID) {
        redirect('?p=411&orderID=' . $Order->orderID);
    } else {
        core::error("order_add");
    }
} else {
    $Client = new Client($_GET['clientID']);
    if (! $Client->clientID) {
        core::raise('Müşteri bulunamadı', 'e');
        redirect('?p=410');
    }
    if ($_GET["serviceID"]) {
        $Service = new Service($_GET["serviceID"]);
        $Service->getPriceOptions();

        $core->assign("Service", $Service);
        $core->assign('servers', $Service->getServers());

    }
    $core->assign('Client', $Client);

    $sql = "SELECT * FROM services WHERE groupID != 1990 AND addon != '1' ORDER BY groupID,service_name";
    $select_services = $db->query($sql, SQL_ALL);
    $core->assign(
        "select_services",
        array2select(
            $select_services,
            "serviceID",
            "serviceID",
            "service_name",
            $_GET["serviceID"],
            "<option value='0'>Seçiniz</option>"
        )
    );

    $tpl_content = "add_order";
}

