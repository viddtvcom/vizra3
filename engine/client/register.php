<?php

$Client = new Client();

if ($_post["action"] == "validate") {

    if ($_SESSION['form_token'][4] != $_post['token']) {
        core::raise('InvalidOperation', 'e', '?p=user&s=login');
    }

    /*                validation begins              */
    $_country = getCountry($_post['country']);

    if (! validate_email($_post['email'])) {
        core::raise('InvalidEmail', 'e');
        $errors['email'] = true;
    } elseif (Client::emailExists($_post['email'])) {
        core::raise('EmailAlreadyExists', 'e');
        $errors['email'] = true;
    }

    if (count(explode(' ', $_post['name'])) < 2
            || (VZR_USER_FIELDS_VALIDATE != 'false'
                    && ! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/', $_post['name']))
    ) {
        $errors['name'] = true;
    }

    if ($_post['type'] == 'corporate') {
        if (! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/', $_post['company']) && VZR_USER_FIELDS_VALIDATE != 'false'
                || $_post['company'] == ''
        ) {
            $errors['company'] = true;
        }
    }

    if (VZR_USER_FIELDS_VALIDATE != 'false' && ! preg_match(
        '/^[\/a-zA-ZçÇşŞöÖğĞüÜıİ.,-:0-9 ]{3,80}$/',
        $_post['address']
    )
            || $_post['address'] == ''
    ) {
        $errors['address'] = true;
    }

    if (VZR_USER_FIELDS_VALIDATE != 'false' && ! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ]{3,50}$/', $_post['city'])
            || $_post['city'] == ''
    ) {
        $errors['city'] = true;
    }

    if (VZR_USER_FIELDS_VALIDATE != 'false' && ! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ]{3,50}$/', $_post['state'])
            || $_post['state'] == ''
    ) {
        $errors['state'] = true;
    }

    if ($_post['zip'] == '') {
        $errors['zip'] = true;
    }

    /*
    *   Telefon dogrulama
    */
    if (isset($_country['calling_code_regex'])) {
        if (! @preg_match('/' . $_country['calling_code_regex'] . '$/', $_post['phone'])) {
            $errors['phone'] = true;
        }
        if (! @preg_match('/' . $_country['calling_code_regex'] . '$/', $_post['cell'])) {
            $errors['cell'] = true;
        }
    }


    if (getSetting('compinfo_tos_url') != '' && $_post['tos_url'] != '1') {
        core::raise('YouMustReadAndAcceptTos', 'e');
        $errors['tos_url'] = true;
    }

    /*
    *   Ekstra Alanlar
    */

    $validate_extras = CustomField::validate($_post['extras'], $_post['type']);

    /*                validation ends               */

    if ($validate_extras['st'] == true && $errors == false) {
        // spare the extras before filtering
        $data['extras'] = $_post['extras'];
        $client_type = $_post['type'];

        // unset strict members
        $Client->filter_fields($_post);

        $_post['autoSuspend'] = '1';
        $_post['type'] = $client_type;
        $Client->create($_post['email']);
        $Client->replace($_post)->replaceExtras($data)->update()->notify();
        $Client->password = core::generateCode(8, 'soft');

        $EMT = new Email_template(9);
        $EMT->replaces['Client_password'] = $Client->password;
        $EMT->clientID = $Client->clientID;
        $ret = $EMT->send();

        $Client->set('password', core::encrypt($Client->password));
        $Client->set('ipReg', getenv(REMOTE_ADDR));

        core::raise('AccountCreatedDetailsSent', 'm');
        redirect("?p=user&s=login&email");
    } else {
        foreach ($validate_extras['errors'] as $error) {
            core::raise($error, 'e');
        }
        $Client->replace($_post);

        $errors = @array_merge($errors, $validate_extras['errors']);
        $core->assign('lerrors', $errors);
        $core->assign('post_extras', $_post['extras']);
    }

} elseif ($_post["action"] == "check_email") {


    if ($_SESSION['form_token'][3] != $_post['token']) {
        core::raise('InvalidOperation', 'e', '?p=user&s=login');
    } elseif (! validate_email($_post['email'])) {
        core::raise('InvalidEmail', 'e');
        redirect("?p=user&s=login");
    } elseif (Client::emailExists($_post['email'])) {
        core::raise('EmailAlreadyExists', 'e');
        redirect("?p=user&s=login");
    } else {
        $Client->email = strtolower($_post['email']);
    }
} else {
    redirect("?p=user&s=login");
}

//$Client->loadExtras(true);
$Client->extras = CustomField::getAttrs(true);

$core->assign('tos_url', getSetting('compinfo_tos_url'));

$core->assign('rClient', $Client);
$core->assign('countries', getCountries());
$tplContent = "user/register.tpl";