<?php

class mod_tco extends PaymentModule
{

    function getHtml($parms)
    {
        global $config;
        $x_Receipt_Link_URL = $config['HTTP_HOST'] . 'callback.php?mod=tco';

        $demo_mode = ($this->get('demo_mode') == '1') ? 'Y' : 'N';
        $url = 'https://www.2checkout.com/2co/buyer/purchase';
        $url = 'https://www.2checkout.com/checkout/spurchase';

        //$url = 'http://developers.2checkout.com/return_script/';

        $Client = new Client($parms['clientID']);


        $html = '<form action="' . $url . '" method="get" style="margin:0px;padding:0px" id="tcoform">
            <input type="hidden" name="sid" value="' . $this->get('sid') . '">
            <input type="hidden" name="cart_order_id" value="' . $parms["paymentID"] . '">
            <input type="hidden" name="total" value="' . $parms["amount"] . '">
            <input type="hidden" name="id_type" value="1">
            <input type="hidden" name="demo" value="' . $demo_mode . '">
            <input type="hidden" name="x_Receipt_Link_URL" value="' . $x_Receipt_Link_URL . '">
            <input type="hidden" name="card_holder_name" value="' . $Client->name . '"/>
            <input type="hidden" name="street_address" value="' . $Client->address . '"/>
            <input type="hidden" name="city" value="' . $Client->city . '"/>
            <input type="hidden" name="state" value="' . $Client->state . '"/>
            <input type="hidden" name="zip" value="' . $Client->zip . '"/>
            <input type="hidden" name="country" value="' . $Client->country . '"/>
            <input type="hidden" name="email" value="' . $Client->email . '"/>
            <input type="hidden" name="phone" value="' . $Client->phone . '"/>
         </form>';

        $html .= '<script language="JavaScript">
                $(document).ready(function() {
                    $("#tcoform").submit();          
                });  
            </script>';

        return $html;

    }

    function callback()
    {
        if ($this->get('auto_approve') != '1') {
            return false;
        }
        global $config;

        if ($_POST['credit_card_processed'] == 'Y') {
            // Here the $_POST['order_number'] is changed to 1 because ACME is in
            // demo mode. When in demo mode, the $_POST['key'] parameter is
            // calculated with a 1 instead of the proper order number to
            // intentionally break the hash so people can not place fake orders
            // using the demo parameter.  Comment this out for real sales.
            //$_POST['order_number'] = 1;

            // this is the secret word defined in your 2Checkout account
            $string_to_hash = $this->get('key');

            // this should be YOUR vendor number
            $string_to_hash .= $this->get('sid');

            // append the order number, in this script it will always be 1
            $string_to_hash .= $_POST['order_number'];

            // append the sale total
            $string_to_hash .= $_POST['total'];

            // get a md5 hash of the string, uppercase it to match the returned key
            $hash_to_check = strtoupper(md5($string_to_hash));

            // check to match that the key received is
            // exactly the same as the key generated
            if ($_POST['key'] === $hash_to_check) {
                $valid_order = true;
                $Payment = new Payment($_POST['cart_order_id']);
                $Payment->approve();
                $Payment->set('description', $_POST['order_number']);
            } else {
                vzrlog('Invalid 2CheckOut Transaction');
                vzrlog($_POST);
            }
            redirect($config['HTTP_HOST'] . '?p=user&s=finance&tab=payments');

        }
    }


} // end of class
