<?php
class Payment extends base
{

    public $paymentID;
    public $clientID;
    public $moduleID;
    public $adminID;
    public $paymentStatus;
    public $datePayed;
    public $amount;
    public $paycurID;
    public $xamount;
    public $description;
    public $dateAdded;
    public $dateUpdated;


    function __construct($id = 0)
    {
        $this->db_table = "payments";
        $this->ID_field = "paymentID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'paymentID',
            'clientID',
            'moduleID',
            'adminID',
            'paymentStatus',
            'datePayed',
            'amount',
            'paycurID',
            'xamount',
            'description',
            'dateAdded',
            'dateUpdated'
        );
        if ($id) {
            $this->load($id);
        }
    }

    function create($genid)
    {
        parent::create($genid);
    }

    function update()
    {
        global $config;
        $this->xamount = $this->amount * $config['CURTABLE'][$this->paycurID]['ratio'];
        parent::update();
    }

    function approve($approverID = 0)
    {
        if ($this->paymentStatus == 'paid') {
            return false;
        }
        global $db;
        $this->datePayed = time();
        $this->paymentStatus = 'paid';
        $this->adminID = $approverID;
        $this->update();

        /* odemeye bagli borclari odendi olarak isaretle */
        $sql = "SELECT billID FROM order_bills WHERE paymentID = " . $this->paymentID . " AND status = 'unpaid'";
        $bills = (array)$db->query($sql, SQL_ALL);
        foreach ($bills as $b) {
            $OB = new OrderBill($b['billID']);
            $OB->pay()->update();
        }

        /* queue'da bekleyen isleri calistir */
        $sql = "SELECT jobID FROM queue WHERE status = 'pending-payment' AND paymentID = " . $this->paymentID . " ORDER BY jobID ASC";
        $jobs = (array)$db->query($sql, SQL_KEY, 'jobID');
        foreach ($jobs as $jobID) {
            $Queue = new Queue($jobID);
            $Queue->process();
        }
        $this->sendmail();

        /* modul komisyonu varsa onu dus */
        if ($this->moduleID != '') {
            $MODULE = Module::getInstance($this->moduleID);
            if ($MODULE != false) {
                $rate = $MODULE->get('commission_rate');
                if ($rate > 0) {
                    $_rate = $rate / 100;
                    $commission = ($this->amount - ($rate / 100)) * ($rate / 100);
                    $commission = ($this->amount / (1 + $_rate)) * $_rate;

                    Payment::addPayment(
                        $this->clientID,
                        $this->moduleID,
                        'paid',
                        $this->datePayed,
                        - $commission,
                        $this->paycurID,
                            $this->paymentID . ' nolu ödemeye ait komisyon'
                    );
                }
            }
        }

    }

    function sendmail()
    {
        $EMT = new Email_template(3);
        $EMT->paymentID = $this->paymentID;
        $ret = $EMT->send();
    }

    static function destroy($paymentID)
    {
        global $db;
        $db->query("UPDATE order_bills SET paymentID = 0 WHERE paymentID = " . $paymentID);
        $db->query("DELETE FROM payments WHERE paymentID = " . $paymentID);
    }

    static function addPayment(
        $clientID,
        $moduleID,
        $paymentStatus,
        $datePayed,
        $amount,
        $paycurID,
        $description = '',
        $sendmail = true
    ) {
        global $config;

        $P = new Payment();
        $P->create(true);
        $P->clientID = $clientID;
        $P->moduleID = $moduleID;
        $P->paymentStatus = $paymentStatus;
        $P->datePayed = $datePayed;
        $P->amount = $amount;
        $P->xamount = $amount * $config['CURTABLE'][$paycurID]['ratio'];
        $P->paycurID = $paycurID;
        $P->description = $description;
        $P->update();
        if ($P->paymentStatus == 'paid') {
            $P->sendmail();
        }
        return $P->paymentID;
    }


} // end of class





