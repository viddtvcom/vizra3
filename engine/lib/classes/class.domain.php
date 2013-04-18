<?php
class Domain extends base
{

    public $domainID;
    public $orderID;
    public $domain;
    public $moduleID;
    public $status;
    public $ns1, $ns2, $ns3, $ns4;
    public $locked;
    public $dateReg;
    public $dateExp;
    public $dateAdded;
    public $dateUpdated;

    static public $icons = array(
        'pending' => 'led_yellow.png',
        'intransfer' => 'led_blue.png',
        'active' => 'led_green.png',
        'expired' => 'led_red.png',
        'deleted' => 'led_white.png',
        'inactive' => 'led_white.png'
    );


    function Domain($id = "")
    {
        $this->db_table = "domains";
        $this->ID_field = "domainID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'domainID',
            'orderID',
            'domain',
            'moduleID',
            'status',
            'ns1',
            'ns2',
            'ns3',
            'ns4',
            'locked',
            'dateReg',
            'dateExp',
            'dateAdded',
            'dateUpdated'
        );
        if ($id) {
            $this->load((int)$id);
        }
    }

    function create($domain)
    {
        global $db;
        $this->domainID = generateID($this->db_table, $this->ID_field);
        $db->query(
            "INSERT INTO domains (domainID,dateAdded,dateUpdated, dateReg, dateExp)
                            VALUES (" . $this->domainID . ",UNIX_TIMESTAMP(),UNIX_TIMESTAMP(), 0, 0)"
        );
        $this->load();
        $this->domain = $domain;
        $this->extensionData = domain::getExtensionData(domain::getExtensionFromDomain($domain));
        $this->moduleID = $this->extensionData["moduleID"];
        $this->update();
    }

    function load($id = "")
    {
        parent::load($id);
        if ($this->moduleID != '') {
            $this->hasModule = true;
        }
        /*    if (VZUSERTYPE != 'admin' && $_SESSION["vclient"]->clientID != order::getClientIdFromOrderId($this->orderID)) {
                unset($this->domainID);
            }    */
    }

    function loadExtensionData()
    {
        $extension = Domain::getExtensionFromDomain($this->domain);
        $data = Domain::getExtensionData($extension);
        $this->domlock = $data['domlock'];
        $this->authcode = $data['authcode'];
    }

    function register($period)
    {
        if ($this->ns1 == '' || $this->ns2 == '') {
            return array('msg' => 'Geçersiz DNS Sunucusu');
        }
        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'register')) {
            return false;
        }

        $res = $this->Registrar->register(&$this, $period);
        if ($res["st"]) {
            $this->set("status", "active");
            $Order = new Order($this->orderID);
            $Order->setStatus('active');

            if (method_exists($this->Registrar, 'refresh')) {
                $this->Registrar->refresh(&$this);
            }

            $EMT = new Email_template(10);
            $EMT->domainID = $this->domainID;
            $ret = $EMT->send();
        }
        return $res;
    }

    function renew($period, $queue = false)
    {
        if ($queue == true) {
            $params = array('period' => $period, 'domainID' => $this->domainID);
            Queue::createJob('domrenew')->setParams($params)->setOrderID($this->orderID)->update()->start();
            return;
        }

        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'renew')) {
            return false;
        }
        $res = $this->Registrar->renew(&$this, $period);
        if ($res["st"]) {
            $this->set("status", "active");
            $Order = new Order($this->orderID);
            $Order->setStatus('active');

            $EMT = new Email_template(20);
            $EMT->domainID = $this->domainID;
            $ret = $EMT->send();
        }
        return $res;
    }

    function refresh()
    {
        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'refresh')) {
            return false;
        }
        $res = $this->Registrar->refresh(&$this);
        return $res;
    }

    function setDNS($ns1, $ns2, $ns3 = "", $ns4 = "")
    {
        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'setDNS')) {
            return false;
        }
        $res = $this->Registrar->setDNS($this, $ns1, $ns2, $ns3, $ns4);
        if ($res["st"]) {
            $this->ns1 = $ns1;
            $this->ns2 = $ns2;
            $this->ns3 = $ns3;
            $this->ns4 = $ns4;
            $this->update();
        }
        return $res;
    }

    function getAuthCode($send2client = false)
    {
        $this->loadModule();
        if (method_exists($this->Registrar, 'getAuthCode')) {
            $ret = $this->Registrar->getAuthCode(&$this);
            if ($ret['st']) {
                if ($send2client) {
                    $EMT = new Email_template(22);
                    $EMT->replaces['authcode'] = $ret['authcode'];
                    $EMT->domainID = $this->domainID;
                    $EMT->send();
                }
                return array('st' => true, 'authcode' => $ret['authcode']);
            } else {
                return $ret;
            }
        } else {
            return array('st' => false, 'msg' => 'Module doesnt support auth code');
        }
    }

    function lock()
    {
        $this->loadModule();
        if (method_exists($this->Registrar, 'lock')) {
            $ret = $this->Registrar->lock(&$this);
            if ($ret['st']) {
                $this->set('locked', '1');
                return array('st' => true);
            } else {
                return $ret;
            }
        } else {
            return array('st' => false, 'msg' => 'Module doesnt support domain lock');
        }
    }

    function unlock()
    {
        $this->loadModule();
        if (method_exists($this->Registrar, 'unlock')) {
            $ret = $this->Registrar->unlock(&$this);
            if ($ret['st']) {
                $this->set('unlocked', '1');
                return array('st' => true);
            } else {
                return $ret;
            }
        } else {
            return array('st' => false, 'msg' => 'Module doesnt support domain lock');
        }
    }


    function addContact(Contact $Contact)
    {
        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'addContact')) {
            return false;
        }

        $res = $this->Registrar->addContact($Contact);
        return $res;
    }

    function setContact($contactID, $type)
    {
        global $db;
        $sql = "INSERT INTO domain_contacts (domainID,contactID,type) VALUES (" . $this->domainID . ",'" . $contactID . "','" . $type . "')
            ON DUPLICATE KEY UPDATE contactID = '" . $contactID . "'";
        $db->query($sql);
    }

    function setDomainContacts($new)
    {
        $this->loadModule();
        if (! $this->Registrar || ! method_exists($this->Registrar, 'setDomainContacts')) {
            return false;
        }

        $old = $this->getContactRegistrarData();

        $diff = array_diff_assoc($new, $old);
        if (! $diff && $old && $new) {
            return array('st' => true);
        }
        $contacts = array_merge($old, $new);
        $res = $this->Registrar->setDomainContacts(&$this, $contacts);
        return $res;
    }

    function getContactRegistrarData()
    {
        global $db;
        $sql = "SELECT dc.type,dcr.registrarID FROM domain_contacts dc INNER JOIN domain_contact_registrar dcr ON dcr.contactID = dc.contactID
            WHERE dc.domainID = " . $this->domainID;
        return $db->query($sql, SQL_KEY, 'type');
    }

    function updateContactRegistrarData($data)
    {
        global $db;
        foreach ($data as $type => $registrarID) {
            $contactID = Contact::getContactIdByRegistrarId($registrarID);
            if (! $contactID) {
                // contact vsys de yok, kaydetmeye calis;
                $ret = $this->Registrar->getContactInfoDetails($registrarID, true, $this->getClientId());
                $contactID = $ret['contactID'];
            }
            $this->setContact($contactID, $type);
        }
    }


