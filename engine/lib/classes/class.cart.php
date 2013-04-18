<?php

class Cart
{

    public $cartID;
    public $services = array();
    public $domains = array();
    public $addons = array();

    function Cart()
    {

        //$this->cartID = md5(uniqid(rand(), true));
    }

    /** @return Cart */
    static function getCart()
    {
        if (! isset($_SESSION["cart"]) || get_class($_SESSION["cart"]) != "Cart") {
            $_SESSION["cart"] = new Cart();
            $_SESSION["cart"]->cartID = md5(uniqid(rand(), true)); // md5(microtime() .  getenv(REMOTE_ADDR));
        }
        return $_SESSION["cart"];
    }

    function addDomain($domain, $params)
    {
        $this->domains["$domain"] = $params;
    }

    function removeDomain($domain)
    {
        unset($this->domains[$domain]);
    }

    function removeAddon($key)
    {
        unset($this->addons[$key]);
    }

    function addService($serviceID)
    {
        $param["serviceID"] = $serviceID;
        $key = uniqid();
        $this->services[$key] = $param;
        return $key;
    }

    function addAddon($serviceID, $orderID = 0, $parentKey = '')
    {
        if ($orderID) {
            $Parent = new Order($orderID);
            if ($Parent->get('status') != 'active') {
                core::raise('Eklenti sipariş edebilmeniz için siparişinizin aktif olması gerekmektedir.', 'e');
                redirect('?p=user&s=orders&a=details&tab=addons&oID=' . $orderID);
            } elseif ($Parent->dateEnd < time()) {
                core::raise('Bu siparişin süresi geçtiği için eklenti sipariş edemezsiniz.', 'e');
                redirect('?p=user&s=orders&a=details&tab=addons&oID=' . $orderID);
            }
            $param["parentID"] = $orderID;
        }
        if ($parentKey) {
            $param['parentKey'] = $parentKey;
        }
        $param["serviceID"] = $serviceID;
        $key = uniqid();
        $this->addons[$key] = $param;
        return $key;
    }

    function getService($key)
    {
        return $this->services[$key];
    }

    function updateService($key, $param)
    {
        global $db;
        $item = $this->getService($key);
        $param["serviceID"] = $item["serviceID"];
        $this->services[$key] = $param;
    }

    function removeService($key)
    {
        unset($this->services[$key]);
        foreach ($this->addons as $addonKey => $item) {
            if ($item['parentKey'] == $key) {
                $this->removeAddon($addonKey);
            }
        }
    }

    function addCoupon(Coupon $CPN)
    {
        //if (array_key_exists($CPN->code,(array)$this->coupons)) return false;
        $this->coupon = $CPN->code;
        $this->updateCouponDiscounts();
    }

    function removeCoupon()
    {
        unset($this->coupon);
        $this->updateCouponDiscounts();
    }

    function updateCouponDiscounts()
    {
        $this->discounts = array();
        if (! $this->coupon) {
            return false;
        }
        $CPN = new Coupon();
        $CPN->loadByCode($this->coupon);
        if (! $CPN) {
            return false;
        }
        foreach ($CPN->services as $serviceID) {
            $this->discounts[$serviceID] = $CPN->amount;
        }
    }


