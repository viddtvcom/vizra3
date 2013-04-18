<?php

if ($_POST["action"] == "add") {
    if (Client::emailExists($_POST['email'])) {
        core::raise('Bu email sistemde kayıtlı', 'e', '');
    } elseif (! validate_email($_POST['email'])) {
        core::raise('Geçersiz email adresi', 'e', '');
    } elseif ($_POST['name'] == '') {
        core::raise('Geçersiz Ad Soyad', 'e', '');
    } else {
        $Client = new Client();
        $_POST['autoSuspend'] = '1';
        $Client->create($_POST['email']);
        if ($Client->clientID) {
            $Client->replace($_POST)->update();
            $Client->setPassword($_POST['password']);
            iredirect("client_details", $Client->clientID);
        } else {
            core::error("client_add");
        }
    }
}


$tpl_content = "add_client";


