<?php
class Order extends base
{

    public $orderID;
    public $parentID = 0;
    public $clientID;
    public $serviceID;
    public $serverID;
    public $couponID;
    public $status;
    public $autoSuspend;
    public $payType;
    public $price;
    public $paycurID;
    public $period;
    public $title;
    public $description;
    public $dateStart;
    public $dateEnd;
    public $dateAdded;
    public $dateUpdated;

    public $attrs;
    public $discount;
    private $isLoadedService = false;

    static public $icons = array(
        'pending-payment' => 'currency.png',
        'pending-provision' => 'info.png',
        'active' => 'ok.png',
        'suspended' => 'lock.png',
        'deleted' => 'stop.png',
        'inactive' => 'led_white.png'
    );

    function __construct($id = "")
    {
        $this->db_table = "orders";
        $this->ID_field = "orderID";

        $this->db_members = array(
            'orderID',
            'parentID',
            'clientID',
            'serviceID',
            'serverID',
            'couponID',
            'status',
            'autoSuspend',
            'payType',
            'price',
            'paycurID',
            'period',
            'title',
            'description',
            'dateStart',
            'dateEnd',
            'dateAdded',
            'dateUpdated'
        );
        $this->db_checkboxes = array('autoSuspend');
        if ($id) {
            $this->load($id);
        }
    }

    function create($serviceID, $clientID, $period = 0, Domain $Domain = null, $couponID = 0)
    {
        global $db;
        $this->orderID = generateID($this->db_table, $this->ID_field);
        $this->Service = new service($serviceID);
        $this->Service->setup -= $this->Service->setup_discount;

        if ($this->Service->groupID != 10) {
            // service order
            $this->Service->getPriceOptions();
            $price = $this->Service->priceOptions[$period];
            if ($this->parentID && ! $price) {
                // no suitable parent period
                $price = $Service->priceOptions[1];
                foreach ($this->Service->priceOptions as $k3 => $v3) {
                    break;
                }
                $price = ($v3 / $k3) * $period;
            } elseif (! $this->Service->priceOptions) {
                // no recurring payment option
                if (! (float)$this->Service->setup) {
                    // no setup fee
                    $payType = 'free';
                } else {
                    $payType = 'onetime';
                    $price = $this->Service->setup;
                }
                $period = 0;
            } else {
                $payType = 'recurring';
            }
        } else {
            // domain order
            $price = $Domain->extensionData["priceRenew"];
            $payType = 'recurring';
        }

        // baslangic tarihi belirtilmisse onu baz al, degilse simdiki zamani al
        if ($this->dateStart > 0) {
            $dateStart = $this->dateStart;
            $curDate = "'" . date('Y-m-d', $dateStart) . "'";
        } else {
            $dateStart = 'UNIX_TIMESTAMP()';
            $curDate = 'CURDATE()';
        }

        // varsa indirim uygula
        $sql = "SELECT discount_rate FROM client_groups cg
                    INNER JOIN clients c ON c.groupID = cg.groupID
                WHERE c.clientID = '" . $clientID . "'";

        $client_discount = $db->query($sql, SQL_INIT, 'discount_rate');
        if ($client_discount > 0 && $client_discount < 1) {
            $price = $price * (1 - $client_discount);
            $this->Service->setup = $this->Service->setup * (1 - $client_discount);
        }

        $sql = "INSERT INTO orders (orderID,parentID,clientID,serviceID,couponID,price,paycurID,period,payType,dateAdded,dateStart,dateEnd) 
                    VALUES (" . $this->orderID . "," . $this->parentID . ",
                            " . $clientID . ",
                            " . $serviceID . "," . (int)$couponID . ",
                            '" . $price . "',
                            " . $this->Service->paycurID . ",
                            " . $period . ",'" . $payType . "',
                            UNIX_TIMESTAMP()," . $dateStart . ",UNIX_TIMESTAMP( ADDDATE(" . $curDate . ",INTERVAL $period MONTH )))";
        $db->query($sql);
        if (! $this->orderID) {
            core::error("order_add");
        }
        $this->load();

        // Eger Free veya Onetime siparis ise Expiration tarihini ayarla
        if ($payType == 'free' || $payType == 'onetime') {
            $expires = $this->Service->getExpirationTime($this->dateStart);
            $this->set('dateEnd', $expires);
        }

        if ($this->Service->groupID != 10) {
            $this->bindService();
        } else {
            $this->Domain = $Domain;
        }

        // Cast price to float;
        $this->price = (float)$this->price;

        return $this;
    }

