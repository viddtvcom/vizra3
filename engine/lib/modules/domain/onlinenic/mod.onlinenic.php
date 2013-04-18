<?php

class mod_onlinenic extends DomainModule
{
    private $debug = false;
    private $resultCode;

    function __construct($moduleID)
    {
        $this->moduleID = $moduleID;
        $this->getSettings();
    }

    function login()
    {
        if ($this->loggedin) {
            return true;
        }
        $clTrid = $this->getClTrid();
        $checksum = md5($this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "login");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
            <command>
                <creds>
                    <clID>" . $this->settings['customerID'] . "</clID>
                    <options>
                        <version>1.0</version>
                        <lang>en</lang>
                    </options>
                </creds>
                <clTRID>" . $clTrid . "</clTRID>
                <login>
                    <chksum>" . $checksum . "</chksum>
                </login>
            </command>
            </epp>";
        $result = $this->call($xml, 'login');


        if (strstr($result, "<result code=\"1000\">")) {
            $this->loggedin = true;
            return true;
        } else {
            vzrlog('Onlinenic login error: ' . getXmlData('msg', $result) . ' ' . getXmlData('value', $result));
            $this->loggedin = false;
            return false;
        }
    }

    function register(Domain $Domain, $period)
    {
        $period = $period / 12;
        $clTrid = $this->getClTrid();
        $authcode = generateCode('12', 'hard');

        $Client = new Client(Order::getClientIdFromOrderId($Domain->orderID));
        $CO = $Client->getDefaultContact();

        $registrarContactId = $CO->getRegistrarID($this->moduleID);
        if (! $registrarContactId) {
            $call = $this->addContact($CO);
            if (! $call['st']) {
                return $call;
            } else {
                $registrarContactId = $call['id'];
            }
        }

        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "crtdomain0" .
                    $Domain->domain . $period .
                    $Domain->ns1 .
                    $Domain->ns2 .
                    $registrarContactId . $registrarContactId . $registrarContactId . $registrarContactId . $authcode
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <create>
                        <domain:create>
                            <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                            <domain:name>" . $Domain->domain . "</domain:name>
                            <domain:period>" . $period . "</domain:period>
                            <domain:ns1>" . $Domain->ns1 . "</domain:ns1>
                            <domain:ns2>" . $Domain->ns2 . "</domain:ns2>
                            <domain:registrant>" . $registrarContactId . "</domain:registrant>
                            <domain:contact type=\"admin\">" . $registrarContactId . "</domain:contact>
                            <domain:contact type=\"tech\">" . $registrarContactId . "</domain:contact>
                            <domain:contact type=\"billing\">" . $registrarContactId . "</domain:contact>
                            <domain:authInfo type=\"pw\">" . $authcode . "</domain:authInfo>
                        </domain:create>
                    </create>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        if ($this->resultCode != '1000') {
            return array('st' => false, 'msg' => getXmlData('msg', $result) . ' ' . getXmlData('value', $result));
        } else {
            return array('st' => true);
        }

    }

    function setDNS(Domain $Domain, $ns1, $ns2, $ns3, $ns4)
    {
        $clTrid = $this->getClTrid();
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "upddomain0" .
                    $Domain->domain . $ns1 . $ns2
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <update>
                        <domain:update>
                            <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                            <domain:name>" . $Domain->domain . "</domain:name>
                            <domain:rep>
                                <domain:ns1>" . $ns1 . "</domain:ns1>
                                <domain:ns2>" . $ns2 . "</domain:ns2>
                            </domain:rep>
                        </domain:update>
                    </update>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        if ($this->resultCode != '1000') {
            $errors[2005] = "Geçersiz DNS sunucusu";
            $errors[2400] = "Geçersiz DNS sunucusu";
            return array('st' => false, 'msg' => $this->resultCode . ': ' . $errors[$this->resultCode]);
        } else {
            return array("st" => true);
        }

    }

    function renew(Domain $Domain, $period)
    {
        $period = $period / 12;
        $clTrid = $this->getClTrid();
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "renewdomain0" .
                    $Domain->domain . $period
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <renew>
                    <domain:renew xmlns:domain=\"urn:iana:xml:ns:domain-1.0\" xsi:schemaLocation=\"urn:iana:xml:ns:domain-1.0 domain-1.0.xsd\">
                    <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                    <domain:name>" . $Domain->domain . "</domain:name>
                    <domain:period>" . $period . "</domain:period>
                    </domain:renew>
                    </renew>                
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        if ($this->resultCode == '1000') {
            $Domain->dateExp = addDate($Domain->dateExp, $period, 'y');
            $Domain->update();
            return array('st' => true);
        } else {
            $errors[2104] = 'Yetersiz Bakiye';
            $errors[2105] = 'Bir alan adı 5dk içinde 2 kere yenilenemez';
            return array('st' => false, 'msg' => $errors[$this->resultCode]);
        }

    }

