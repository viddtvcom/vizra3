<?php

class mod_directi extends DomainModule
{
    private $debug = false;

    private function call($operation, $wsdlfile, $arrParms)
    {
        $this->error = false;
        $GLOBALS["soap_serviceurl"] = trim($this->get('service_url'));
        require_once(dirname(__FILE__) . "/nusoap.php");
        $password = $this->get('password');
        $arrAuth = array(
            $this->get('username'),
            $password,
            'reseller',
            'en',
            $this->get('parentID')
        );
        $arrAll = array_merge($arrAuth, $arrParms);

        $serviceObj = new soap2client(dirname(__FILE__) . "/wsdl/" . $wsdlfile, "wsdl", 0, 0, 0, 0);
        $resp = (array)$serviceObj->call($operation, $arrAll);
        $this->errorAnalyse($resp);

        if ($this->get('debug') == '1') {
            $request = str_replace($password, '*******', $operation . ':' . print_r($arrAll, 1));
            vzrlog($request, 'info', 'DirectI (request)');
            vzrlog($resp, 'info', 'DirectI (response)');
            if ($serviceObj->error_str != '') {
                vzrlog($serviceObj->error_str, 'info', 'DirectI (soap error)');
            }
        }
        return $resp;
    }

    function register(Domain $Domain, $period)
    {
        $wsdlfile = "DomOrder.wsdl";

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

        $chash['registrantcontactid'] = $registrarContactId;
        $chash['admincontactid'] = $registrarContactId;
        $chash['technicalcontactid'] = $registrarContactId;
        $chash['billingcontactid'] = $registrarContactId;

        $dhash = array($Domain->domain => '' . ($period / 12) . '');
        $temp['domainhash'] = $dhash;
        $temp['contacthash'] = $chash;
        $addParamList[] = $temp;
        $nameServersList = array($Domain->ns1, $Domain->ns2);
        $invoiceOption = 'NoInvoice';
        $enablePrivacyProtection = false;
        $validate = true;
        $extraInfo = array();
        $arrParms = array(
            $addParamList,
            $nameServersList,
            $this->getCustomerID($Client),
            $invoiceOption,
            $enablePrivacyProtection,
            $validate,
            $extraInfo
        );
        $response = $this->call("registerDomain", $wsdlfile, $arrParms);
        foreach ($response as $key => $value) {
            if ($value["status"] == "error") {
                if (strpos($value["error"], "already registered") > 0 || strpos(
                    $value["error"],
                    "nable to connect to Registry"
                ) > 0
                ) {
                    return array("st" => false, "msg" => $value["error"]);
                } elseif (strpos($value["error"], "not a valid Nameserver") > 0) {
                    $Domain->ns1 = getSetting('domains_ns1');
                    $Domain->ns2 = getSetting('domains_ns2');
                    $Domain->update();
                    if ($this->tries ++ > 2) {
                        return array("st" => false, "msg" => "Geçersiz DNS Sunucusu");
                    } else {
                        vzrlog(
                            $Domain->domain . ' alan adı tescil edilmeye çalışırken geçersiz DNS sunucusu hatası aldım, default DNSler ile tescil ediyorum'
                        );
                        return $this->register($Domain, $period);
                    }

                } else {
                    return array("st" => false, "msg" => $value['error']);
                }
            } elseif (strpos($value["actionstatusdesc"], "registration completed Successfully") > 0) {
                $this->refresh($Domain);
                return array("st" => true);
            } elseif ($value["status"] == "InvoicePaid") {
                return array("st" => false, "msg" => "Yetersiz Bakiye");
            }
        }
        return false;
    }

    function renew(Domain $Domain, $period)
    {
        $wsdlfile = "DomOrder.wsdl";
        $arr = array(
            'entityid'   => $this->getDetailsByDomain($Domain->domain, 'entityid'),
            'noofyears'  => '' . ($period / 12) . '',
            'expirydate' => $Domain->dateExp
        );
        $domainHash = array($Domain->domain => $arr);
        $arrParms = array($domainHash, 'NoInvoice');
        $response = $this->call("renewDomain", $wsdlfile, $arrParms);

        if ($response[$Domain->domain]["status"] == "Success") {
            $Domain->dateExp = $this->getDetailsByDomain($Domain->domain, 'endtime');
            $Domain->update();
            return array("st" => true);
        } elseif ($response[$Domain->domain]["status"] == "InvoicePaid") {
            return array("st" => false, "msg" => "Yetersiz Bakiye");
        } elseif ($this->error) {
            return array("st" => false, "msg" => $this->error);
        }
    }

