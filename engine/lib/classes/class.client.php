<?php
class Client extends base
{

    public $clientID;
    public $groupID;
    public $type;
    public $status;
    public $autoSuspend;
    public $email;
    public $password;
    public $name;
    public $company;
    public $address, $state, $zip, $city, $country, $phone, $cell, $notes, $fnote;
    public $dateAdded, $dateUpdated, $dateLogin;
    public $ipReg, $ipLogin;
    public $isVip;

    public $contact;

    function client($id = 0)
    {
        $this->db_table = "clients";
        $this->ID_field = "clientID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'groupID',
            'type',
            'status',
            'autoSuspend',
            'email',
            'name',
            'company',
            'address',
            'state',
            'zip',
            'city',
            'country',
            'phone',
            'cell',
            'notes',
            'fnote',
            'dateAdded',
            'dateUpdated',
            'dateLogin',
            'ipReg',
            'ipLogin',
            'isVip',
            'balance'
        );
        $this->fields_allowed = array(
            'name',
            'email',
            'company',
            'address',
            'city',
            'state',
            'zip',
            'country',
            'phone',
            'cell'
        );
        $this->db_checkboxes = array('autoSuspend');
        if ($id) {
            $this->load($id);
        }
    }

    function create($email)
    {
        global $db;
        $this->clientID = generateID($this->db_table, $this->ID_field);

        $sql = "INSERT INTO " . $this->db_table . " ( " . $this->ID_field . ",email," . $this->_dateAdded . " )
             VALUES (" . $this->clientID . ",'" . $email . "',UNIX_TIMESTAMP())";
        $db->query($sql);
        $this->load();

        return $this->clientID;
    }

    function load($id = "")
    {
        parent::load($id);
        //unset($this->password);
    }

    function login()
    {
        $this->set('dateLogin', time());
        $this->set('ipLogin', getenv(REMOTE_ADDR));
    }

    function update()
    {
        $exclude = array();
        if ($this->password == "") {
            $exclude = array("password");
        }
        parent::update($exclude);
        return $this;
    }

    function replace($data)
    {
        global $db;
        parent::replace($data);
        $this->password = ($data["password"]) ? core::encrypt($data["password"]) : "";
        return $this;
    }

    function replaceExtras($data)
    {
        global $db;
        $attrs = $db->query("SELECT attrID,encrypted,visibility FROM attrs", SQL_KEY, 'attrID');
        foreach ($attrs as $attrID => $attr) {
            if (VZUSERTYPE != 'admin' && $attr['visibility'] == 'hidden') {
                continue;
            }
            $value = ($attr['encrypted'] == '1') ? core::encrypt($data['extras'][$attrID]) : $data['extras'][$attrID];
            $sql = "INSERT INTO client_extras (clientID,attrID,value) VALUES (" . $this->clientID . "," . $attrID . ",'" . $value . "')
                ON DUPLICATE KEY UPDATE value = '" . $value . "'";
            $db->query($sql);
        }
        return $this;
    }

    function getPassword()
    {
        return core::decrypt($this->get('password'));
    }

    function setPassword($password)
    {
        $this->set('password', core::encrypt($password));
    }

    function getCell()
    {
        $calling_code = getCountry($this->country, 'calling_code');
        return '+' . $calling_code . str_replace(' ', '', $this->cell);
    }

    function loadExtras($client = false)
    {
        global $db;
        $extras = CustomField::getAttrs($client);

        foreach ($extras as $key => $_extra) {
            if ($_extra->client_type != 'all' && $this->type != $_extra->client_type) {
                unset($extras[$key]);
                continue;
            }
        }

        $sql = "SELECT * FROM client_extras
            WHERE clientID = '" . $this->clientID . "'";
        $values = (array)$db->query($sql, SQL_ALL, 'attrID');

        if (count($extras) > 0) {
            $this->extras = $extras;
            foreach ($values as $key => $value) {
                if (!is_object($this->extras[$key])) {
                    $this->extras[$key] = new stdClass();
                }
                if ($client && $this->extras[$key]->visibility == 'hidden') {
                    unset($this->extras[$key]);
                    continue;
                }
                if ($this->extras[$key]->encrypted == '1') {
                    $value["value"] = core::decrypt($value["value"]);
                }
                $this->extras[$key]->value = $value["value"];
            }
            return $this->extras;
        }
    }

    function getExtra($label, $returnValueOnly = false)
    {
        global $db;
        $sql = "SELECT * FROM client_extras ce
                INNER JOIN attrs a ON a.attrID = ce.attrID
            WHERE ce.clientID = " . $this->clientID . " AND a.label = '" . $label . "'";
        $data = $db->query($sql, SQL_INIT);
        return ($returnValueOnly) ? $data['value'] : $data;
    }

    function setExtra($label, $value)
    {
        global $db;
        $attrID = $db->query("SELECT attrID FROM attrs WHERE label = '" . $label . "'", SQL_INIT, 'attrID');
        $sql = "INSERT INTO client_extras (clientID,attrID,value) VALUES (" . $this->clientID . "," . $attrID . ",'" . $value . "')
            ON DUPLICATE KEY UPDATE value = '" . $value . "'";
        $db->query($sql);
    }


    function getDefaultContact()
    {
        global $db;
        $contactID = $db->query(
            "SELECT contactID FROM client_contacts WHERE `default` = '1' AND clientID = " . $this->clientID,
            SQL_INIT,
            'contactID'
        );
        if ($contactID) {
            $Contact = new Contact($contactID);
            if (! $Contact->validate()) {
                // kontak geçerli
                return $Contact;
            } else {
                // gecersiz kontak, sil gitsin
                $Contact->destroy();
            }
        }
        return Contact::createDefaultClientContact($this->clientID);
    }

    function getAvatarName()
    {
        return md5('CL' . $this->clientID . "-" . $this->dateAdded) . ".jpg";
    }

    function getClientTickets($condition, $status, $limit)
    {
        global $db;
        $limit = ($limit > 0) ? "LIMIT $limit" : "";
        $sql = "SELECT t.*,a.adminName
                FROM tickets t
                INNER JOIN ticket_responses tr ON t.ticketID = tr.ticketID
                    AND tr.dateAdded = (SELECT MAX(dateAdded) FROM ticket_responses WHERE ticketID = t.ticketID AND private = '0')
                LEFT JOIN admins a ON a.adminID = tr.adminID
                WHERE t.clientID = " . $this->clientID . " AND t.status $condition '$status' ORDER BY t.dateUpdated DESC $limit";
        //debug($db->query($sql,SQL_ALL));
        return $db->query($sql, SQL_ALL);
    }

    function getClientOrders($t)
    {
        global $db;
        $sql = "SELECT o.*,s.service_name FROM orders o
                INNER JOIN services s ON s.serviceID = o.serviceID 
            WHERE o.clientID = " . $this->clientID . " AND o.parentID = 0 AND o.status != 'deleted'
            ";

        if ($t == 'wod') {
            $sql .= " AND s.groupID != 10";
        } elseif ($t == 'd') {
            $sql .= " AND s.groupID = 10";
        }
        $sql .= " ORDER BY o.title ASC";
        $orders = $db->query($sql, SQL_ALL);
        return $orders;
    }

    function getClientDomains()
    {
        global $db;
        $sql = "SELECT * FROM domains d INNER JOIN orders o ON d.orderID = o.orderID
            WHERE o.clientID = " . $this->clientID . " ORDER BY o.dateAdded DESC";
        $domains = $db->query($sql, SQL_ALL);
        return $domains;
    }

    function getClientBills($status = "", $dateOffset = "")
    {
        global $db;
        if ($status != "") {
            $where = "AND ob.status = '" . $status . "'";
        }
        if ($dateOffset != "") {
            $dateOffset = 'AND ob.dateDue < UNIX_TIMESTAMP(ADDDATE(CURDATE(),INTERVAL ' . $dateOffset . ' DAY))';
        }
        $sql = "SELECT ob.*,o.title FROM orders o
                INNER JOIN order_bills ob ON ob.orderID = o.orderID
            WHERE o.clientID = " . $this->clientID . " $where
                $dateOffset
            ORDER BY ob.dateDue DESC";
        $bills = $db->query($sql, SQL_ALL);
        return $bills;
    }

    function getClientPayments($st = array())
    {
        global $db;
        if (count($st)) {
            $where = "AND p.paymentStatus IN ('" . implode("','", $st) . "')";
        }
        $sql = "SELECT * FROM payments p
            WHERE p.clientID = " . $this->clientID . " $where ORDER BY datePayed DESC";
        $payments = $db->query($sql, SQL_ALL);
        return $payments;
    }

    function getBalance()
    {
        global $db, $config;

        $sql = "SELECT ob.*,o.title, ob.dateDue AS dateAdded
            FROM order_bills ob  
                LEFT JOIN orders o ON (ob.orderID = o.orderID AND o.status != 'pending-payment')
            WHERE (ob.clientID = " . $this->clientID . " OR o.clientID = " . $this->clientID . ")";

        $bills = $db->query($sql, SQL_ALL);

        $sql = "SELECT p.* FROM payments p
            WHERE p.clientID = " . $this->clientID . " AND p.paymentStatus = 'paid'";
        $payments = $db->query($sql, SQL_ALL);

        $all = array_merge($bills, $payments);
        $total = 0;
        foreach ($all as $a) {


            if ($a['billID'] > 0) {
                $total -= $a['xamount'];
            } else {
                $total += $a['xamount'];
            }
        }

        return $total;
    }

    function getDiscountRate()
    {
        global $db;
        $sql = "SELECT discount_rate FROM client_groups WHERE groupID = '" . $this->groupID . "'";
        $discount_rate = $db->query($sql, SQL_INIT, 'discount_rate');

        return $discount_rate;
    }

    function sendEmailFromTemplate($templateID)
    {
        $EMT = new Email_template($templateID);
        $EMT->clientID = $this->clientID;
        $ret = $EMT->send();
        return $ret;
    }

    function notify()
    {
        global $config;
        $notify = getSetting('notify_newclient', true);
        if ($notify[0] == '1') {
            $body = "Yeni müşteri kaydı (" . $this->name . "), detaylar için <a href='" . $config['HTTP_HOST'] . "acp/?p=311&clientID=" . $this->clientID . "'>buraya</a> tıklayınız";
            core::send_mail('Yeni müşteri kaydı', getSetting('notify_notifymail'), $body);
        }
        if ($notify[1] == '1') {
            core::send_sms("Yeni müşteri kaydı (" . $this->name . ")", getSetting('notify_notifycell'));
        }
        if ($notify[2] == '1') {
            $body = "Yeni müşteri kaydı (" . $this->name . "), detaylar için: " . $config['HTTP_HOST'] . "acp/?p=311&clientID=" . $this->clientID;
            core::send_msn($body, getSetting('notify_notifymsn'));
        }
    }

    function doesQualifyForSupport()
    {
        global $db;
        if (getSetting('tickets_limited') != '1') {
            return true;
        }
        $mode = getSetting('tickets_limit_scope');
        if ($mode == 'active') {
            $check = $db->query(
                "SELECT orderID FROM orders WHERE status = 'active' AND clientID = " . $this->clientID,
                SQL_INIT,
                'orderID'
            );
            return $check;
        } else {
            $sql = "SELECT orderID FROM orders o
                INNER JOIN services s ON o.serviceID = s.serviceID
                WHERE o.status = 'active' AND s.has_support = '1'";
            $check = $db->query($sql, SQL_INIT, 'orderID');
            return $check;
        }
    }


    static function emailExists($email)
    {
        global $db;
        $check = $db->query("SELECT clientID FROM clients WHERE email = '" . $email . "'", SQL_INIT, 'clientID');
        //debug($check,1);
        return $check;
    }

    static function authenticate($email, $password)
    {
        global $db;
        $sql = "SELECT clientID,password FROM clients WHERE email = '" . sanitize(
            $email
        ) . "' AND status = 'active' LIMIT 1";
        $cl = $db->query($sql, SQL_INIT);
        if (core::encrypt($password) == $cl["password"]) {
            return $cl["clientID"];
        } else {
            return false;
        }
    }

    static function sendPassword($email)
    {
        $Client = new Client(Client::getClientIdByEmail($email));
        $EMT = new Email_template(9);
        $EMT->replaces['Client_password'] = core::decrypt($Client->get('password'));
        $EMT->clientID = $Client->clientID;
        $ret = $EMT->send();
    }

    static function getClientIdByEmail($email)
    {
        global $db;
        $clientID = $db->query("SELECT clientID FROM clients WHERE email = '" . $email . "'", SQL_INIT, 'clientID');
        return $clientID;
    }


    function destroy()
    {
        global $db;
        $orders = $db->query("SELECT orderID FROM orders WHERE clientID = " . $this->clientID, SQL_KEY, 'orderID');
        foreach ($orders as $orderID) {
            $Order = new Order($orderID);
            $Order->destroy();
        }
        $tickets = $db->query("SELECT ticketID FROM tickets WHERE clientID = " . $this->clientID, SQL_KEY, 'ticketID');
        foreach ($tickets as $ticketID) {
            $Ticket = new Ticket($ticketID);
            $Ticket->destroy();
        }
        $db->query("DELETE FROM payments WHERE clientID = " . $this->clientID);
        $db->query("DELETE FROM order_bills WHERE clientID = " . $this->clientID);
        $db->query("DELETE FROM clients WHERE clientID = " . $this->clientID . " LIMIT 1");
        $db->query("DELETE FROM client_contacts WHERE clientID = " . $this->clientID);
        $db->query("DELETE FROM client_extras WHERE clientID = " . $this->clientID);

    }


} // end of class client
