<?php
class Contact extends base
{
    public $contactID;
    public $clientID;
    public $default;
    public $name;
    public $company;
    public $email;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $country;
    public $phone;
    public $fax;
    public $cell;
    public $dateAdded;
    public $dateUpdated;

    function __construct($id = '')
    {
        $this->db_table = "client_contacts";
        $this->ID_field = "contactID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'clientID',
            'default',
            'name',
            'company',
            'email',
            'address',
            'city',
            'state',
            'city',
            'zip',
            'country',
            'phone',
            'fax',
            'cell',
            'dateAdded',
            'dateUpdated'
        );
        $this->fields_allowed = array(
            'name',
            'company',
            'email',
            'address',
            'city',
            'state',
            'city',
            'zip',
            'country',
            'phone',
            'fax',
            'cell'
        );
        $this->db_checkboxes = array('default');
        if ($id) {
            $this->load((int)$id);
        }
    }

    function load($id = "")
    {
        parent::load($id);
        /*    if (VZUSERTYPE != 'admin' && $_SESSION["vclient"]->clientID != $this->clientID) {
                unset($this->contactID);
            } */
    }

    function update2()
    {
        // burada bütün modullerde update olmasi lazim
        $MOD = Module::getInstance('directi');
        $resp = $MOD->updateContact($this);
        return $resp;
    }

    function antiInternational()
    {
        foreach ($this as $k => $v) {
            $this->$k = antiInternational($v);
        }
    }

    function destroy()
    {
        global $db;
        $db->query("DELETE FROM client_contacts WHERE contactID = " . $this->contactID);
    }

    function getRegistrarID($moduleID)
    {
        global $db;
        $sql = "SELECT registrarID FROM domain_contact_registrar WHERE contactID = " . $this->contactID . " AND moduleID = '" . $moduleID . "'";
        $this->registrarID = $db->query($sql, SQL_INIT, 'registrarID');
        return $this->registrarID;
    }

    function setRegistrarID($moduleID, $registrarID)
    {
        global $db;
        $sql = "INSERT INTO domain_contact_registrar (contactID,moduleID,registrarID) VALUES ('" . $this->contactID . "','" . $moduleID . "','" . $registrarID . "')
            ON DUPLICATE KEY UPDATE registrarID = '" . $registrarID . "'";
        $db->query($sql);
    }

    function validate()
    {
        if (! validate_email($this->email)) {
            $errors['email'] = true;
        }

        if (! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ. ]{6,40}$/', $this->name)) {
            $errors['name'] = true;
        }

        if (! preg_match('/^[\/a-zA-ZçÇşŞöÖğĞüÜıİ.,-:0-9 ]{6,50}$/', $this->address)) {
            $errors['address'] = true;
        }
        if (! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ]{3,50}$/', $this->city)) {
            $errors['city'] = true;
        }
        if (! preg_match('/^[a-zA-ZçÇşŞöÖğĞüÜıİ]{3,50}$/', $this->state)) {
            $errors['state'] = true;
        }
        if (! preg_match('/^\d{5}$/', $this->zip)) {
            $errors['zip'] = true;
        }
        if (! preg_match('/^\d{3} \d{3} \d{2} \d{2}$/', $this->phone)) {
            $errors['phone'] = true;
        }
        if (! preg_match('/^\d{3} \d{3} \d{2} \d{2}$/', $this->cell)) {
            $errors['cell'] = true;
        }

        return $errors;
    }

    static function createDefaultClientContact($clientID)
    {
        $CL = new Client($clientID);
        $CO = new Contact();
        $CO->create(true);
        $CO->clientID = $clientID;
        $CO->default = '1';
        $CO->name = $CL->name;
        $CO->company = $CL->company ? $CL->company : 'N/A';
        $CO->email = $CL->email;
        $CO->address = $CL->address;
        $CO->city = $CL->city;
        $CO->state = $CL->state;
        $CO->zip = $CL->zip;
        $CO->country = $CL->country ? $CL->country : 'TR';
        $CO->phone = $CL->phone;
        $CO->cell = $CL->cell;
        $CO->fax = $CL->phone;
        $CO->update();
        return $CO;
    }

    static function getClientContacts($clientID)
    {
        global $db;
        $contacts = $db->query("SELECT * FROM client_contacts WHERE clientID = " . $clientID, SQL_ALL);
        return $contacts;
    }

    static function emailExists($email, $clientID)
    {
        global $db;
        $check = $db->query(
            "SELECT contactID FROM client_contacts WHERE email = '" . $email . "' AND clientID != " . $clientID,
            SQL_INIT,
            'contactID'
        );
        return $check;
    }

    static function getContactIdByRegistrarId($registrarID)
    {
        global $db;
        $contactID = $db->query(
            "SELECT contactID FROM domain_contact_registrar WHERE registrarID = " . $registrarID,
            SQL_INIT,
            'contactID'
        );
        return $contactID;
    }

    static function getRegistrarIdByContactId($contactID)
    {
        global $db;
        $registrarID = $db->query(
            "SELECT registrarID FROM domain_contact_registrar WHERE contactID = " . $contactID,
            SQL_INIT,
            'registrarID'
        );
        return $registrarID;
    }

    static function convertRegistrarData($data)
    {
        foreach ($data as $type => $contactID) {
            $data[$type] = self::getRegistrarIdByContactId($contactID);
        }
        return $data;
    }


} // end of class