    function start($paymentID, $paid, $genbill = true, $prov = true, $sendmail = true)
    {
        //$bill_status = ($paid) ? 'paid' : 'unpaid';
        $order_status = ($paid) ? 'pending-provision' : 'pending-payment';
        $queue_status = ($paid) ? 'pending' : 'pending-payment';


        // not free, not addon
        if ($this->Service->priceOptions && ! $this->parentID && $genbill) {
            $OB = $this->createBill('recurring')->dateStart($this->dateStart)
                    ->dateEnd($this->dateEnd)
                    ->dateDue($this->dateStart)
                    ->amount($this->price);
            $OB->paymentID = $paymentID;
            if ($paid) {
                $OB->pay();
            }
            $OB->update();
        }
        // domain bills      
        if ($this->Service->groupID == 10 && $genbill) {
            $price = $this->Domain->extensionData["priceRegister"] + ($this->Domain->extensionData["priceRenew"] * (($this->period / 12) - 1));
            $OB = $this->createBill('recurring')->dateStart($this->dateStart)
                    ->dateEnd($this->dateEnd)
                    ->dateDue($this->dateStart)
                    ->amount($price);
            $OB->paymentID = $paymentID;
            if ($paid) {
                $OB->pay();
            }
            $OB->update();
        }
        // setup bills
        if ($this->Service->setup > 0 && $genbill) {
            $OB = $this->createBill('onetime')->dateStart($this->dateStart)
                    ->dateEnd($this->dateStart)
                    ->dateDue($this->dateStart)
                    ->amount($this->Service->setup);
            $OB->paymentID = $paymentID;
            $OB->description = 'İlk Ödeme';
            if ($paid) {
                $OB->pay();
            }
            $OB->update();
        }

        ///////////// Provisioning
        if ($this->Service->groupID != 10) {
            if ($this->Service->moduleID != '' && ! $this->parentID) {
                // has module
                if ($this->Service->provisionType == 'auto' && $prov) {
                    // auto provision
                    $this->moduleQueueCmd('create', array('status' => $queue_status), $paymentID);
                } else {
                    // manual provision
                    $params = array('orderStatus' => 'pending-provision');
                    Queue::createJob('orderstatus')->setParams($params)
                            ->setPaymentID($paymentID)
                            ->setOrderID($this->orderID)
                            ->setStatus($queue_status)
                            ->update()
                            ->start();
                }
                $this->setStatus($order_status);
            } else {
                // no module
                if ($paid) {
                    $this->setStatus('active');
                } else {
                    $params = array('orderStatus' => 'active');
                    Queue::createJob('orderstatus')->setParams($params)
                            ->setPaymentID($paymentID)
                            ->setOrderID($this->orderID)
                            ->setStatus($queue_status)
                            ->update()
                            ->start();
                }
            }
        } else {
            // domain order
            $this->setStatus($order_status);
            if ($this->Service->moduleID !== '' && $prov) {

                $params = array('domainID' => $this->Domain->domainID, 'period' => $this->period);
                Queue::createJob('domregister')->setParams($params)
                        ->setPaymentID($paymentID)
                        ->setOrderID($this->orderID)
                        ->setStatus($queue_status)
                        ->update()
                        ->start();

            }
            $this->set('period', '12');
        }

        //  Execute post-provision commands
        $action = array(true => 'pending', false => 'pending-payment');
        if ($this->Service->moduleCmd != '') {
            if ($this->parentID) {
                $Parent = new Order($this->parentID);
                $obj = & $Parent;
            } else {
                $obj = & $this;
            }
            $obj->loadService();
            if ($obj->Service->provisionType == 'auto' && $prov) {
                $obj->moduleQueueCmd($this->Service->moduleCmd, array('status' => $queue_status), $paymentID);
            }
        }

        //  Set title   
        $this->setTitle();

        // send mail
        if ($sendmail) {
            $EMT = new Email_template(14);
            $EMT->orderID = $this->orderID;
            $EMT->send();
        }

    }

