<?php
if (USE_SSL_UCP == '1') {
    forceSSL();
}

$core->assign("s", $_get["s"]);

if ($_SESSION["force_required_fields"] == true && $_get['s'] != 'details' && $_get['s'] != 'logout') {
    core::raise('Lütfen bilgilerinizi güncelleyiniz', 'e');
    redirect('?p=user&s=details');
}


switch ($_get["s"]) {
    case 'register':
        require("register.php");
        break;
    case 'login':
        $tplContent = "authentication.tpl";
        if ($_post['action'] == 'remind_password') {
            if ($_SESSION['form_token'][2] != $_post['token']) {
                core::raise('InvalidOperation', 'e');
            } elseif (! validate_email($_post['email'])) {
                core::raise('InvalidEmail', 'e');
            } elseif (! Client::emailExists($_post['email'])) {
                core::raise('EmailNotFound', 'e');
            } else {
                Client::sendPassword($_post['email']);
                core::raise('WeHaveSentYourDetails', 'm');
            }
        } elseif ($_post['action'] == 'validate_login') {
            if ($_SESSION['form_token'][1] != $_post['token']) {
                core::raise('InvalidOperation', 'e');
            } elseif (! validate_email($_post["email"])) {
                core::raise('InvalidEmail', 'e');
            } else {
                $clientID = Client::authenticate($_post["email"], $_post["password"]);
                if ($clientID) {
                    // login success
                    $_SESSION["vclient"] = new client();
                    $_SESSION["vclient"]->load($clientID);
                    $_SESSION["vclient"]->login();

                    // zorunlu alanlar?
                    if (getSetting('portal_force_required_fields') == '1') {
                        $extras = $db->query(
                            "SELECT attrID, value FROM client_extras WHERE clientID = " . $_SESSION["vclient"]->clientID,
                            SQL_KEY,
                            'attrID'
                        );
                        $ret = CustomField::validate($extras, $_SESSION["vclient"]->type, true);
                        if ($ret['st'] == false) {
                            $_SESSION["force_required_fields"] = true;
                            foreach ($ret['errors'] as $error) {
                                core::raise($error, 'e');
                            }
                            redirect('?p=user&s=details');
                        }
                    }
                    $return = $_SESSION['return_after_login'] != '' ? $_SESSION['return_after_login'] : '?p=user';
                    $_SESSION['return_after_login'] = '';
                    redirect($return);
                } else {
                    core::raise('InvalidUserPass', 'e');
                }
            }
        }
        if ($_post) {
            redirect("?p=user&s=login");
        }
        break;
    case 'logout':
        unset($_SESSION['vclient']);
        unset($_SESSION["force_required_fields"]);
        redirect("?p=user&s=login");
        break;
    case 'support':
        secure();
        require_once("support.php");
        break;
    case 'details':
        secure();
        require_once("details.php");
        break;
    case 'orders':
        secure();
        require_once("orders.php");
        break;
    case 'packs':
        secure();
        require_once("packs.php");
        break;
    case 'domains':
        secure();
        require_once("domains.php");
        break;
    case 'contacts':
        secure();
        require_once("contacts.php");
        break;
    case 'finance':
        secure();
        require_once("finance.php");
        break;
    case 'renew':
        secure();
        require_once("renew.php");
        break;
    default:
        secure();
        require_once("home.php");
        break;
}
 
 
