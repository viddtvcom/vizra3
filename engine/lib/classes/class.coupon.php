<?php
class Coupon extends base
{

    public $couponID;
    public $code;
    public $type;
    public $amount;
    public $services = array();
    public $dateExpires;
    public $dateAdded;

    public $active;


    function __construct($id = 0)
    {
        $this->db_table = "coupons";
        $this->ID_field = "couponID";
        $this->_dateAdded = "dateAdded";
        //$this->_dateUpdated = "dateUpdated";
        $this->db_members = array('code', 'type', 'amount', 'services', 'dateExpires', 'dateAdded',);
        if ($id) {
            $this->load($id);
        }
    }

    function load($id = 0)
    {
        parent::load($id);
        if ($this->couponID) {
            $this->services = explode(',', $this->services);
            if (! $this->dateExpires || $this->dateExpires > time()) {
                $this->active = true;
            }
        }
    }

    function loadByCode($code)
    {
        global $db;
        $couponID = $db->query("SELECT couponID FROM coupons WHERE code = '" . $code . "'", SQL_INIT, 'couponID');
        if (! $couponID) {
            return false;
        }
        $this->load($couponID);
        return true;
    }

    function update()
    {
        $this->services = implode(',', $this->services);
        parent::update();
    }


    function destroy()
    {
        global $db;
        $db->query("DELETE FROM coupons WHERE couponID = " . $this->couponID);
    }


} // end of class