    function refresh(Domain $Domain)
    {
        $res = $this->getDetailsByDomain($Domain->domain);

        if (! $res['st']) {
            return array('st' => false, 'msg' => 'Alan adı bilgilerine ulaşılamadı');
        } else {
            $data = $res['data'];
        }

        $dateReg = explode("/", $data["domain_creData"]["0"]["regdate"]);
        $dateExp = explode("/", $data["domain_creData"]["0"]["expdate"]);


        $Domain->dateReg = mktime(0, 0, 0, $dateReg[0], $dateReg[1], $dateReg[2]);
        $Domain->dateExp = mktime(0, 0, 0, $dateExp[0], $dateExp[1], $dateExp[2]);

        $Domain->saveDNS(
            $data["domain_creData"]["0"]["dns1"],
            $data["domain_creData"]["0"]["dns2"],
            $data["domain_creData"]["0"]["dns3"],
            $data["domain_creData"]["0"]["dns4"]
        );


        /*    // update contacts
            $contacts = array('registrant'=>$res['registrantcontactid'], 'tech'=>$res['techcontactid'], 'admin'=>$res['admincontactid'], 'billing'=>$res['billingcontactid']);
            $Domain->updateContactRegistrarData($contacts);

            // lock status
            if ($res["orderstatus"]["0"] == "customerlock" || $res["orderstatus"]["0"] == "transferlock") {
                $Domain->locked = '1';
            } else {
                $Domain->locked = '0';
            }
            // domain status
            if ($res['currentstatus'] == 'Active') {
                $Domain->status = 'active';
            }  */
        $Domain->update();
        return array('st' => true);
    }

    function getDetailsByDomain($domain)
    {
        $clTrid = $this->getClTrid();
        $checksum = md5($this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "getdomaininfo");
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <getdomaininfo>
                        <clID>" . $this->settings['customerID'] . "</clID>
                        <domain>" . $domain . "</domain>
                        <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                        <options>
                        <version>1.0</version>
                        <lang>en</lang>
                        </options>
                    </getdomaininfo>               
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);


        if ($this->resultCode == '1000') {
            $xmlArray = new XmlToArray($result);
            $arr = $xmlArray->createArray();
            $response = $arr["epp"]["response"]["0"];
            return array('st' => true, 'data' => $response["resData"]["0"]);
        } else {
            return array('st' => false);
        }

    }

    function setLockStatus(Domain $Domain, $action)
    {
        $clTrid = $this->getClTrid();
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "upddomain0" . $Domain->domain
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <update>
                        <domain:update>
                            <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                            <domain:name>" . $Domain->domain . "</domain:name>
                            <domain:" . $action . ">
                                <domain:status s=\"clientTransferProhibited\"/>
                            </domain:" . $action . ">
                        </domain:update>
                    </update>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        if ($this->resultCode == '1000' || $this->resultCode == '2302' || $this->resultCode == '2303') {
            return array('st' => true);
        } else {
            return array('st' => false);
        }
    }

    function lock(Domain $Domain)
    {
        return $this->setLockStatus($Domain, 'add');
    }

    function unlock(Domain $Domain)
    {
        return $this->setLockStatus($Domain, 'rem');
    }

    function getAuthCode(Domain $Domain)
    {
        $clTrid = $this->getClTrid();
        $authcode = generateCode(12, 'hard');
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "upddomain" .
                    $this->getDomainType($Domain->domain) . $Domain->domain . $authcode
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <update>
                        <domain:update>
                            <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                            <domain:name>" . $Domain->domain . "</domain:name>
                            <domain:rep>
                                <domain:authInfo type=\"pw\">" . $authcode . "</domain:authInfo>
                            </domain:rep>
                        </domain:update>
                    </update>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        if ($this->resultCode == '1000' || $this->resultCode == '2302' || $this->resultCode == '2303') {
            return array('st' => true, 'authcode' => $authcode);
        } else {
            return array('st' => false);
        }

    }

    function contest(Domain $Domain)
    {
        $clTrid = $this->getClTrid();
        $contact_id = 'oln16805542';
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "upddomain" .
                    $this->getDomainType($Domain->domain) . $Domain->domain . $contact_id
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <update>
                        <domain:update>
                            <domain:type>" . $this->getDomainType($Domain->domain) . "</domain:type>
                            <domain:name>" . $Domain->domain . "</domain:name>
                            <domain:rep>
                                <domain:contact type=\"admin\">" . $contact_id . "</domain:authInfo>
                            </domain:rep>
                        </domain:update>
                    </update>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);
        //debug($xml);
        //debug($result);

        if ($this->resultCode == '1000') {
            return array('st' => true, 'authcode' => $authcode);
        } else {
            return array('st' => false);
        }

    }


