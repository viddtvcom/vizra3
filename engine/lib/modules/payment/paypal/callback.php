<?php

if (! $_POST) {
    exit();
}

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}


$paypal_url = ($this->get('test_mode') == '1') ? 'www.sandbox.paypal.com' : 'www.paypal.com';

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen($paypal_url, 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];


if (! $fp) {
    debuglog("HTTP ERROR", 'mod_paypal');
} else {
    fputs($fp, $header . $req);
    while (! feof($fp)) {
        $res = fgets($fp, 1024);

        if (strcmp($res, "VERIFIED") == 0
                && ($this->get('paypal_email') == $receiver_email || $this->get('paypal_email') == $_POST['business'])
                && $payment_status == 'Completed'
        ) {
            // check that txn_id has not been previously processed
            $Payment = new Payment($item_number);
            if ($Payment->amount == $payment_amount) {
                //debuglog('Approving', 'mod_paypal');
                $Payment->approve();
                $Payment->set('description', $txn_id);
            } else {
                debuglog($_POST, 'mod_paypal');
                debuglog($Payment, 'mod_paypal');
            }

        } else if (strcmp($res, "INVALID") == 0) {
            debuglog($res, 'mod_paypal');
        }
    }
    fclose($fp);
}
