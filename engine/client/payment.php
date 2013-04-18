<?php
require_once("func.user.php");

$modules = Module::getActiveModules("payment");
$core->assign("modules", $modules);

$core->assign('mtitles', Module::getModuleTitles('payment'));

if ($_post['action'] == 'gateway') {
    if (! isset($_post["moduleID"])
            || ! isset($_SESSION['payparms'])
            || (! (float)$_post['amount'] && ! (float)$_SESSION['payparms']['amount'])
    ) {
        redirect('?p=payment&a=' . $_get['a']);
    }

    /*
    *   Bakiyeden ödeme
    */
    if ($_post['moduleID'] == 'balance_full') {
        $balance = $_SESSION['vclient']->getBalance();
        if ($balance >= $_SESSION['payparms']['amount']) {
            if ($_SESSION['payparms']['action'] == 'renewOrder') { // yenileme
                foreach ($_SESSION['payparms']['orders'] as $data) {
                    //$Order = new Order($data['orderID']);
                    $Order = getClientOrder($data['orderID']);
                    if (! $Order) {
                        continue;
                    }
                    $Order->renew(false, 0, $data['multiplier']);
                }
            } elseif ($_SESSION['payparms']['action'] == 'checkout') { // checkout
                weborder::process(true, 0);
            }
            redirect('?p=user&s=orders');
        } else {
            redirect('?p=payment&a=' . $_get['a']);
        }
    }

    $MOD = Module::getInstance($_post["moduleID"]);
    $modConfig = $MOD->getModuleConfig();
    $method = $modConfig['sys']['method']->value;

    $_SESSION['payparms']['moduleID'] = $_post['moduleID'];
    $_SESSION['payparms']['clientID'] = getClientID();
    $_SESSION['payparms']['currency_code'] = $config['CURTABLE'][$_SESSION['payparms']['paycurID']]['code'];

    if ($_SESSION['payparms']['action'] == 'addFunds') {
        $_SESSION['payparms']['amount'] = $_post['amount'];
        $_SESSION['payparms']['paycurID'] = $_post['paycurID'];
    }

    /*
    *   Kur cevirme islemi
    */
    $curID = $MOD->get('convert');
    if ($_SESSION['payparms']['paycurID'] != $curID) {
        $_SESSION['payparms']['amount'] = convertCurrency(
            $curID,
            $_SESSION['payparms']['paycurID'],
            $_SESSION['payparms']['amount']
        );
        $_SESSION['payparms']['paycurID'] = $curID;
    }

    /*
    *   Komisyon orani
    */
    $_SESSION['payparms']['commission_rate'] = $MOD->get('commission_rate');
    if ($_SESSION['payparms']['commission_rate'] > 0) {
        $_SESSION['payparms']['amount'] = $_SESSION['payparms']['amount'] * (1 + $_SESSION['payparms']['commission_rate'] / 100);
    }

    $_SESSION['payparms']['amount'] = number_format($_SESSION['payparms']['amount'], 2, '.', '');

    /*
    *   Offline Payment
    */
    if ($method == "html") {
        $_SESSION['payparms']['paymentID'] = Payment::addPayment(
            getClientID(),
            $_post['moduleID'],
            'pending-payment',
            0,
            $_SESSION['payparms']['amount'],
            $_SESSION['payparms']['paycurID']
        );
        /*
        *   Order Renewal
        */
        if ($_SESSION['payparms']['action'] == 'renewOrder') {
            foreach ($_SESSION['payparms']['orders'] as $data) {
                //$Order = new Order($data['orderID']);
                $Order = getClientOrder($data['orderID']);
                if (! $Order) {
                    continue;
                }
                $Order->renew(true, $_SESSION['payparms']['paymentID'], $data['multiplier']);
            }
        } elseif ($_SESSION['payparms']['action'] == 'checkout') {
            weborder::process(false, $_SESSION['payparms']['paymentID']);
        }

        $core->assign("html", $MOD->getHtml($_SESSION['payparms']));
        unset($_SESSION['payparms']);
        $tplContent = "payment_html.tpl";
    } elseif ($method == "cc") {
        ccform($MOD->get('use3d'));
        $tplContent = "payment_cc.tpl";
    }

} elseif ($_post['action'] == 'doPayment') {
    if (! isset($_SESSION['payparms']['moduleID'])) {
        redirect('?p=payment&a=' . $_get['a']);
    }
    $_SESSION['payparms']['cardType'] = $_post['cardType'];
    $_SESSION['payparms']['cardNumber'] = $_post['pan'];
    $_SESSION['payparms']['cardExpMonth'] = $_post['cardExpMonth'];
    $_SESSION['payparms']['cardExpYear'] = $_post['cardExpYear'];
    $_SESSION['payparms']['cardCV2'] = $_post['cv2'];


    $MOD = Module::getInstance($_SESSION['payparms']['moduleID']);
    $ret = $MOD->process($_SESSION['payparms']);
    /*
    *   Transaction sonucu basarili
    */
    if ($ret['st'] == 1) {
        $_SESSION['payparms']['paymentID'] = Payment::addPayment(
            getClientID(),
            $MOD->moduleID,
            'paid',
            time(),
            $_SESSION['payparms']['amount'],
            $_SESSION['payparms']['paycurID']
        );

        if ($_SESSION['payparms']['action'] == 'renewOrder') {
            foreach ($_SESSION['payparms']['orders'] as $data) {
                //$Order = new Order($data['orderID']);
                $Order = getClientOrder($data['orderID']);
                if (! $Order) {
                    continue;
                }
                $Order->renew(false, $_SESSION['payparms']['paymentID'], $data['multiplier']);
            }
        } elseif ($_SESSION['payparms']['action'] == 'checkout') {
            weborder::process(true, $_SESSION['payparms']['paymentID']);
        }
        $core->assign('parms', $_SESSION['payparms']);
        $tplContent = "payment_ok.tpl";
        unset($_SESSION['payparms']);
    } else {
        core::raise($ret["msg"], 'e', '/?p=payment&a=checkout');
        /*        ccform();
                $tplContent = "payment_cc.tpl";*/
    }
} else {
    unset($_SESSION['payparms']);
    switch ($_get["a"]) {
        case 'renewOrder':
            if ($_get['oID']) {
                $_post['selected'][] = $_get['oID'];
            }
            if (empty($_post['selected'])) {
                core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=user&s=orders');
            }
            foreach ((array)$_post['selected'] as $orderID) {
                //$Order = new Order($orderID);
                $Order = getClientOrder($orderID);
                if (! $Order) {
                    core::raise($orderID . ': böyle bir sipariş kaydı bulunamadı.', 'e');
                    $error = true;

                } elseif ($Order->status != 'active' && $Order->status != 'suspended') {
                    core::raise($orderID . ': Sadece aktif ve askıda olan siparişler yenilenebilir', 'e');
                    $error = true;

                } elseif ($Order->payType != 'recurring') {
                    core::raise($orderID . ': Bu sipariş yenilenemez', 'e');
                    $error = true;

                } else {
                    $_multiplier = (int)$_post['multipliers'][$orderID];
                    $_multiplier = ($_multiplier < 1) ? 1 : (($_multiplier > 10) ? 10 : $_multiplier);
                    $renew_price = $Order->getRenewPrice() * $_multiplier;
                    $_SESSION['payparms']['orders'][] = array('orderID' => $orderID, 'multiplier' => $_multiplier);
                    $_SESSION['payparms']['amount'] += convertCurrency(MAIN_CUR_ID, $Order->paycurID, $renew_price);
                    $_SESSION['payparms']['paycurID'] = MAIN_CUR_ID;
                }
            }

            if (! $error && empty($_SESSION['payparms']['orders'])) {
                core::raise('En az bir sipariş seçmelisiniz', 'e');
                $error = true;

            } elseif ($_SESSION['payparms']['amount'] <= 0) {
                core::raise('Geçersiz sipariş tutarı', 'e');
                $error = true;

            }
            if ($error) {
                unset($_SESSION['payparms']);
                $return = ($_post['return'] == '') ? '?p=user' : $_post['return'];
                redirect($return);
            }
            break;

        case 'checkout':
            // ücretsiz sipariş?
            if ($_SESSION['cart']->item_count > 0 && (float)$_SESSION["cart"]->totals['all'] == 0) {
                weborder::process(true, 0);
                redirect('?p=user&s=orders');
            }
            $_SESSION['payparms']['amount'] = $_SESSION["cart"]->totals["all"];
            $_SESSION['payparms']['paycurID'] = MAIN_CUR_ID;
            if ((float)($_SESSION['payparms']['amount']) == 0) {
                redirect('?p=cart');
            }
            break;
        case 'addFunds':
            break;
        default:
            die();
            break;
    }

    /*
    *   Bakiyesi musait ise opsiyon olarak bunu da getir (islem addFunds degilse)
    */
    if ($_SESSION['payparms']['action'] != 'addFunds') {
        $balance = $_SESSION['vclient']->getBalance();
        if ($balance >= $_SESSION['payparms']['amount']) {
            $_SESSION['payparms']['paymode'] = 'balance_full';
        } else {
            $_SESSION['payparms']['paymode'] = 'gateway';
        }
        $core->assign('paymode', $_SESSION['payparms']['paymode']);
        $core->assign('balance', $balance);
    }


    $core->assign('total', $_SESSION['payparms']['amount']);
    $core->assign('paycurID', $_SESSION['payparms']['paycurID']);


    $_SESSION['payparms']['action'] = $_get["a"];
    $tplContent = 'payment.tpl';
}


function ccform($use3d = false)
{
    global $core;
    if ($use3d == '1') {
        $core->assign('use3d', true);
        //$core->assign('tdsparams',$Module->tdsparams($_SESSION['payparms']['amount']));
    }

    for ($i = 1; $i < 13; $i ++) {
        $months[] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }
    $core->assign('months', $months);
    for ($i = date('y'); $i < date('y') + 16; $i ++) {
        $years[] = $i;
    }
    $core->assign('years', $years);
}

  
