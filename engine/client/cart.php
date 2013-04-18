<?php
//debug($_SESSION);
unset($_SESSION['payparms']);
require_once("func.domain.php");

$cart = cart::getCart();
$cart->updateCart();

if ($_get["a"] == "empty") {
    unset($_SESSION["cart"]);
    redirect("?");
}

switch ($_get["s"]) {
    case 'srvconf':
        if ($_get['a'] == "addon") {
            $key = $cart->addAddon($_get["sID"], $_get["oID"]);
            redirect("?p=cart");
        } elseif ($_get["a"] == "add") {
            $Service = new Service($_get["ID"]);
            if (! $Service->serviceID) {
                redirect('?p=cart');
            }
            if ($Service->status != 'active') {
                core::raise('Bu hizmet aktif değil', 'e', '?p=cart');
            }

            $Service->getPriceOptions();
            $core->assign('Service', $Service);

            // addons
            $addons = $Service->getAddons();
            foreach ($addons as $k => $ao) {
                $aob = Service::newInstance($ao['serviceID'], false)->objectFromArray($ao);
                $aob->getPriceOptions();
                $price = array_slice($aob->priceOptions, 0, 1, true);
                list ($addons[$k]['period'], $addons[$k]['price']) = each($price);
            }
            $core->assign('addons', $addons);

            // domain setting
            if ($Service->hasAttr('domain')) {
                $core->assign('dom', true);
                $core->assign('exts', getDomainExtensions());
            }

            // default price option
            $core->assign('cartItem', array('period' => $Service->getDefaultPriceOptionPeriod()));

            $tplContent = "cart_product.tpl";
        } elseif ($_get["a"] == "rm") {
            $key = $cart->removeService($_get["key"]);
            redirect("?p=cart");
        } elseif ($_get["a"] == "rmaddon") {
            $key = $cart->removeAddon($_get["key"]);
            redirect("?p=cart");
        } elseif ($_get["a"] == "update") {


            $param["attributes"] = $_post["att"];
            $valid = validateAttrs($param["attributes"]);

            if ($_post['serviceID'] > 0) {
                if (! $valid) {
                    redirect('?p=cart&s=srvconf&a=add&ID=' . $_post['serviceID']);
                }
                $key = $cart->addService($_post['serviceID']);
            } else {
                if (! $valid) {
                    redirect('?p=cart&s=srvconf&a=update-form&key=' . $_get['key']);
                }
                $key = $_get['key'];
            }

            /* servisi kontrol et */
            $Service = new Service($cart->services[$key]['serviceID']);
            if (! $Service->serviceID || $Service->status != 'active') {
                unset($cart->services[$key]);
                redirect('?p=cart');
            }

            /* gelen periyodu kontrol et */
            $price_options = $Service->getPriceOptions();

            if (is_array($price_options) && ! array_key_exists($_post['period'], $price_options)) {
                unset($cart->services[$key]);
                redirect('?p=cart');
            }

            //debuglog($price_options);
            //echo $Service->serviceID;            

            $param["service_name"] = $_post["service_name"];
            $param["description"] = $_post["description"];
            $param["period"] = $_post["period"];
            $cart->updateService($key, $param);
            foreach ((array)$_post['addons'] as $serviceID => $v) {
                $cart->addAddon($serviceID, 0, $key);
            }
            if ($_post['domreg'] == 'new') {
                $ns = Service::newInstance($_post['serviceID'])->getNameServers();
                $cart->addDomain(
                    $param["attributes"]['domain'],
                    array('period' => $_post['domperiod'], 'ns1' => $ns[0], 'ns2' => $ns[1])
                );
            }
            redirect("?p=cart");
        } else {
            $item = $cart->getService($_get["key"]);
            $Service = new Service($item["serviceID"]);
            // check
            if (! $Service->serviceID) {
                redirect('?p=cart');
            }

            $Service->getPriceOptions();
            $core->assign('Service', $Service);
            $core->assign('cartItem', $item);

            // addons
            $addons = $Service->getAddons();
            foreach ($addons as $k => $ao) {
                $aob = Service::newInstance($ao['serviceID'], false)->objectFromArray($ao);
                $aob->getPriceOptions();
                $price = array_slice($aob->priceOptions, 0, 1, true);
                list ($addons[$k]['period'], $addons[$k]['price']) = each($price);
            }
            $core->assign('addons', $addons);

            // domains
            if ($Service->hasAttr('domain')) {
                $core->assign('dom', true);
                $core->assign('exts', getDomainExtensions());
            }

            $tplContent = "cart_product.tpl";
        }
        break;

    case 'domconf':
        if ($_get["a"] == "rm") {
            $cart->removeDomain($_get["d"]);
        } else {
            if (! $_post["domains"]) {
                core::raise('PleaseSelectAValidDomain', 'e', '?p=shop&s=domain');
            } else {
                foreach ((array)$_post["domains"] as $k => $domain) {
                    $params["ns1"] = $_post["ns1"][$k];
                    $params["ns2"] = $_post["ns2"][$k];
                    $params["period"] = $_post["period"][$k];
                    $params["action"] = $_post["action"][$k];
                    $cart->addDomain($domain, $params);
                }
            }
        }
        redirect("?p=cart");
        break;

    case 'do':
        if ($_get["a"] == "update") {
            $cart->updateCart();
            redirect("?p=cart");
        } elseif ($_get["a"] == "empty") {
            $cart->destroy();
            redirect("?p=cart");
        }
        break;

    default:
        /* validate coupon code */
        if ($_post['action'] == 'validate_coupon') {
            $CPN = new Coupon();
            if (! $CPN->loadByCode($_post['coupon'])) {
                core::raise('Geçersiz kupon kodu', 'e');
            } elseif (! $CPN->active) {
                core::raise('Bu kupon kodunun geçerlilik süresi sona ermiştir.', 'e');
            } else {
                $cart->addCoupon($CPN);
                $_SESSION["cart"]->updateCart();
                core::raise('Kupon sepetinize eklendi');
            }
        } elseif ($_post['action'] == 'remove_coupon') {
            $cart->removeCoupon();
            $_SESSION["cart"]->updateCart();
            core::raise('Kupon sepetinizde çıkarıldı');

        }
        $tplContent = "cart.tpl";
        $core->assign("cart", $_SESSION["cart"]);
        //if ($_SESSION["vclient"]) $core->assign("display_cart",cart::display());
        break;
}
