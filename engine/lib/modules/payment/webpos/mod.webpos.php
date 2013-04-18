<?php

class mod_webpos extends PaymentModule
{
    public $INSTALLMENT;
    public $CCNUMBER;
    public $CCEXP;
    public $CCCVC2;
    public $TOTAL;

    public $USERNAME;
    public $PASSWORD;
    public $CLIENTID;
    public $POS_URL;


    function process($params)
    {
        $this->getSettings();
        $this->CLIENTID = $this->settings['merchantID'];
        $this->USERNAME = $this->settings['username'];
        $this->PASSWORD = $this->settings['password'];

        //return array('st'=>true);

        if ($this->settings['use3d'] == '1') {

        } else {
            $this->CCNUMBER = $params['cardNumber'];
            $this->CCEXP = $params['cardExpMonth'] . '/' . $params['cardExpYear'];
            $this->CCCVC2 = $params['cardCV2'];
            $this->TOTAL = number_format($params['amount'], 2, '.', '');
            $ret = $this->ccpos();
        }
        return $ret;
    }


    function ccpos()
    {
        $this->xml = file_get_contents(dirname(__FILE__) . "/webpos.xml");
        $this->xml = preg_replace("/\[(\w+)\]/e", "\$this->\\1", $this->xml);


        /*    $this->xml = urlencode($this->xml);
            $len = strlen("$this->xml") + 5;
            $headers =  "POST /servlet/cc5ApiServer HTTP/1.1\n".
                                    "Host: ".$this->settings['posUrl']."\n".
                                    "Content-Type: application/x-www-form-urlencoded\n".
                                    "Content-Length: $len\n\n".
                                    "DATA=$this->xml";
            $fp = @fsockopen("ssl://".trim($this->settings['posUrl']), 443, $errno, $errstr);
            if (!$fp) {
                $result['st'] = -1;
                $result['msg'] = "Banka ile iletişim kurulamadı.";
            }
            @fputs($fp, $headers);
            $ret = '';
            $ret = @fread($fp, 8192);*/

        $this->xml = "DATA=<?xml version=\"1.0\" encoding=\"ISO-8859-9\"?>" . $this->xml;
        $url = "https://" . $this->settings['posUrl'] . "/servlet/cc5ApiServer";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml);
        $ret = curl_exec($ch);

        $pure_xml = substr($ret, strpos($ret, '<CC5Response>'));
        $xml_array = $this->makeXMLTree($pure_xml);

        switch ($xml_array[CC5Response][Response]) {
            case 'Approved' :
                $result['st'] = 1;
                break;
            case 'Declined' :
                $result['st'] = 0;
                $result['msg'] = $xml_array[CC5Response][ErrMsg]; // ."<br>".$xml_array[CC5Response][Extra];
                break;
            case 'Error' :
                $result['st'] = - 1;
                $result['msg'] = $xml_array[CC5Response][ErrMsg]; // ."<br>".$xml_array[CC5Response][Extra];
                break;
        }
        flush();
        @fclose($fp);
        $result['msg'] = $result['st'] . " * " . $result['msg'];
        return $result;
    }


    function do3dspayment($data, $amount)
    {
        global $db;
        $this->tds_used = true;
        $this->XID = $data["xid"];
        $this->ECI = $data["eci"];
        $this->CAVV = $data["cavv"];
        $this->CCNUMBER = $data["md"];
        $this->CPC = 13;
        $this->setTOTAL($amount);
        $this->ccpos();
        $this->cc_sonuc();

        if ($this->RET == 1) {
            $ret["status"] = "ok";
            $db->query("UPDATE cc SET 3dsApproved = 'yes' WHERE uid = '" . $_POST["kkid"] . "' LIMIT 1");
        } else {
            $ret["status"] = "failed";
            $ret["msg"] = $this->MSG1 . "<br>" . $this->MSG2 . "<br>" . $this->MSG3 . "<br>" . $this->MSG4;
            $ret["msg"] = $this->MSG4;
        }
        return $ret;
    }


    function tdsparams($amount)
    {
        $this->getSettings();
        $tds["3dsUrl"] = $settings['3dsUrl'];
        $tds["clientid"] = $settings['merchantID'];
        $tds["amount"] = $amount;
        $tds["okUrl"] = "http://colinux.vizra.net/panel/debug.php";
        $tds["failUrl"] = "http://colinux.vizra.net/panel/debug.php";
        $tds["oid"] = "";
        $tds["rnd"] = microtime();
        $tds["storetype"] = "3d_pay";
        $tds["lang"] = "tr";
        $tds["hash"] = base64_encode(
            pack(
                'H*',
                sha1(
                    $tds["clientid"] . $tds["oid"] . $tds["amount"] . $tds["okUrl"] . $tds["failUrl"] . $tds["rnd"] . $settings['3dsStoreKey']
                )
            )
        );

        return $tds;
    }

    static function tds_checkhash($hashparams, $hashparamsval, $hashparam, $mdStatus)
    {
        global $config;

        if (! (in_array($mdStatus, array('1', '2', '3', '4')))) {
            return false;
        }

        $paramsval = "";
        $index1 = 0;
        $index2 = 0;

        while ($index1 < strlen($hashparams)) {
            $index2 = strpos($hashparams, ":", $index1);
            $vl = $_POST[substr($hashparams, $index1, $index2 - $index1)];
            if ($vl == null) {
                $vl = "";
            }
            $paramsval = $paramsval . $vl;
            $index1 = $index2 + 1;
        }
        $hashval = $paramsval . $config["3DS_STOREKEY"];
        $hash = base64_encode(pack('H*', sha1($hashval)));

        /*    echo "paramsval: $paramsval  hashparamsval:$hashparamsval <br />";
            echo "hashparam: $hashparam  hash:$hash <br /><br />";*/

        //sh::debuglog("paramsval: $paramsval  hashparamsval:$hashparamsval","neweb");
        //sh::debuglog("hashparam: $hashparam  hash:$hash","neweb");

        if ($paramsval != $hashparamsval || $hashparam != $hash) {
            return false;
        } else {
            return true;
        }
    }

    function makeXMLTree($data)
    {
        $output = array();
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $values, $tags);
        xml_parser_free($parser);
        $hash_stack = array();
        foreach ($values as $key => $val) {
            switch ($val['type']) {
                case 'open':
                    array_push($hash_stack, $val['tag']);
                    break;
                case 'close':
                    array_pop($hash_stack);
                    break;
                case 'complete':
                    array_push($hash_stack, $val['tag']);
                    eval("\$output['" . implode($hash_stack, "']['") . "'] = \"{$val['value']}\";");
                    array_pop($hash_stack);
                    break;
            }
        }
        return $output;
    }

} // end of class