    function notify()
    {
        global $config;
        $notify = getSetting('notify_neworder', true);
        if ($notify[0] == '1') {
            $body = "Yeni sipariş alındı (" . $this->title . "), detaylar için <a href='" . $config['HTTP_HOST'] . "acp/?p=311&tab=orders&orderID=" . $this->orderID . "'>buraya</a> tıklayınız";
            core::send_mail('Yeni sipariş alındı', getSetting('notify_notifymail'), $body);
        }
        if ($notify[1] == '1') {
            core::send_sms("Yeni sipariş alındı (" . $this->title . ")", getSetting('notify_notifycell'));
        }
        if ($notify[2] == '1') {
            $body = "Yeni sipariş alındı (" . $this->title . "), detaylar için: " . $config['HTTP_HOST'] . "acp/?p=311&tab=orders&orderID=" . $this->orderID;
            core::send_msn($body, getSetting('notify_notifymsn'));
        }
    }

    function load($id = "")
    {
        if (! parent::load($id)) {
            return false;
        }
        $this->curSymbol = ($this->paycurID) ? core::getCurrencyById($this->paycurID) : "";
        if ($this->couponID) {
            $CPN = new Coupon($this->couponID);
            if ($CPN->couponID && $CPN->active && in_array($this->serviceID, $CPN->services)) {
                $this->discount = $CPN->amount / 100;
                $this->price_discounted = $this->price * (1 - $this->discount);
                $this->CPN = $CPN;
            }
        }
    }

    function setStatus($st)
    {
        global $db;
        $this->loadService();
        if ($st == 'active' && ($this->status == 'pending-provision' || $this->status == 'pending-payment')) {
            if ($this->Service->templateID > 0) {
                $EMT = new Email_template($this->Service->templateID);
                $EMT->orderID = $this->orderID;
                $EMT->send();
            }
            if ($this->Service->notifyOnOrderDepID) {
                $Dep = new Department($this->Service->notifyOnOrderDepID);
                $Dep->notifyNewOrder($this);
            }
        } elseif ($st == 'suspended' && $this->status == 'active') {
            $EMT = new Email_template(17);
            $EMT->orderID = $this->orderID;
            $EMT->send();
            // suspend module command
            $this->moduleQueueCmd('moduleCmd', array('cmd' => 'suspend'));
        } elseif ($st == 'inactive' && ($this->status == 'active' || $this->status == 'suspended')) {
            $EMT = new Email_template(23);
            $EMT->orderID = $this->orderID;
            $EMT->send();
            // terminate module command
            $this->moduleQueueCmd('moduleCmd', array('cmd' => 'terminate'));
        } elseif ($st == 'active' && $this->status == 'suspended') {
            $EMT = new Email_template(18);
            $EMT->orderID = $this->orderID;
            $EMT->send();
            // unsuspend module command
            $this->moduleQueueCmd('moduleCmd', array('cmd' => 'unsuspend'));
        } elseif ($st == 'deleted') {
            $EMT = new Email_template(19);
            $EMT->orderID = $this->orderID;
            $EMT->send();
        }
        // update addons status
        $db->query("UPDATE orders SET status = '" . $st . "' WHERE parentID = " . $this->orderID);
        $this->set('status', $st);
        return array('st' => true);
    }