    function updateCart()
    {
        global $config;
        $domExt = getDomainExtensions();
        $this->totals["all"] = 0;
        $this->item_count = 0;


        foreach ($this->domains as $dom => $data) {
            $ext = domain::getExtensionFromDomain($dom);
            $data["paycurID"] = $domExt[$ext]["paycurID"];
            $price = $domExt[$ext]["priceRegister"] + ($domExt[$ext]["priceRenew"] * ($data["period"] - 1));
            if ($data["paycurID"] == MAIN_CUR_ID) {
                $data['payPrice'] = $price;
            } else {
                $data['payPrice'] = $price * $config['CURTABLE'][$data["paycurID"]]["ratio"];
            }
            /* apply discount */
            $discount = $this->discounts[$domExt[$ext]['serviceID']];
            if ($discount > 0 && $discount < 100) {
                $data['payPrice'] = $data['payPrice'] * (1 - $discount / 100);
            }

            $data['payPrice'] = number_format($data['payPrice'], 2, ".", "");
            $data['serviceID'] = $domExt[$ext]['serviceID'];
            $this->domains[$dom] = $data;
            $this->totals["all"] += $data['payPrice'];
            $this->item_count ++;
        }

        foreach ($this->services as $k => $data) {
            $Service = new service($data["serviceID"]);
            $Service->getPriceOptions();
            $data['service_name'] = $Service->service_name;
            $data['payPrice'] = $Service->priceOptions[$data["period"]];
            if ($Service->setup["price"] > 0) {
                if ($data['payPrice'] == 0) {
                    $data['oneTime'] = true;
                }
                $data['payPrice'] += ($Service->setup - $Service->setup_discount);
            }
            if ($Service->paycurID != MAIN_CUR_ID) {
                $data['payPrice'] = $data['payPrice'] * $config['CURTABLE'][$Service->paycurID]["ratio"];
            }
            /* apply discount */
            $discount = $this->discounts[$data['serviceID']];
            if ($discount > 0 && $discount < 100) {
                $data['payPrice'] = $data['payPrice'] * (1 - $discount / 100);
            }
            $data['payPrice'] = number_format($data['payPrice'], 2, ".", "");
            $this->services[$k] = $data;
            $this->totals["all"] += $data['payPrice'];
            $this->item_count ++;

        }

        foreach ($this->addons as $k => $data) {
            $Service = new service($data["serviceID"]);
            $Service->getPriceOptions();
            $data['service_name'] = $Service->service_name;

            if ($Service->priceOptions) {
                if (isset($data['parentID'])) {
                    $Parent = new Order($data['parentID']);
                    if ($Service->priceOptions[$Parent->period]) {
                        $data["fullPrice"] = $Service->priceOptions[$Parent->period];
                        $days = $Parent->period * 30;

                    } else {
                        foreach ($Service->priceOptions as $k2 => $v2) {
                            $data['fullPrice'] = $v2;
                            $days = $k2 * 30;
                            break;
                        }
                    }
                    $data['diff'] = floor(($Parent->dateEnd - time()) / (60 * 60 * 24));
                    $data['payPrice'] = ($data["fullPrice"] / $days) * $data['diff'];
                    $data["prorated"] = $data['payPrice'];
                } else {
                    $parentService = $this->getService($data['parentKey']);
                    $data['payPrice'] = $Service->priceOptions[$parentService['period']];
                    if (! $data['payPrice']) {
                        $data['payPrice'] = $Service->priceOptions[1];
                        foreach ($Service->priceOptions as $k3 => $v3) {
                            break;
                        }
                        $data['payPrice'] = ($v3 / $k3) * $parentService['period'];
                    }
                    /*                debugd($Service->priceOptions);
                                    debugd($data,1);*/
                }

                if ($Service->setup > 0) {
                    $data['payPrice'] += $Service->setup;
                }
            } else {
                $data['payPrice'] = $Service->setup;
            }

            if ($Service->paycurID != MAIN_CUR_ID) {
                $data['payPrice'] = $data['payPrice'] * $config['CURTABLE'][$Service->paycurID]["ratio"];
            }
            /* apply discount */
            $discount = $this->discounts[$data['serviceID']];
            if ($discount > 0 && $discount < 100) {
                $data['payPrice'] = $data['payPrice'] * (1 - $discount / 100);
            }

            $data['payPrice'] = number_format($data['payPrice'], 2, ".", "");

            if ($data['payPrice'] > 0) {
                $this->totals["all"] += $data['payPrice'];
                $this->addons[$k] = $data;
                $this->item_count ++;
            } else {
                unset($this->addons[$k]);
            }

        }

        $this->totals["all"] = number_format($this->totals["all"], 2, ".", "");

        //debug($this);
    }

    function destroy()
    {
        unset($_SESSION['cart']);
    }

    static function display()
    {

        $str = "<textarea style='width:90%; height:500px;'>";
        $str .= print_r($_SESSION["cart"], 1);
        $str .= "</textarea>";

        return $str;
    }

} // end of class cart
