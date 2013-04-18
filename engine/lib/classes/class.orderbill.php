<?php
class OrderBill extends base
{

    public $billID;
    public $parentID = 0;
    public $orderID;
    public $clientID;
    public $paymentID;
    public $status = 'unpaid';
    public $type = 'recurring';
    public $amount;
    public $paycurID;
    public $xamount;
    public $description;
    public $mail_count;
    public $dateStart;
    public $dateEnd;
    public $dateDue;
    public $datePayed = 0;

    public $discount = 0;


    function OrderBill($id = "")
    {
        $this->db_table = "order_bills";
        $this->ID_field = "billID";
        $this->db_members = array(
            'parentID',
            'orderID',
            'clientID',
            'paymentID',
            'status',
            'type',
            'amount',
            'paycurID',
            'xamount',
            'description',
            'mail_count',
            'dateStart',
            'dateEnd',
            'dateDue',
            'datePayed'
        );
        if ($id) {
            $this->load($id);
        }
    }

    function create()
    {
        global $db, $config;
        $this->billID = generateID($this->db_table, $this->ID_field);

        $sql = "INSERT INTO order_bills (billID,parentID,orderID,status,type,amount,paycurID,dateDue,dateStart,dateEnd)
            VALUES (" . $this->billID . "," . $this->parentID . ",
                    '" . $this->orderID . "',
                    '" . $this->status . "',
                    '" . $this->type . "',
                    '" . $this->amount . "',
                    '" . $this->paycurID . "',
                    '" . $this->dateDue . "','" . $this->dateStart . "','" . $this->dateEnd . "')";
        $db->query($sql);
        $this->load();
        if ($db->success) {
            return $this->billID;
        }
    }

    function update()
    {
        global $config;
        $this->xamount = $this->amount * $config['CURTABLE'][$this->paycurID]['ratio'];
        parent::update();
    }

    function dateStart($date)
    {
        $this->dateStart = $date;
        return $this;
    }

    function dateEnd($date)
    {
        $this->dateEnd = $date;
        return $this;
    }

    function dateDue($date)
    {
        $this->dateDue = $date;
        return $this;
    }

    function amount($amount)
    {
        global $config;
        $amount = $amount * (1 - $this->discount);
        $this->amount = $amount;
        $this->xamount = $amount * $config['CURTABLE'][$this->paycurID]['ratio'];
        return $this;
    }

    function pay($paymentID = 0)
    {
        $this->status = 'paid';
        $this->datePayed = time();
        if ($paymentID) {
            $this->paymentID = $paymentID;
        }

        if (false && $this->type == 'recurring') {
            $this->createNext();
        }
        return $this;
    }

    function updateOrderDateEnd()
    {
        global $db;
        $sql = "UPDATE orders SET dateEnd = " . $this->dateEnd . " WHERE orderID = " . $this->orderID . " LIMIT 1";
        $db->query($sql);
    }

    function createNext()
    {
        $NOB = new OrderBill();
        $NOB->orderID = $this->orderID;
        $Order = new Order($this->orderID);
        $NOB->parentID = $this->billID;
        $NOB->paycurID = $Order->paycurID;
        $NOB->amount = $Order->price;
        $NOB->dateStart($this->dateEnd)->dateDue($this->dateEnd)->dateEnd(
            addDate($this->dateEnd, $Order->period, 'm')
        )->create();
        $Order->set('dateEnd', $NOB->dateStart);
    }

    function sendmail()
    {
        $EMT = new Email_template(12);
        $EMT->billID = $this->billID;
        $ret = $EMT->send();
        $this->set('mail_count', ++$this->mail_count);
    }

    function checkPrevious()
    {
        if ($this->clientID) {
            return false;
        }
        global $db;
        $sql = "SELECT billID FROM order_bills WHERE status = 'unpaid' AND dateStart < " . $this->dateStart . " AND orderID = " . $this->orderID;
        $this->previous_unpaid = $db->query($sql, SQL_INIT, 'billID');
        return $this->previous_unpaid;
    }


    static function delete($billID)
    {
        global $db;
        $db->query("DELETE FROM order_bills WHERE billID = " . $billID . " LIMIT 1");
    }

    static function sendbill($billID)
    {
        $EM = new Email_template(12);
        $EM->billID = $billID;
        $EM->send();
    }


} // end of class