    function refresh(Domain $Domain)
    {
        $res = $this->getDetailsByDomain($Domain->domain);
        if ($res['domainname'] != $Domain->domain) {
            return array(
                'st'  => false,
                'msg' => 'Alan adı bilgilerine ulaşılamadı'
            );
        }
        $Domain->dateReg = $res["creationtime"];
        $Domain->dateExp = $res["endtime"];
        $Domain->saveDNS($res["ns1"], $res["ns2"], $res["ns3"], $res["ns4"]);
        // update contacts
        $contacts = array(
            'registrant' => $res['registrantcontactid'],
            'tech'       => $res['techcontactid'],
            'admin'      => $res['admincontactid'],
            'billing'    => $res['billingcontactid']
        );
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
        }
        $Domain->update();
        return array('st' => true);
    }

    function moveWebsite($websiteName, $newCustomerId)
    {
        $wsdlfile = "Website.wsdl";
        $arrParms = array($websiteName, $newCustomerId);
        $res = $this->call("moveWebsite", $wsdlfile, $arrParms);

        if ($res['domain']['status'] == 'Success') {
            return array('st' => true);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }

    }

    function setDNS(Domain $Domain, $ns1, $ns2, $ns3, $ns4)
    {
        $wsdlfile = "DomOrder.wsdl";
        $nsHash = array();
        for ($i = 1; $i < 5; $i ++) {
            if (${'ns' . $i}) {
                $nsHash['ns' . $i] = ${'ns' . $i};
            }
        }
        $arrParms = array($this->getDetailsByDomain($Domain->domain, 'entityid'), $nsHash);
        $response = $this->call("modifyNameServer", $wsdlfile, $arrParms);
        if ($response['status'] == "Success") {
            return array("st" => true);
        } else {
            if ($this->error) {
                if (strstr(strtolower($this->errorMsg), "ame value for new and old nameservers.")) {
                    return array("st" => true);
                }
                if (strstr(strtolower($this->errorMsg), "should be in proper format")) {
                    return array('st' => false, 'msg' => 'NS formatı hatalı');
                }
                if (strstr(strtolower($this->errorMsg), "is not a valid nameserver")) {
                    return array('st' => false, 'msg' => 'NS kayıtlı değil');
                }
                if (strstr(strtolower($this->errorMsg), "duplicate values for nameserver")) {
                    return array('st' => false, 'msg' => 'NS ler farklı olmalıdır');
                }
                if (strstr(strtolower($this->errorMsg), "here should be atleast 2 nameserver")) {
                    return array('st' => false, 'msg' => 'En az 2 NS girmelisiniz');
                }

                return array('st' => false, 'msg' => $this->errorMsg);
            }
        }
    }

    function lock(Domain $Domain)
    {
        $wsdlfile = "Order.wsdl";
        $arrParms = array($this->getDetailsByDomain($Domain->domain, 'entityid'));
        $res = $this->call('setCustomerLock', $wsdlfile, $arrParms);
        if ($res['status'] == 'Success') {
            return array('st' => true);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }
    }

    function unlock(Domain $Domain)
    {
        $wsdlfile = "Order.wsdl";
        $arrParms = array($this->getDetailsByDomain($Domain->domain, 'entityid'));
        $res = $this->call('removeCustomerLock', $wsdlfile, $arrParms);
        if ($res['status'] == 'Success') {
            return array('st' => true);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
        }
    }


    function getAuthCode(Domain $Domain)
    {
        $res = $this->getDetailsByDomain($Domain->domain);
        if ($res['domainname'] != $Domain->domain) {
            return array(
                'st'  => false,
                'msg' => 'Alan adı bilgilerine ulaşılamadı'
            );
        }
        return array('st' => true, 'authcode' => $res["domsecret"]);
    }

    function listDomains($pageNum, $numOfRecordPerPage, $showChildOrders = "TRUE")
    {

        $wsdlfile = "DomOrder.wsdl";
        $currentStatus = "Active";
        $arrParms = array(
            $orderId,
            $resellerId,
            $customerId,
            $showChildOrders,
            $classKey,
            $currentStatus,
            $description,
            $ns,
            $contactName,
            $contactCompanyName,
            $creationDTRangStart,
            $creationDTRangEnd,
            $endTimeRangStart,
            $endTimeRangEnd,
            $numOfRecordPerPage,
            $pageNum,
            $orderBy
        );
        $response = $this->call("list", $wsdlfile, $arrParms);

        return $response;
    }

    function addCustomer($Client)
    {
        $wsdlfile = "Customer.wsdl";
        $pass = core::generateCode(7) . randomCode(3, '1234567890');
        $email = $Client->email;
        $Contact = $this->rebuildContact($Client->getDefaultContact());
        unset($Contact[2]);

        $arrParms = array_merge(array($email, $pass), $Contact, array("", "", "tr", "", ""));
        $response = $this->call("signUp", $wsdlfile, $arrParms);

        if ($this->error) {
            if (strstr($this->errorMsg, 'already')) {
                return $this->getCustomerIdByEmail($email);
            } else {
                return array('st' => false, 'msg' => $this->errorMsg);
            }
        } else {
            return array('st' => true, 'customerID' => $response[0]);
        }
    }

    function getCustomerIdByEmail($email)
    {
        $wsdlfile = "Customer.wsdl";
        $response = $this->call("getCustomerId", $wsdlfile, array($email));
        if ($this->error) {
            return array('st' => false, 'msg' => $this->errorMsg);
        } else {
            return array('st' => true, 'customerID' => $response[0]);
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
        $wsdlfile = "DomContact.wsdl";
        $arrParms = array_merge(
            $this->rebuildContact($Contact),
            array($this->getCustomerID(new Client($Contact->clientID)), "Contact")
        );
        $response = $this->call("addContact", $wsdlfile, $arrParms);

        if ($response != "" && ! $this->error) {
            $registrarID = intval($response[0]);
            $Contact->setRegistrarID($this->moduleID, $registrarID);
            return array('st' => true, 'id' => $registrarID);
        } else {
            return array('st' => false, 'msg' => $this->errorMsg);
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

        $calling_code = getCountry($Contact->country, 'calling_code');
        $con = array(
            $Contact->name,
            $Contact->company,
            $Contact->email,
            $Contact->address,
            '',
            '',
            $Contact->city,
            $Contact->state,
            $Contact->country,
            $Contact->zip,
            $calling_code,
            str_replace(' ', '', $Contact->phone),
            $calling_code,
            str_replace(' ', '', $Contact->fax)
        );

        /*    if ($con->country == 'UK') {
                $con->country = 'GB';
            }*/
        return $con;

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

    function getDetailsByDomain($domain, $field = '')
    {
        $wsdlfile = "DomOrder.wsdl";
        $option[0] = "All";
        $arrParms = array($domain, $option);
        $res = $this->call("getDetailsByDomain", $wsdlfile, $arrParms);
        if ($field != "") {
            return $res[$field];
        } else {
            return $res;
        }
    }

    function getContactDetails($domain, $contact_type)
    {
        return $this->getDetailsByDomain($domain, $contact_type);
    }

    function getContactTypes()
    {
        return array('registrant' => 'Registrant', 'admin' => 'Admin', 'tech' => 'Technical', 'billing' => 'Billing');
    }

    function errorAnalyse($data)
    {
        foreach ($data as $key => $value) {
            if (is_string($key) and $key == "faultstring") {
                $error = array();
                $counter = 1;
                $start = 0;

                while ($pos = strpos($value, "#~#", $start)) {
                    $error[$counter] = substr($value, $start, $pos - $start);
                    $start = $pos + strlen("#~#");
                    $counter = $counter + 1;
                }
                $this->errorCode = $error[1];
                $this->errorClass = $error[2];
                $this->errorMsg = $error[3];
                $this->errorLevel = $error[4];
                $this->error = true;
                vzrlog($this->errorMsg, 'error', 'DirectI');
            }
        }
    }

    function getCustomerID(Client $Client)
    {
        $method = $this->get('account_method');

        if ($method == 'seperate') {
            $customerID = $Client->getExtra('directi_customerID', true);
            if (! $customerID) {
                $ret = $this->addCustomer($Client);
                if (! $ret['st']) {
                    // debug log
                    return false;
                } else {
                    $customerID = $ret['customerID'];
                    $Client->setExtra('directi_customerID', $customerID);
                }
            }
            return $customerID;
        } else {
            return parent::get('customerID');
        }

    }


} // end of module class