    function renew($queue = false, $paymentID = 0, $multiplier = 1)
    {
        global $db;
        if ($queue == true) {
            Queue::createJob('reneworder')->setParams(array('multiplier' => $multiplier))
                    ->setOrderID($this->orderID)
                    ->setPaymentID($paymentID)
                    ->setStatus('pending-payment')
                    ->update()
                    ->start();
            return;
        }

        // siparis bitis tarihinden sonraki borclari sil
        $db->query(
            "DELETE FROM order_bills WHERE orderID = " . $this->orderID . " AND status = 'unpaid' AND type = 'recurring' AND dateStart >= " . $this->dateEnd
        );


        $OB = $this->createBill('recurring')->dateStart($this->dateEnd)
                ->dateEnd(addDate($this->dateEnd, ($this->period * $multiplier), 'm'))
                ->dateDue($this->dateEnd)
                ->amount($this->price * $multiplier);
        $OB->paymentID = $paymentID;
        $OB->pay();
        $this->set('dateEnd', $OB->dateEnd);
        $OB->update();


        $this->loadService();

        // renew addons
        $this->loadAddons();
        foreach ((array)$this->addons as $addonID) {
            $Addon = new Order($addonID);
            $Addon->renew(false, $paymentID, $multiplier);
        }

        // renew domain if domain order
        if ($this->Service->moduleID != '') {
            if ($this->Service->groupID == 10) {
                // domain renewal
                $Domain = Domain::newInstanceByOrderID($this->orderID);
                if ($Domain->domainID != false) {
                    $Domain->renew($this->period * $multiplier, true);
                }
            } else {
                // renew module if method exists
                $MOD = Module::getInstance($this->Service->moduleID);
                if (method_exists($MOD, 'renew')) {
                    $MOD->renew($multiplier);
                }
            }
        }

        if (! $this->parentID) {
            // set status active if needed
            $this->setStatus('active');

            // send renewal email
            $EMT = new Email_template(21);
            $EMT->orderID = $this->orderID;
            $EMT->send();
        }

        return array('st' => true);
    }

    function bindService()
    {
        global $db;
        $this->set('serverID', $this->Service->serverID);

        $sql = "SELECT sa.*,sat.encrypted 
                FROM service_attrs sa 
                    LEFT JOIN service_attr_types sat ON sat.setting = sa.setting 
                WHERE sa.serviceID = " . $this->serviceID;
        $attrs = (array)$db->query($sql, SQL_KEY, 'setting');

        foreach ($attrs as $set => $item) {
            if ($item['encrypted'] == '1') {
                $item['value'] = core::encrypt($item['value']);
            }
            $db->query(
                "INSERT INTO order_attrs (orderID,setting,value,clientCanSee)
                                        VALUES (" . $this->orderID . ",'" . $set . "','" . $item['value'] . "','" . $item['clientCanSee'] . "')"
            );
        }
    }

    function updateAttrs($attrs)
    {
        if (! is_array($attrs)) {
            return false;
        }
        global $db;
        $encs = Setting::getEncryptedSettings();
        foreach ($attrs as $setting => $value) {
            if (in_array($setting, $encs)) {
                $value = core::encrypt($value);
            }
            $db->query(
                "UPDATE order_attrs SET value = '" . $value . "' WHERE setting = '" . $setting . "' AND orderID = " . $this->orderID
            );
        }
        // paket adını güncelle
        $this->setTitle();
    }

    function getModuleID()
    {
        global $db;
        $moduleID = $db->query(
            "SELECT moduleID FROM services WHERE serviceID = " . $this->serviceID,
            SQL_INIT,
            "moduleID"
        );
        return $moduleID;
    }

    function loadAttrs()
    {
        global $db;

        $sql = "SELECT setting,value,clientCanSee FROM order_attrs 
                WHERE value != '' AND orderID = " . $this->orderID;
        $this->attrs = $db->query($sql, SQL_KEY, 'setting');

        $encs = Setting::getEncryptedSettings();
        foreach ($this->attrs as $set => $data) {
            if (in_array($set, $encs)) {
                $this->attrs[$set]['value'] = core::decrypt($this->attrs[$set]['value']);
            }
        }
        return $this;
    }

    function loadAddonAttrs()
    {
        global $db;
        $this->addon_attrs = array();
        $this->loadAddons();
        if (! $this->addons) {
            return false;
        }

        $sql = "SELECT setting,value FROM order_attrs 
                WHERE value != '' AND orderID IN (" . implode(',', $this->addons) . ")";
        $attrs = $db->query($sql, SQL_ALL);
        foreach ($attrs as $data) {
            if (is_numeric($data['value'])) {
                $this->addon_attrs[$data['setting']]['value'] += $data['value'];
            }
        }
        return $this;
    }

    function getAttributes()
    {
        global $db;
        $sql = "SELECT sat.*,oa.clientCanSee FROM order_attrs oa INNER JOIN service_attr_types sat ON oa.setting = sat.setting
                WHERE oa.orderID = " . $this->orderID;

        $attrs = $db->query($sql, SQL_KEY, 'setting');
        foreach ($attrs as $attr => $data) {
            $ret[$attr] = Setting::type($data['type'])->lab($data['label'])->desc($data['description'])->width(
                $data['width']
            )->source('custom');
            $ret[$attr]->clientCanSee = $data['clientCanSee'];
            if ($attr == 'password') {
                $ret[$attr]->cmd('setPassword');
            }
        }
        return (array)$ret;
    }