// Helper Functions

    function saveDNS($ns1, $ns2, $ns3 = "", $ns4 = "")
    {
        $this->ns1 = $ns1;
        $this->ns2 = $ns2;
        $this->ns3 = $ns3;
        $this->ns4 = $ns4;
        $this->update();
    }


    function moduleCmd($cmd)
    {
        $result = Module::getInstance($this->moduleID)->$cmd();
        return $result;
    }

    function loadModule($moduleID = "")
    {
        if ($this->moduleLoaded) {
            return true;
        }
        //$moduleID = ($moduleID != "") ? $moduleID : $this->moduleID;
        $this->Registrar = Module::getInstance($this->moduleID);
        $this->moduleLoaded = true;
    }

    function getContactDetails($contact_type = 'registrantcontact')
    {
        $this->loadModule();
        $details = $this->Registrar->getContactDetails($this->domain, $contact_type);
        return $details;
    }

    function getContactTypes()
    {
        $this->loadModule();
        return $this->Registrar->getContactTypes();
    }

    function getClientId()
    {
        global $db;
        return Order::getClientIdFromOrderId($this->orderID);
    }


    static function getExtensionFromDomain($dom)
    {
        $pos = strpos($dom, ".") + 1;
        $tld = substr($dom, $pos, strlen($dom) - $pos);
        return $tld;
    }

    static function getExtensionData($extension)
    {
        global $db;
        $sql = "SELECT * FROM domain_extensions de
                INNER JOIN services s ON s.serviceID = de.serviceID 
            WHERE de.extension = '$extension'";
        return $db->query($sql, SQL_INIT);

    }

    static function getDomainIdByOrderId($orderID)
    {
        global $db;
        $domainID = $db->query("SELECT domainID FROM domains WHERE orderID = " . $orderID, SQL_INIT, "domainID");
        return $domainID;
    }

    static function newInstanceByOrderID($orderID, $load = true)
    {
        global $db;
        $Domain = new self();
        $Domain->domainID = $db->query(
            "SELECT domainID FROM domains WHERE orderID = " . $orderID,
            SQL_INIT,
            "domainID"
        );
        if ($load) {
            $Domain->load();
        }
        return $Domain;
    }

    static function getServiceIdByDomain($domain)
    {
        $ext = self::getExtensionFromDomain($domain);
        $domExt = getDomainExtensions();
        return $domExt[$ext]['serviceID'];
    }


} // end of class
