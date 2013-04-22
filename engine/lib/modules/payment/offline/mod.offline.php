<?php

class mod_offline extends PaymentModule
{


    function getHtml($parms)
    {
        global $config;
        $html = $this->get('instructions');

        $html = str_replace('{$paymentID}', $parms['paymentID'], $html);
        $html = str_replace('{$amount}', $parms['amount'], $html);
        $html = str_replace('{$currency}', $config['CURTABLE'][$parms['paycurID']]['symbol'], $html);
        return $html;
    }

} // end of class