    function setAttr($set, $val, $add = true)
    {
        global $db;
        if ($add == true) {
            $this->loadService();
            $set = $this->Service->moduleID . '_' . $set;
        }
        $this->moduleAttrs[$set] = $val;

        // encrypt value
        if (in_array($set, Setting::getEncryptedSettings())) {
            $val = core::encrypt($val);
        }

        // update
        $sql = "INSERT INTO order_attrs (orderID,setting,value) VALUES (" . $this->orderID . ",'" . $set . "','" . $val . "')
                ON DUPLICATE KEY UPDATE value = '" . $val . "'";

        $db->query($sql);
    }

    function loadAddons()
    {
        if ($this->isLoadedAddons) {
            return true;
        }
        global $db;
        $sql = "SELECT orderID FROM orders WHERE status = '" . $this->status . "' AND  parentID = " . $this->orderID;
        $this->addons = $db->query($sql, SQL_KEY, 'orderID');
        $this->isLoadedAddons = true;
        return $this;
    }

    function loadAddonOrders()
    {
        global $db;
        $sql = "SELECT o.* FROM orders o WHERE o.parentID = " . $this->orderID;
        $addonOrders = (array)$db->query($sql, SQL_ALL);
        return $addonOrders;
    }

    function loadService()
    {
        if ($this->isLoadedService) {
            return true;
        }
        $this->Service = new Service($this->serviceID);
        $this->isLoadedService = true;
        return $this;
    }

    function loadClient()
    {
        if ($this->isLoadedClient) {
            return true;
        }
        $this->Client = new Client($this->clientID);
        $this->isLoadedClient = true;
        return $this;
    }


    // ****************  Finance Methods ****************** //

    function getBills($status = 'all', $orderID = '')
    {
        global $db;
        if ($status != 'all') {
            $inSQL = " AND status = '" . $status . "'";
        }

        $sql = "SELECT orderID FROM orders WHERE parentID = " . $this->orderID;
        $ids = $db->query($sql, SQL_KEY, 'orderID');
        $ids[] = $this->orderID;

        if ($orderID == 'all' || $orderID == '') {
            $where = 'IN (' . implode(',', $ids) . ')';
        } else {
            //if ($orderID == '') $orderID = $this->orderID; 
            $where = " = '" . (int)$orderID . "'";
        }
        if (VZUSERTYPE != 'admin') {
            $sql = "SELECT ob.* FROM order_bills ob INNER JOIN orders o ON o.orderID = ob.orderID 
                    WHERE ob.orderID $where AND o.clientID = " . $_SESSION['vclient']->clientID . " $inSQL
                    ORDER BY ob.dateDue DESC";
        } else {
            $sql = "SELECT * FROM order_bills WHERE orderID $where $inSQL ORDER BY dateDue DESC";
        }
        $this->bills = $db->query($sql, SQL_ALL);
        return $this->bills;
    }

    function getAddonBills($unpaidOnly = false)
    {
        global $db;
        if ($unpaidOnly) {
            $inSQL = 'AND ob.status = "unpaid"';
        }
        $sql = "SELECT * FROM order_bills ob 
                     INNER JOIN orders o ON (o.orderID = ob.orderID AND o.parentID = $this->orderID AND o.status NOT IN ('inactive')) 
                WHERE 1=1 $inSQL ORDER BY ob.dateDue DESC";
        $this->addonBills = (array)$db->query($sql, SQL_ALL);
        return $this->addonBills;
    }

