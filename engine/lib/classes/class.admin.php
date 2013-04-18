<?php

class Admin extends base
{

    public $adminID;
    public $type;
    public $status;
    protected $adminPassword;
    public $adminEmail;
    public $adminMsn;
    public $adminName;
    public $adminNick;
    public $adminTitle;
    public $dateAdded;
    public $dateUpdated;
    public $dateLogin;
    public $ipLogin;


    function admin($id = 0)
    {
        $this->db_table = "admins";
        $this->ID_field = "adminID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'adminID',
            'type',
            'status',
            'adminEmail',
            'adminMsn',
            'adminName',
            'adminNick',
            'adminTitle',
            'dateAdded',
            'dateUpdated',
            'dateLogin',
            'ipLogin'
        );
        if ($id) {
            $this->load($id);
        }
    }


    static function authenticate($email, $password)
    {
        global $db;
        $email = substr(sanitize($email, true, true), 0, 40);
        $password = substr(sanitize($password, true, true), 0, 40);

        $sql = "SELECT adminID FROM admins
            WHERE adminPassword = '" . core::encrypt(
            $password
        ) . "' AND adminEmail = '" . $email . "' AND status = 'active'";
        $adminID = $db->query($sql, SQL_INIT, "adminID");
        return $adminID;
    }

    function load($id = "")
    {
        global $db;
        parent::load($id);
        unset($this->adminPassword);
        $this->deps = array();
        $deps = (array)$db->query("SELECT depID FROM admin_deps WHERE adminID = " . $this->adminID, SQL_ALL);
        foreach ($deps as $d) {
            $this->deps[] = $d['depID'];
        }
        $sql = "SELECT ase.value,ast.setting FROM admin_setting_types ast
            INNER JOIN admin_settings ase ON ase.settingID = ast.settingID WHERE ase.adminID = " . $this->adminID;
        $this->settings = (array)$db->query($sql, SQL_KEY, 'setting');
    }

    function setSetting($setting, $value)
    {
        if ($this->settings[$setting] == $value) {
            return false;
        }
        global $db;
        $settingID = $db->query(
            "SELECT settingID FROM admin_setting_types WHERE setting = '" . $setting . "'",
            SQL_INIT,
            'settingID'
        );
        if (! $settingID) {
            $db->query("INSERT INTO admin_setting_types (setting,grp) VALUES ('" . $setting . "','hidden')");
            $settingID = $db->lastInsertID();
        }
        $sql = "INSERT INTO admin_settings (settingID,adminID,value)
            VALUES (" . $settingID . "," . $this->adminID . ",'" . $value . "')
            ON DUPLICATE KEY UPDATE value = '" . $value . "'";
        $db->query($sql);
        $this->settings[$setting] = $value;
    }

    function syncSetting($setting, $value)
    {

        if ($this->settings[$setting] != '' && $value == '') {
            $value = $this->settings[$setting];
        } elseif ($value != '') {
            $this->setSetting($setting, $value);
        }
    }

    function update()
    {
        $exclude = array();
        if ($this->adminPassword == "") {
            $exclude = array("adminPassword");
        } else {
            $this->adminPassword = core::encrypt($this->adminPassword);
        }
        parent::update($exclude);
    }

    function login($cookie)
    {
        $this->loadPagePrivs();
        $this->set('dateLogin', time());
        $this->set('ipLogin', getenv(REMOTE_ADDR));
        if ($cookie) {
            setcookie("_vae", core::encrypt($this->adminEmail), time() + 60 * 60 * 24 * 365 * 10, "/");
            setcookie("_vap", $this->get('adminPassword'), time() + 60 * 60 * 24 * 365 * 10, "/");
        } else {
            self::delCookies();
        }
    }

    function getPassword()
    {
        return core::decrypt($this->get('adminPassword'));
    }

    function setPassword($password)
    {
        $this->set('adminPassword', core::encrypt($password));
    }

    function loadPagePrivs()
    {
        global $db;
        $this->privs = $this->getPagePrivs();
    }

    function setPagePrivs($data)
    {
        global $db;
        $db->query("DELETE FROM admin_privs WHERE adminID = " . $this->adminID);
        foreach ($data["priv"] as $pageID => $bits) {
            $priv[$pageID] = 0;
            foreach ($bits as $bit => $v) {
                setbit($priv[$pageID], $bit);
            }
            $db->query(
                "INSERT INTO admin_privs (adminID,pageID,priv) VALUES (" . $this->adminID . "," . $pageID . "," . $priv[$pageID] . ")"
            );
        }
    }

    function setPagePriv($pageID, $bit, $val)
    {
        global $db;
        $priv = $db->query(
            "SELECT priv FROM admin_privs WHERE pageID = " . $pageID . " AND adminID = " . $this->adminID,
            SQL_INIT,
            'priv'
        );
        if (! $priv) {
            $priv = 0;
        }
        if ($val == 'true') {
            priv_setbit($priv, $bit);
        } else {
            priv_clearbit($priv, $bit);
        }
        $sql = "INSERT INTO admin_privs (adminID,pageID,priv) VALUES (" . $this->adminID . "," . $pageID . ",'" . $priv . "')
            ON DUPLICATE KEY UPDATE priv = '" . $priv . "'";
        $db->query($sql);
    }

    function getPagePrivs()
    {
        global $db;
        $privs = $db->query("SELECT * FROM admin_privs WHERE adminID = " . $this->adminID, SQL_ALL, "pageID");
        return $privs;
    }

    function updateDeps($deps)
    {
        global $db;
        $db->query("DELETE FROM admin_deps WHERE adminID = " . $this->adminID);
        foreach ((array)$deps as $depID) {
            $db->query("INSERT INTO admin_deps (adminID,depID) VALUES (" . $this->adminID . "," . $depID . ")");
        }
    }

    function getDeps()
    {
        if (! $this->deps) {
            global $db;
            $this->deps = $db->query("SELECT depID FROM admin_deps WHERE adminID = " . ADMINID, SQL_KEY, 'depID');
        }
        return $this->deps;
    }

    function getAvatarName()
    {
        return md5('AD' . $this->adminID . "-" . $this->dateAdded) . ".jpg";
    }

    function getQreps()
    {
        global $db;
        $qreps = $db->query("SELECT * FROM admin_qreps WHERE adminID = " . $this->adminID . " ORDER BY reply", SQL_ALL);
        return $qreps;
    }

    static function loginWithCookie()
    {

        $email = core::decrypt($_COOKIE['_vae']);
        $pass = core::decrypt($_COOKIE['_vap']);

        $adminID = self::authenticate($email, $pass);
        if ($adminID) {
            $_SESSION["vadmin"] = new Admin($adminID);
            $_SESSION["vadmin"]->login('1');
            return true;
        } else {
            self::delCookies();
            return false;
        }
    }


    static function emailExists($email)
    {
        global $db;
        $check = $db->query("SELECT adminID FROM admins WHERE adminEmail = '" . $email . "'", SQL_INIT, 'adminID');
        return $check;
    }

    static function delCookies()
    {
        setcookie('_vap', '', time() - 3600, "/");
        setcookie('_vae', '', time() - 3600, "/");
    }

    static function getPropertyByAdminId($adminID, $property)
    {
        global $db;

        $sql = "SELECT `" . $property . "` FROM admins WHERE adminID = " . (int)$adminID;
        $value = $db->query($sql, SQL_INIT, $property);
        return $value;
    }

    function destroy()
    {
        global $db;
        $tables = array(
            'announcements',
            'chat',
            'dc_files',
            'kb_entries',
            'payments',
            'tickets',
            'ticket_attachments',
            'ticket_responses'
        );
        foreach ($tables as $tbl) {
            $db->query("UPDATE " . $tbl . " SET adminID = 0 WHERE adminID = " . (int)$this->adminID);
        }

        $db->query("DELETE FROM admin_deps WHERE adminID = " . (int)$this->adminID);
        $db->query("DELETE FROM admin_privs WHERE adminID = " . (int)$this->adminID);
        $db->query("DELETE FROM admin_qreps WHERE adminID = " . (int)$this->adminID);
        $db->query("DELETE FROM admin_settings WHERE adminID = " . (int)$this->adminID);

        $db->query("DELETE FROM admins WHERE adminID = " . (int)$this->adminID);


    }

} // end of class admin





