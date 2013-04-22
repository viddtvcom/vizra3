<?php
class weborder
{

    static function process($paid = false, $paymentID = 0)
    {
        global $db;
        $action = array(true => 'pending', false => 'pending-payment');
        $CPN = new Coupon();
        $CPN->loadByCode($_SESSION['cart']->coupon);

        // ****** Services
        foreach ($_SESSION['cart']->services as $key => $item) {
            /* kupon bu servisi kapsiyorsa uygula*/
            $couponID = (in_array($item['serviceID'], $CPN->services)) ? $CPN->couponID : 0;

            $Order = new order();
            $Order->create($item['serviceID'], getClientID(), $item["period"], null, $couponID);

            $Order->updateAttrs($item["attributes"]);
            $Order->start($paymentID, $paid);
            $Order->set('description', $item['description']);

            $_SESSION['cart']->services[$key]['orderID'] = $Order->orderID;
        }
        // ****** Addons
        foreach ($_SESSION['cart']->addons as $item) {
            /* kupon bu servisi kapsiyorsa uygula*/
            $couponID = (in_array($item['serviceID'], $CPN->services)) ? $CPN->couponID : 0;

            $Order = new order();
            $parentID = ($item['parentID']) ? $item['parentID'] : $_SESSION['cart']->services[$item['parentKey']]['orderID'];
            $Parent = new Order($parentID);
            $Order->parentID = $parentID;
            $Order->create($item['serviceID'], getClientID(), $Parent->period, null, $couponID);
            $Order->updateAttrs($item["attributes"]);
            $Order->start($paymentID, $paid);

            $price = ($item['parentID']) ? $item['prorated'] : $Order->price;
            if ($price > 0 && $Order->Service->getPriceOptions()) {
                $OB = $Order->createBill('recurring')->dateStart(time())
                        ->dateEnd($Parent->dateEnd)
                        ->dateDue(time())
                        ->amount($price);
                $OB->paymentID = $paymentID;
                $OB->description = $Order->title;
                if ($paid) {
                    $OB->pay();
                }
                $OB->update();
            }

            $Order->dateStart = time();
            $Order->dateEnd = $Parent->dateEnd;
            $Order->update();
        }
        // ****** Domains
        foreach ($_SESSION['cart']->domains as $domain => $item) {
            $Domain = new Domain();
            $Domain->create($domain);
            $Domain->ns1 = $item['ns1'];
            $Domain->ns2 = $item['ns2'];

            /* kupon bu domaini kapsiyorsa uygula*/
            $couponID = (in_array($Domain->extensionData['serviceID'], $CPN->services)) ? $CPN->couponID : 0;

            $Order = new order();
            $Order->create(
                $Domain->extensionData['serviceID'],
                getClientID(),
                    $item["period"] * 12,
                $Domain,
                $couponID
            );

            $Domain->orderID = $Order->orderID;
            $Domain->update();

            $Order->start($paymentID, $paid);

        }
        if (is_object($Order)) {
            $Order->notify();
        }
        $_SESSION['cart']->destroy();

    }

} // end of class weborder
