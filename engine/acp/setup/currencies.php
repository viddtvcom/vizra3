<?php

if ($_POST["action"] == "update") {
    unset($_SESSION['currencies']);

    $any_active_cur = false;
    foreach ($_POST["currencies"] as $curID => $v) {
        if ($_POST["status"][$curID] == 'active') {
            $any_active_cur = true;
        }
    }
    if ($any_active_cur == false) {
        core::raise('En az 1 aktif kur bulunmalıdır', 'e', 'rt');
    }

    if ($_POST["status"][$_POST['main_cur_id']] != 'active') {
        core::raise('Ana kur, aktif bir kur olmalıdır', 'e', 'rt');
    }

    foreach ($_POST["currencies"] as $curID => $v) {
        $sql = "UPDATE settings_currencies
                SET status = '" . $_POST["status"][$curID] . "',
                    description = '" . $_POST["description"][$curID] . "',
                    code = '" . $_POST["code"][$curID] . "',
                    symbol = '" . $_POST["symbol"][$curID] . "',
                    ratio = '" . $_POST["ratio"][$curID] . "' WHERE curID = $curID";
        $db->query($sql);
    }
    if ($_POST["new_currency"] != "") {
        $maxRowOrder = $db->query("SELECT MAX(rowOrder) as maxro FROM settings_currencies", SQL_INIT, "maxro") + 1;
        $db->query(
            "INSERT INTO settings_currencies (description,rowOrder) VALUES ('" . $_POST["new_currency"] . "'," . $maxRowOrder . ")"
        );
    }
    // main currency change
    if (MAIN_CUR_ID != $_POST['main_cur_id']) {
        //debug($config['CURTABLE'],1);
        $new_ratio = $config['CURTABLE'][$_POST['main_cur_id']]['ratio'];
        foreach ($config['CURTABLE'] as $curID => $data) {
            $db->query("UPDATE settings_currencies SET ratio = ratio / " . $new_ratio . " WHERE curID = " . $curID);
        }
        $sql = "UPDATE settings_general SET  value = '" . $_POST['main_cur_id'] . "' WHERE setting = 'main_cur_id'";
        $db->query($sql);

        // maint
        $db->query("UPDATE payments SET xamount = xamount / " . $new_ratio);
        $db->query("UPDATE order_bills SET xamount = xamount / " . $new_ratio);

    }
    core::raise('Kur bilgileri güncellendi', 'm', '?p=165');
} elseif ($_GET["act"] == "move") {
    core::moveRow("settings_currencies", "curID", $_GET["curID"], $_GET["dir"]);
    redirect('?p=165');
}

$sql = "SELECT * FROM settings_currencies ORDER BY rowOrder ASC";
$curs = $db->query($sql, SQL_ALL);
$core->assign('curs', $curs);
$core->assign('main_cur_id', MAIN_CUR_ID);


$tpl_content = "currencies";





