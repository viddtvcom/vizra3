<?php




switch ($_get["a"]) {

    case 'password':
        if ($_post['action'] == 'update') {
            if (core::encrypt($_post['oldpassword']) != $_SESSION['vclient']->get('password')) {
                core::raise('InvalidOldPassword', 'e');
                redirect('?p=user&s=details&a=password');
            } elseif (strlen($_post['newpassword']) < 6 || strlen($_post['newpassword']) > 16) {
                core::raise('NewPasswordMustBeAtLeast6Maximum16Characters', 'e');
                redirect('?p=user&s=details&a=password');
            } elseif ($_post['newpassword'] != $_post['newpassword2']) {
                core::raise('PasswordsDontMatch', 'e');
                redirect('?p=user&s=details&a=password');
            } else {
                $_SESSION['vclient']->set('password', core::encrypt($_post['newpassword']));
                core::raise('PasswordUpdated', 'm', '?p=user&s=details');
            }
        }

        $tplContent = 'user/details.password.tpl';
        break;

    default:
        if ($_post['action'] == 'update') {

            /*                validation begins              */
            $_country = getCountry($_post['country']);
            //$_post['phone'] = str_replace(' ', '', $_post['phone']);
            //$_post['cell'] = str_replace(' ', '', $_post['cell']);


            if ($_post['email'] != $_SESSION["vclient"]->email) {
                if (! validate_email($_post['email'])) {
                    core::raise('InvalidEmail', 'e');
                    $errors['email'] = true;
                } elseif (Client::emailExists($_post['email'])) {
                    core::raise('EmailAlreadyExists', 'e');
                    $errors['email'] = true;
                }
            }


            if (count(explode(' ', $_post['name'])) < 2
                    || (VZR_USER_FIELDS_VALIDATE != 'false'
                            && ! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/', $_post['name']))
            ) {
                $errors['name'] = true;
            }

            if ($_post['type'] == 'corporate') {
                if (! preg_match(
                    '/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/',
                    $_post['company']
                ) && VZR_USER_FIELDS_VALIDATE != 'false'
                        || $_post['company'] == ''
                ) {
                    $errors['company'] = true;
                }
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


            /*
            *   Ekstra Alanlar
            */
            $validate_extras = CustomField::validate($_post['extras'], $_SESSION["vclient"]->type);
            if ($validate_extras['st'] == true && $errors == false) {
                $data['extras'] = $_post['extras'];

                $_SESSION["vclient"]->filter_fields($_post);
                $_SESSION["vclient"]->replace($_post)->replaceExtras($data)->update();

                unset($_SESSION["force_required_fields"]);

                core::raise('YourDetailsHasBeenUpdated', 'm');
                redirect("?p=user&s=details");
            } else {
                foreach ($validate_extras['errors'] as $error) {
                    core::raise($error, 'e');
                }
                core::raise('AnErrorOccuredWhileUpdatingYourDetails', 'e');

                $errors = @array_merge($errors, $validate_extras['errors']);
                $core->assign('lerrors', $errors);
            }
        }

        $_SESSION["vclient"]->loadExtras(true);

        $core->assign('countries', getCountries());

        $tplContent = "user/details.main.tpl";
        break;
}

 

