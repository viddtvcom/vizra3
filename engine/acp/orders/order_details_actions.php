<?php
if ($_POST['action'] == 'transfer_order') {
    $ret = $Order->transfer($_POST['clientID'], $_POST['transfer_funds']);
    if ($ret['st']) {
        core::raise('Sipariş taşıma işlemi başarılı', 'm', '');
    } else {
        core::raise($ret['msg'], 'e', '');
    }

}