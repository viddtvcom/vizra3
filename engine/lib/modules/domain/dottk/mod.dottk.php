<?php

class mod_dottk extends DomainModule
{
    private $debug = true;
    private $baseurl = 'https://secure.dot.tk/partners/partnerapi.tk';

    private function call($operation, $params)
    {
        $params['function'] = $operation;
        $params['email'] = $this->get("username");
        $params['password'] = $this->get("password");

        //$resp = file($c) or $error = true;

        $resp = core::curlpost($this->baseurl, $params);

        if ($resp['st'] == false) {
            vzrlog('DotTK bağlantı hatası:' . $c, 'error');
        }

        $xml = new XmlToArray($resp['data']);
        $data = $xml->createArray();

        debuglog($data, 'dottk');

        $params['password'] = str_replace($this->get("password"), "******", $c);

        if (false && $this->get('debug') == '1') {
            vzrlog($c, 'info', 'DotTK (request)');
            vzrlog(print_r($resp, 1), 'info', 'DotTK (response)');
        }

        return $data['dottk'];
    }

    function register(Domain $Domain, $period)
    {
        $arrParms["domainname"] = $Domain->domain;
        $arrParms["lengthofregistration"] = $period / 12;
        $arrParms["nameserver"] = $Domain->ns1;
        $arrParms["nameserver1"] = $Domain->ns2;
        $resp = $this->call("register", $arrParms);
        $data = $resp['partner_registration'][0];

        if ($data['status'] == 'REGISTERED') {
            $dateExp = $data['expirationdate'];
            $Domain->dateExp = mktime(0, 0, 0, substr($dateExp, 4, 2), substr($dateExp, 6, 2), substr($dateExp, 0, 4));
            $Domain->dateReg = mktime(
                0,
                0,
                0,
                substr($dateExp, 4, 2),
                substr($dateExp, 6, 2),
                    substr($dateExp, 0, 4) - $arrParms["lengthofregistration"]
            );
            $Domain->update();
            //$this->updateWhoisInfo();
            return array("st" => true);
        } else {
            return array("st" => false, "msg" => $resp);
        }
    }

    function renew(Domain $Domain, $period)
    {
        $arrParms["domainname"] = $Domain->domain;
        $arrParms["lengthofregistration"] = $period / 12;
        $resp = $this->call("renew", $arrParms);
        $data = $resp['partner_renew'][0];

        if ($data['status'] == 'RENEWED') {
            $dateExp = $data['expirationdate'];
            $Domain->dateExp = mktime(0, 0, 0, substr($dateExp, 4, 2), substr($dateExp, 6, 2), substr($dateExp, 0, 4));
            $Domain->update();
            return array("st" => true);
        } else {
            return array("st" => false, "msg" => $resp);
        }
    }

    function setDNS(Domain $Domain, $ns1, $ns2, $ns3, $ns4)
    {
        $arrParms = array();
        $arrParms["domainname"] = $Domain->domain;
        $arrParms['nameserver'] = $ns1;
        $arrParms['nameserver2'] = $ns2;
        $arrParms['nameserver3'] = $ns3;
        $arrParms['nameserver4'] = $ns4;
        $resp = $this->call("updatedns", $arrParms);
        if ($resp['status'] == 'NOT OK') {
            if (strstr($resp['reason'], "is not a valid name")) {
                return array("st" => false, "msg" => "NS formati hatali");
            } else {
                return array("st" => false, "msg" => $resp['reason']);
            }
        }

        $data = $resp['partner_updatedns'][0];

        if ($data['status'] == 'NAMESERVERS UPDATED') {
            return array("st" => true);
        }
    }

    function getContactTypes()
    {
        return array();
    }


} // end of module class
