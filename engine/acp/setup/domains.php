<?php

if ($_POST["action"] == "update") {
    if ($_POST["extensions"]) {
        foreach ($_POST["extensions"] as $serviceID => $v) {
            $sql = "UPDATE domain_extensions
                SET priceRegister = '" . $_POST["priceRegister"][$serviceID] . "',
                    priceRenew = '" . $_POST["priceRenew"][$serviceID] . "',
                    priceTransfer = '" . $_POST["priceTransfer"][$serviceID] . "',
                    status = '" . $_POST["status"][$serviceID] . "',
                    domlock = '" . $_POST["domlock"][$serviceID] . "',
                    authcode = '" . $_POST["authcode"][$serviceID] . "',
                    periodMax = '" . $_POST["periodMax"][$serviceID] . "' WHERE serviceID = $serviceID";
            $db->query($sql);
            $Service = new Service($serviceID);
            $Service->status = $_POST["status"][$serviceID];
            $Service->moduleID = $_POST["moduleID"][$serviceID];
            $Service->paycurID = $_POST["paycurID"][$serviceID];
            $Service->type = 'domain';
            $Service->provisionType = 'auto';
            $Service->update();
        }
    }

    if ($_POST["new_extension"] != "") {
        $extension = trim($_POST["new_extension"], ".");
        $Service = new Service();
        $Service->create();
        $Service->set("service_name", "." . $extension . " Alan Adı Tescil Hizmeti")->set("groupID", 10);
        $maxRowOrder = $db->query("SELECT MAX(rowOrder) as maxro FROM domain_extensions", SQL_INIT, "maxro") + 1;
        $db->query(
            "INSERT INTO domain_extensions (serviceID,extension,status,rowOrder) VALUES (" . $Service->serviceID . ",'" . $extension . "','inactive'," . $maxRowOrder . ")"
        );
    }
} elseif ($_GET["act"] == "move") {
    core::moveRow("domain_extensions", "serviceID", $_GET["serviceID"], $_GET["dir"]);
}
$sql = "SELECT * FROM domain_extensions de
            INNER JOIN services s ON s.serviceID = de.serviceID
        ORDER BY de.rowOrder ASC";
$exts = $db->query($sql, SQL_ALL);
$core->assign("exts", $exts);
$core->assign("registrars", Module::getActiveModules("domain"));


$tpl_content = "domains";