    function createNextBill($paymentID = 0, $billPaymentStatus, $dateStart = 0)
    {
        if (! (float)$this->price) {
            core::raise('Ücretsiz bir sipariş için borç kaydı oluşturamazsınız', 'e');
            return false;
        } elseif (! $this->dateEnd) {
            core::raise('Bitiş tarihi olmayan bir sipariş için borç kaydı oluşturamazsınız', 'e');
            return false;
        }
        if (! $dateStart) {
            global $db;
            // hic odenmemis faturasi var mi
            $sql = "SELECT MAX(dateEnd) as max_dateEnd FROM order_bills WHERE status = 'unpaid' AND orderID = " . $this->orderID;
            $max_unpaid_dateEnd = $db->query($sql, SQL_INIT, 'max_dateEnd');
            if ($max_unpaid_dateEnd) {
                // son odenmemis faturasin
                $dateStart = $max_unpaid_dateEnd;
            } else {
                $dateStart = $this->dateEnd;
            }
        }

        $OB = $this->createBill('recurring')->dateStart($dateStart)
                ->dateEnd(addDate($dateStart, $this->period, 'm'))
                ->dateDue($dateStart)
                ->amount($this->price);
        $OB->paymentID = $paymentID;

        if ($billPaymentStatus == 'paid') {
            $OB->pay();
            $this->set('dateEnd', $OB->dateEnd);
        }
        $OB->update();

        /* Periyodik odemeli siparis degilse bir sonraki borc kaydini olusturma */
        if ($this->payType != 'recurring') {
            return true;
        }

        // create next if needed
        $dateNextDue = addDate($dateStart, $this->period, 'm');

        if ($OB->dateEnd > $OB->dateStart && $OB->dateEnd < time() + getSetting('payments_billgen') * 60 * 60 * 24) {
            return $this->createNextBill($paymentID, $billPaymentStatus, $dateNextDue);
        } else {
            return true;
        }
    }

    function createBill($type)
    {
        $OB = new OrderBill();
        $OB->discount = $this->discount;
        $OB->orderID = $this->orderID;
        $OB->type = $type;
        $OB->paycurID = $this->paycurID;
        $OB->create();
        return $OB;
    }

    function payBill($billID, $paymentID = 0)
    {
        global $db;
        $OB = new OrderBill($billID);
        $OB->pay($paymentID)->update();
        $this->set('dateEnd', $OB->dateEnd);
    }

    function getRenewPrice()
    {

        global $db;
        // or is this an early renewal
        $sql = "SELECT SUM(price) AS amount FROM orders 
                WHERE status IN ('active','suspended') AND (orderID = " . $this->orderID . " OR parentID = " . $this->orderID . ")";
        $amount = $db->query($sql, SQL_INIT, "amount");

        $amount = $amount * (1 - $this->discount);
        return $amount;

    }


    //////////////////////

    function displayPeriod()
    {
        if ($this->period > 12) {
            $this->periodDisplay = ($this->period / 12) . " ##Years##";
        } else {
            $this->periodDisplay = $this->period;
            $this->periodDisplay .= ($this->period == 1) ? " ##Month##" : " ##Months##";
        }
        return $this->periodDisplay;
    }

    function getStatusIcon()
    {
        return Order::$icons[$this->status];
    }

    function setTitle()
    {
        $title = $this->Service->service_name;
        if ($this->Service->settingID > 0) {
            global $db;
            $sql = "SELECT value,encrypted FROM order_attrs oa INNER JOIN service_attr_types sat ON sat.setting = oa.setting 
                    WHERE sat.settingID = " . $this->Service->settingID . " AND oa.orderID = " . $this->orderID;
            $attr = $db->query($sql, SQL_INIT);
            $value = ($attr['encrypted'] == '1') ? core::decrypt($attr['value']) : $attr['value'];
            if ($value != '') {
                $title .= ' (' . $value . ')';
            }
        } elseif ($this->Domain) {
            $title .= ' (' . $this->Domain->domain . ')';
        }
        $this->set('title', $title);
    }


    // ****************   Module Methods **************** //

    function getModuleConfig()
    {
        $this->loadService();

        return Module::getInstance($this->Service->moduleID)->getModuleConfig();
        return Module::getConfig($this->Service->moduleID);

    }

    function loadModule()
    {
        $this->loadService();
        $this->Module = Module::getInstance($this->Service->moduleID);
        if (! is_object($this->Module)) {
            return false;
        }
        $this->isModuleLoaded = true;
        $this->Module->setOrder($this);
        return $this;
    }