// <contact>

    function setDomainContacts(Domain $Domain, $contacts)
    {
        $wsdlfile = "DomOrder.wsdl";

        $arrParms = array(
            $this->getDetailsByDomain($Domain->domain, 'entityid'),
            $contacts['registrant'],
            $contacts['admin'],
            $contacts['tech'],
            $contacts['billing']
        );
        $response = $this->call("modifyContact", $wsdlfile, $arrParms);
        if ($response["status"] == "Success") {
            $Domain->updateContactRegistrarData($contacts);
            return array("st" => true);
        } else {
            return array("st" => false, "msg" => $this->errorMsg);
        }
    }

    function addContact(Contact &$Contact)
    {

        $clTrid = $this->getClTrid();
        $checksum = md5(
            $this->settings['customerID'] . md5($this->settings['password']) . $clTrid . "crtcontact" .
                    $Contact->name . $Contact->company . $Contact->email
        );

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <epp>
                <command>
                    <create>
                        <contact:create xmlns:contact=\"urn:iana:xml:ns:contact-1.0\" xsi:schemaLocation=\"urn:iana:xml:ns:contact-1.0 contact-1.0.xsd\">
                        <contact:domaintype>0</contact:domaintype>
                        <contact:ascii>
                        " . $this->rebuildContact($Contact) . "
                        </contact:create>
                    </create>
                    <clTRID>" . $clTrid . "</clTRID>
                    <chksum>" . $checksum . "</chksum>
                </command>
            </epp>";

        $result = $this->call($xml);

        /*    debug($xml);
            debug($result,1);*/

        if (! strstr($result, "<result code=\"1000\">")) {
            return array('st' => false, 'msg' => getXmlData('msg', $result) . ' ' . getXmlData('value', $result));
        } else {
            $registrarID = getXmlData('contact:id', $result);
            $Contact->setRegistrarID($this->moduleID, $registrarID);
            return array('st' => true, 'id' => $registrarID);
        }

    }

    function updateContact(Contact &$Contact)
    {
        $wsdlfile = "DomContact.wsdl";
        $arrParms = array_merge(
            array($Contact->getRegistrarID($this->moduleID)),
            $this->rebuildContact($Contact),
            array('Contact')
        );
        $response = $this->call("mod", $wsdlfile, $arrParms);
        if ($response['status'] == 'Success') {
            return array('st' => true);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }
    }

    function rebuildContact(Contact &$Contact)
    {
        $Contact->antiInternational();
        if ($Contact->company == '') {
            $Contact->company = 'N/A';
        }
        if ($Contact->zip == '') {
            $Contact->zip = '12345';
        }
        if ($Contact->cell == '') {
            $Contact->cell = $Contact->phone;
        }

        $calling_code = getCountry($Contact->country, 'calling_code');

        $contact = '<contact:name>' . $Contact->name . '</contact:name>
                <contact:org>' . $Contact->company . '</contact:org>
                <contact:addr>
                <contact:street1>' . $Contact->address . '</contact:street1>
                <contact:street2></contact:street2>
                <contact:city>' . $Contact->name . '</contact:city>
                <contact:sp>' . $Contact->state . '</contact:sp>
                <contact:pc>' . $Contact->zip . '</contact:pc>
                <contact:cc>' . $Contact->country . '</contact:cc>
                </contact:addr>
                </contact:ascii>
                <contact:voice>+' . $calling_code . '.' . str_replace(' ', '', $Contact->phone) . '</contact:voice>
                <contact:fax>+' . $calling_code . '.' . str_replace(' ', '', $Contact->cell) . '</contact:fax>
                <contact:email>' . $Contact->email . '</contact:email>
                <contact:pw>' . generateCode(12, 'hard') . '</contact:pw>';


        return $contact;

    }

    function getDefaultContacts($customerID)
    {
        $wsdlfile = "DomContact.wsdl";
        $arrParms = array($customerID, array('Contact'));
        $response = $this->call("getDefaultContacts", $wsdlfile, $arrParms);
        if ($response['Contact']) {
            return array('st' => true, 'response' => $response);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }
    }

    function saveDefaultContact()
    {
        $contacts = $this->getDefaultContacts($this->get('customerID'));

        if (! $contacts['st']) {
            return false;
        }
        $con = $contacts['response']['Contact']['techContactDetails'];
        /*    $Contact = new Contact();
            $Contact->create(true);
            $Contact->name = $con['contact.name'];
            $Contact->company = $con['contact.company'];
            $Contact->address = $con['contact.address1'];
            $Contact->state = $con['contact.state'];
            $Contact->city = $con['contact.city'];
            $Contact->city = $con['contact.zip'];
            $Contact->country = $con['contact.country'];
            $Contact->phone = $con['contact.telno'];
            $Contact->email = $con['contact.emailaddr'];
            $Contact->update();
            $Contact->setRegistrarID($this->moduleID,$con['contact.contactid']);*/
        $this->getContactInfoDetails($con['contact.contactid'], true, 0);
        $this->set('defaultContactID', $con['contact.contactid']);
        return $con['contact.contactid'];
    }

    function getContactInfoDetails($entityID, $savetosys = false, $clientID = 0)
    {
        $wsdlfile = "DomContact.wsdl";
        $arrParms = array($entityID, array('ContactDetails'));
        $response = $this->call("getDetails", $wsdlfile, $arrParms);
        if ($response['contactid']) {
            $ret = array('st' => true, 'response' => $response);
            if ($savetosys) {
                $Contact = new Contact();
                $Contact->create(true);
                $Contact->clientID = $clientID;
                $Contact->name = $response['name'];
                $Contact->company = $response['company'];
                $Contact->address = $response['address1'];
                $Contact->state = $response['state'];
                $Contact->city = $response['city'];
                $Contact->city = $response['zip'];
                $Contact->country = $response['country'];
                $Contact->phone = $response['telno'];
                $Contact->email = $response['emailaddr'];
                $Contact->update();
                $Contact->setRegistrarID($this->moduleID, $response['contactid']);
                $ret['contactID'] = $Contact->contactID;
            }
            return $ret;
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }
    }

