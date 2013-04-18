<?php
class Server extends base
{

    var $serverID;
    var $moduleID;
    var $status;
    var $serverName;
    var $mainIp;
    var $hostname;
    var $username;
    var $password;
    var $dateAdded;
    var $dateUpdated;

    function Server($id = "")
    {
        $this->db_table = "servers";
        $this->ID_field = "serverID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = explode(
            ",",
            "serverID,moduleID,status,serverName,mainIp,hostname,username,password,dateAdded,dateUpdated"
        );
        if ($id) {
            $this->load($id);
        }
    }

    function load($id = "")
    {
        global $db;
        parent::load($id);
        if (! $this->serverID) {
            return false;
        }
        $this->password = core::decrypt($this->password, ENCRYPT_SALT);

        $settings = $db->query(
            "SELECT setting,value,encrypted FROM server_settings WHERE serverID = " . (int)$this->serverID,
            SQL_KEY,
            'setting'
        );
        foreach ($settings as $key => $data) {
            $this->attrs[$key] = ($data['encrypted'] == '1') ? core::decrypt($data['value']) : $data['value'];
        }
    }

    function update()
    {
        $this->password = core::encrypt($this->password, ENCRYPT_SALT);
        parent::update();
    }

    function getModuleConfig()
    {
        return Module::getConfig($this->moduleID);
    }

    function getSetting($key)
    {
        global $db;
        return $db->query(
            "SELECT value FROM server_settings WHERE setting = '$key' AND serverID = " . $this->serverID,
            SQL_INIT,
            'value'
        );
    }

    function setSetting($key, $val)
    {
        global $db;
        $sql = "INSERT INTO server_settings (serverID,setting,value) VALUES (" . $this->serverID . ",'" . $key . "','" . $val . "') ON DUPLICATE KEY UPDATE
                value = '" . $val . "'";
        $db->query($sql);
    }

    function loadSettings()
    {
        global $db;
        $this->settings = $db->query(
            "SELECT value,setting FROM server_settings WHERE serverID = " . $this->serverID,
            SQL_KEY,
            'setting'
        );
    }

    function loadModule()
    {
        $this->Module = Module::getInstance($this->moduleID);
        $this->Module->setServer($this->serverID);
        return $this->Module;
    }

    function moduleQueueCmd($cmd, $data = array())
    {
        $data['serverID'] = $this->serverID;
        $params = array_merge(array('cmd' => $cmd), (array)$data);
        Queue::createJob('servercmd')->setParams($params)
                ->update()
                ->start();
    }

    function moduleRunCmd($ocmd, $data = array())
    {
        $this->loadModule();
        if (! $data['nolog']) {
            vzrlog('<' . $ocmd . '> çalıştırılıyor...', 'info', $this->Module->Server->serverName, $this->orderID);
        }
        $cmd = 'cmd_' . $ocmd;
        $cmd = $ocmd;
        if (method_exists($this->Module, $cmd)) {
            $result = $this->Module->$cmd($data);
            if ($result['st']) {
                if (! $data['nolog']) {
                    vzrlog(
                        '<' . $ocmd . '> işlem başarılı...',
                        'info',
                        $this->Module->Server->serverName,
                        $this->orderID
                    );
                }
            } else {
                vzrlog(
                    '<' . $ocmd . '> işleminde hata: ' . $result['msg'],
                    'error',
                    $this->Module->Server->serverName,
                    $this->orderID
                );
            }
            return array('st' => $result['st'], 'msg' => $result['msg']);
        } else {
            return array('st' => false, 'msg' => 'module_method_doesnot_exist');
        }

    }

    function destroy()
    {
        global $db;
        $db->query("UPDATE orders SET serverID = 0 WHERE serverID = " . (int)$this->serverID);
        $db->query("UPDATE services SET serverID = 0 WHERE serverID = " . (int)$this->serverID);

        $db->query("DELETE FROM server_probes WHERE serverID = " . (int)$this->serverID);
        $db->query("DELETE FROM server_settings WHERE serverID = " . (int)$this->serverID);
        $db->query("DELETE FROM servers WHERE serverID = " . (int)$this->serverID);

    }


} // end of class server