    function loadModuleCmds()
    {
        if (! $this->isModuleLoaded) {
            $this->loadModule();
        }
        if (! is_object($this->Module) || ! method_exists($this->Module, 'getCmds')) {
            return $this;
        }

        $cmds = $this->Module->getCmds();
        $this->moduleAdminCmds = (array)$cmds['admin'];
        $this->moduleUserCmds = (array)$cmds['user'];

        return $this;
    }

    function loadModuleLinks()
    {
        if (! $this->isModuleLoaded) {
            $this->loadModule();
        }
        if (! is_object($this->Module) || ! method_exists($this->Module, 'getLinks')) {
            return $this;
        }

        $this->moduleLinks = $this->Module->getLinks();

        return $this;
    }

    function moduleQueueCmd($cmd, $data = array(), $paymentID = 0)
    {
        $this->loadService();
        if ($this->Service->moduleID == '') {
            return false;
        }
        $this->loadModuleCmds();

        $params = array_merge(array('cmd' => $cmd), (array)$data);
        Queue::createJob('modulecmd')->setParams($params)
                ->setOrderID($this->orderID)
                ->setPaymentID($paymentID)
                ->setStatus($data['status'])
                ->update()
                ->start();

    }

    function moduleRunCmd($ocmd, $data = array())
    {
        if (! $this->isModuleLoaded) {
            $this->loadModule();
        }
        if (! $this->isLoadedService) {
            $this->loadService();
        }

        $cmd = 'cmd_' . $ocmd;
        if (method_exists($this->Module, $cmd)) {
            vzrlog('<' . $ocmd . '> çalıştırılıyor...', 'info', $this->Module->Server->serverName, $this->orderID);
            foreach ((array)$data as $key => $val) {
                $key = str_replace($this->Service->moduleID . '_', '', $key);
                $ndata[$key] = $val;
            }
            $result = $this->Module->$cmd($ndata);
            if ($result['st']) {
                vzrlog('<' . $ocmd . '> işlem başarılı', 'info', $this->Module->Server->serverName, $this->orderID);
            } else {
                vzrlog(
                    '<' . $ocmd . '> işleminde hata: ' . $result['msg'],
                    'error',
                    $this->Module->Server->serverName,
                    $this->orderID
                );
            }
            return $result;
        } else {
            return array('st' => false, 'msg' => 'module_method_doesnot_exist');
        }
    }

    /**               Static Functions                 **/

    static function getClientIdFromOrderId($orderID)
    {
        global $db;
        return $db->query("SELECT clientID FROM orders WHERE orderID = " . $orderID, SQL_INIT, "clientID");
    }

    static function newInstance($orderID)
    {
        $o = new self();
        $o->orderID = $orderID;
        return $o;
    }


    function transfer($newClientID, $transfer_funds)
    {
        global $db;
        $newClient = new Client($newClientID);
        if (! $newClient->clientID) {
            return array('st' => false, 'msg' => 'Böyle bir müşteri kaydı bulunamadı');
        }

        if ($transfer_funds == '1') {
            $totalPayed = $db->query(
                "SELECT SUM(amount) as total FROM order_bills WHERE status = 'paid' AND orderID = " . $this->orderID,
                SQL_INIT,
                'total'
            );
            $description = $this->orderID . " nolu sipariş transfer işlemi";
            Payment::addPayment(
                $this->clientID,
                '',
                'paid',
                time(),
                - $totalPayed,
                $this->paycurID,
                $description,
                false
            );
            Payment::addPayment($newClientID, '', 'paid', time(), $totalPayed, $this->paycurID, $description, false);
        }
        $this->set('clientID', $newClientID);
        return array('st' => true);

    }

    function destroy()
    {
        global $db;

        if ($this->addon == '0') {
            $addons = $this->loadAddons()->addons;
            foreach ($addons as $orderID) {
                $AO = new Order($orderID);
                $AO->destroy();
            }
        }

        $db->query("DELETE FROM order_attrs WHERE orderID = " . $this->orderID);
        $db->query("DELETE FROM order_bills WHERE orderID = " . $this->orderID);
        $db->query("DELETE FROM domains WHERE orderID = " . $this->orderID);
        $db->query("DELETE FROM queue WHERE orderID = " . $this->orderID);
        $db->query("DELETE FROM orders WHERE orderID = " . $this->orderID);

    }

} // end of class order
