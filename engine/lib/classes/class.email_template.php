<?php
class Email_template extends base
{

    public $templateID;
    public $type;
    public $title;
    public $language;
    public $fromName;
    public $fromEmail;
    public $copyTo;
    public $subject;
    public $body;
    public $sms;
    public $variables;
    public $dateAdded;
    public $dateUpdated;

    public $replaces;

    static $types = array(
        "domain"  => "##DomainEmailTemplates##",
        "finance" => "##FinanceEmailTemplates##",
        "support" => "##SupportEmailTemplates##"
    );

    function Email_template($id = 0)
    {
        global $config;
        $this->replaces = array("vurl" => $config["HTTP_HOST"]);

        $this->db_table = "settings_email_templates";
        $this->ID_field = "templateID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'templateID',
            'type',
            'title',
            'language',
            'fromName',
            'fromEmail',
            'copyTo',
            'subject',
            'body',
            'sms',
            'variables',
            'dateAdded',
            'dateUpdated'
        );
        if ($id) {
            $this->load($id);
        }
    }

    function loadObjects()
    {
        if ($this->ticketID) {
            $this->Ticket = new Ticket($this->ticketID);
            $this->clientID = $this->Ticket->clientID;
        }
        if ($this->domainID) {
            $this->Domain = new Domain($this->domainID);
            $this->orderID = $this->Domain->orderID;
        }
        if ($this->billID) {
            $this->OrderBill = new OrderBill($this->billID);
            if ($this->OrderBill->orderID) {
                $this->orderID = $this->OrderBill->orderID;
            } else {
                $this->clientID = $this->OrderBill->clientID;
            }
        }
        if ($this->paymentID) {
            $this->Payment = new Payment($this->paymentID);
            $this->clientID = $this->Payment->clientID;
        }

        if ($this->orderID) {
            $this->Order = new Order($this->orderID);
            $this->Service = new Service($this->Order->serviceID);
            $this->clientID = $this->Order->clientID;
        }
    }

    function send()
    {
        global $config;
        $this->loadObjects();
        $this->Client = new Client($this->clientID);

        //echo $this->textreplace($this->body); die;

        if ($this->sms != '') {
            core::send_sms($this->textreplace($this->sms), $this->Client->getCell());
        }
        $signature = getSetting('compinfo_mail_signature');
        if ($signature != '') {
            $this->body .= '<br><br>' . nl2br($signature);
        }

        $data = array(
            'subject'   => $this->textreplace($this->subject),
            'to'        => $this->Client->email,
            'body'      => $this->textreplace($this->body),
            'fromName'  => $this->fromName,
            'fromEmail' => $this->fromEmail
        );
        if ($this->copyTo != '') {
            $data['cc'] = $this->copyTo;
        }
        Queue::createJob('sendmail')->setParams($data)->update()->start();
    }

    function textreplace($text)
    {
        global $config;
        if ($this->Order) {
            $this->body = str_replace('{$Order_status}', '##OrderDetails%{$Order_status}##', $this->body);
            foreach ($this->Order as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date')) {
                    $v = formatDate($v, 'date', 'long');
                }
                if ($k == 'paycurID') {
                    $v = $config["CURTABLE"][$v]['symbol'];
                }
                $text = str_replace('{$Order_' . $k . '}', $v, $text);
            }

            $this->Order->loadAttrs();
            foreach ($this->Order->attrs as $k => $v) {
                $text = str_replace('{$Order_' . $k . '}', $v['value'], $text);
            }

            if ($this->Order->serverID) {
                $Server = new Server($this->Order->serverID);
                $text = str_replace('{$Order_server_name}', $Server->serverName, $text);
                $text = str_replace('{$Order_server_ip}', $Server->mainIp, $text);
                $text = str_replace('{$Order_server_hostname}', $Server->hostname, $text);
            }


        }

        if ($this->Service) {
            foreach ($this->Service as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date')) {
                    $v = formatDate($v, 'datetime', 'long');
                }
                $text = str_replace('{$Service_' . $k . '}', $v, $text);
            }
        }
        if ($this->Ticket) {
            foreach ($this->Ticket as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date')) {
                    $v = formatDate($v, 'datetime', 'long');
                }
                $text = str_replace('{$Ticket_' . $k . '}', $v, $text);
            }
        }
        if ($this->Domain) {
            foreach ($this->Domain as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date') && is_numeric($v)) {
                    $v = formatDate($v, 'date', 'long');
                }
                $text = str_replace('{$Domain_' . $k . '}', $v, $text);
            }
        }
        if ($this->OrderBill) {
            foreach ($this->OrderBill as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date') && is_numeric($v)) {
                    $v = formatDate($v, 'date', 'long');
                }
                if ($k == 'paycurID') {
                    $v = $config["CURTABLE"][$v]['symbol'];
                }
                $text = str_replace('{$OrderBill_' . $k . '}', $v, $text);
            }
        }
        if ($this->Payment) {
            foreach ($this->Payment as $k => $v) {
                if (is_object($v)) {
                    continue;
                }
                if (strstr($k, 'date') && is_numeric($v)) {
                    $v = formatDate($v, 'date', 'long');
                }
                if ($k == 'paycurID') {
                    $v = $config["CURTABLE"][$v]['symbol'];
                }
                $text = str_replace('{$Payment_' . $k . '}', $v, $text);
            }
        }

        foreach ($this->Client as $k => $v) {
            if ($k == 'password') {
                continue;
            }
            if (strstr($k, 'date') && is_numeric($v)) {
                $v = formatDate($v, 'datetime', 'long');
            }
            $text = str_replace('{$Client_' . $k . '}', $v, $text);
        }

        foreach ($this->replaces as $k => $v) {
            $text = str_replace('{$' . $k . '}', $v, $text);
        }
        //language
        $text = preg_replace_callback('/##(.+?)##/', '_compile_lang', $text);
        return $text;
    }

    static function getEmails($type = array())
    {
        global $db;
        $sql = "SELECT * FROM settings_email_templates";
        if ($type) {
            $sql .= " WHERE type IN ('" . implode("','", $type) . "')";
        }
        $emails = $db->query($sql . " ORDER BY type", SQL_KEY, 'templateID');
        return $emails;
    }


} // end of class 





