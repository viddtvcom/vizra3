<?php
$core->assign('countries', getCountries());

if ($_post) {
    if (! validate_email($_post['email'])) {
        core::raise('Geçersiz email adresi', 'e');
        $errors['email'] = true;
    } elseif (Contact::emailExists($_post['email'], CLIENTID)) {
        core::raise('Bu email adresi sistemimizde kayıtlı', 'e');
        $errors['email'] = true;
    }

    $_country = getCountry($_post['country']);

    if (count(explode(' ', $_post['name'])) < 2
            || (VZR_USER_FIELDS_VALIDATE != 'false'
                    && ! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/', $_post['name']))
    ) {
        $errors['name'] = true;
    }

    if (VZR_USER_FIELDS_VALIDATE != 'false' && ! preg_match(
        '/^[\/a-zA-ZçÇşŞöÖğĞüÜıİ.,-:0-9 ]{6,80}$/',
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

    if ($errors) {
        $Contact = new Contact();
        $Contact->filter_fields($_post);
        $Contact->replace($_post);
        $core->assign('lerrors', $errors);
    } else {

        if ($_get['a'] == 'ec') {
            $Contact = getClientContact($_get['cID']);
            /* check */
            if (! $Contact) {
                core::raise('Kontak kaydı bulunamadı', 'e', 'rt');
            }

            $Contact->filter_fields($_post);
            $Contact->replace($_post);
            $Contact->update();
            $ret = $Contact->update2();
            if ($ret['st']) {
                core::raise('Alan adı kontak bilgisi güncellendi', 'm');
            } else {
                core::raise('Alan adı kontak bilgisi güncellenirken hata oluştu: ' . $ret['msg'], 'e');
            }

        } elseif ($_get['a'] == 'ndc') {
            $Contact = new Contact();
            $Contact->create(true);
            $Contact->replace($_post);
            $Contact->clientID = CLIENTID;
            $Contact->update();

            // once domaini kontrol et:
            $Dom = getClientDomain($_get['dID']);
            if (! $Dom) {
                core::raise('Alan adı kaydı bulunamadı', 'e', 'rt');
            }

            $res = $Dom->addContact($Contact);
            if ($res['st']) {
                $data[$_get['t']] = $res['id'];
                $res = $Dom->setDomainContacts($data);
                if ($res['st']) {
                    core::raise(
                        'Alan adı kontak bilgisi eklendi',
                        'm',
                            '?p=user&s=orders&a=details&tab=domain&oID=' . $Dom->orderID
                    );
                } else {
                    core::raise('Alan adı kontak bilgileri güncellenemedi:' . $res['msg'], 'e');
                }
            } else {
                core::raise('Alan adı kontak kaydı eklenemedi:' . $res['msg'], 'e');
                $Contact->destroy();
            }
        }
    }
}

if ($_get['a'] == 'ec') {
    $Contact = getClientContact($_get['cID']);
    if (! $Contact) {
        core::raise('Kontak kaydı bulunamadı', 'e', 'rt');
    }
} elseif ($_get['a'] == 'ndc') {
    $Dom = getClientDomain($_get['dID']);
    if (! $Dom) {
        core::raise('Alan adı kaydı bulunamadı', 'e', 'rt');
    }
}
$core->assign('Contact', $Contact);
$tplContent = 'user/contacts_add.tpl'; 


 
  