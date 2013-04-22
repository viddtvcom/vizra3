<?php

class mod_paypal extends PaymentModule
{

    function getHtml($parms)
    {
        global $config;

        // musterinin odeme islemini yaptiktan sonra donecegi adres
        $parms["return_url"] = $config['HTTP_HOST'] . "?p=user&s=finance";

        // musterinin odeme islemini iptal ettikten sonra donecegi adres
        $parms["cancel_url"] = $config['HTTP_HOST'] . "?p=user&s=finance";

        // PAYPAL'in odeme sonucunu callback yapacagi adres
        $parms["notify_url"] = $config['HTTP_HOST'] . 'callback.php?mod=paypal';

        // odeme kur kodu
        $parms["currency_code"] = $config['CURTABLE'][$parms['paycurID']]['code'];

        // sandbox / production secimi
        $paypal_url = ($this->get('test_mode') == '1') ? 'www.sandbox.paypal.com' : 'www.paypal.com';

        $html = '<form action="https://' . $paypal_url . '/cgi-bin/webscr" method="post" style="margin:0px;padding:0px" id="paypalform">
              <input type="hidden" name="cmd" value="_xclick">
              <input type="hidden" name="business" value="' . $this->get('paypal_email') . '">
              <input type="hidden" name="item_name" value="payment for client #' . $parms["clientID"] . '">
              <input type="hidden" name="item_number" value="' . $parms["paymentID"] . '">
              <input type="hidden" name="amount" value="' . $parms["amount"] . '">
              <input type="hidden" name="no_shipping" value="1">
              <input type="hidden" name="return" value="' . $parms["return_url"] . '">
              <input type="hidden" name="notify_url" value="' . $parms["notify_url"] . '">
              <input type="hidden" name="cancel_return" value="' . $parms["cancel_url"] . '">
              <input type="hidden" name="no_note" value="1">
              <input type="hidden" name="currency_code" value="' . $parms["currency_code"] . '">
              <input type="hidden" name="bn" value="PP-BuyNowBF">
              <input type="submit" value="Paypal Payment - ' . $parms["amount"] . ' ' . $parms["currency_code"] . '">
              <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
          </form>';

        $html .= '<script language="JavaScript">
                $(document).ready(function() {
                    $("#paypalform").submit();          
                });  
            </script>';

        return $html;

    }

    function callback()
    {
        if ($this->get('auto_approve') != '1') {
            return false;
        }
        include(dirname(__FILE__) . '/callback.php');
    }


} // end of class