// </contact>


    function getContactTypes()
    {
        return array();
        return array('registrant' => 'Registrant', 'admin' => 'Admin', 'tech' => 'Technical', 'billing' => 'Billing');
    }

    private function connect()
    {
        if ($this->fp) {
            return true;
        }
        $this->fp = @fsockopen(
            ltrim($this->settings['ol_host'], 'http://'),
            ($this->settings['ol_port'] ? $this->settings['ol_port'] : 20001),
            $errno,
            $errstr,
            90
        );
        if (! $this->fp) {
            vzrlog("Onlinenic connection error: $errstr ($errno)");
            return false;
        }
        $this->call('');
    }

    private function call($xml, $cmd = '')
    {
        $this->connect();
        if ($cmd != 'login' && $xml != '') {
            $login_result = $this->login();
            if (! $login_result) {
                return false;
            }
        }

        fputs($this->fp, $xml);
        while (! feof($this->fp)) {
            $line = fgets($this->fp, 2);
            $result .= $line;
            if (preg_match("/<\/epp>$/i", substr($result, - 6, 6))) {
                break;
            }
            if ($i ++ > 4096) {
                break;
            }
        }

        $this->resultCode = $this->getResultCode($result);
        return $result;
    }

    private function getClTrid()
    {
        return "Helloeveryone-" . str_replace(Array(".", " "), "", microtime()) . ($this->cnt ++);
    }

    private function getResultCode($result)
    {
        $start_pos = strpos($result, "<result code=\"");
        return substr($result, $start_pos + 14, 4);
    }

    private function getDomainType($domain)
    {
        preg_match('/^([a-z0-9]+[a-z0-9\-]*[a-z0-9]+)\.([a-z]+[a-z\.]*[a-z]+)$/i', $domain, $type);
        $sld = $type[1];
        $tld = $type[2];

        switch ($tld) {
            default:
                return "0";
            case "biz":
                return "800";
            case "info":
                return "805";
            case "us":
                return "806";
            case "in":
                return "808";
            case "cn":
                return "220";
        }
    }


} // end of module class
