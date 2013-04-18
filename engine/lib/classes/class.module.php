<?php

class Module
{
    public $moduleID;
    static public $types = array('service', 'domain', 'payment', 'system');

    function Module($moduleID)
    {
        $this->moduleID = $moduleID;
    }

    static function loadModuleFile($moduleID)
    {
        global $config;

        foreach (self::$types as $folder) {
            $dir = $config['LIB_DIR'] . 'modules' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $moduleID . DIRECTORY_SEPARATOR;
            if (file_exists($dir)) {
                foreach (new DirectoryIterator($dir) as $file) {
                    if (! $file->isDot()) {
                        if (preg_match('/^(mod.)([a-z0-9]*)(.php)$/', $file->getFilename(), $matches)) {
                            require_once($dir . $matches[0]);
                            return 'mod_' . $matches[2];
                        }
                    }

                }
            }
        }
        return false;

    }

    /** @return Module */
    static function getInstance($moduleID)
    {
        if (! $moduleID) {
            return false;
        }
        $class_name = self::loadModuleFile($moduleID);
        if (! $class_name) {
            return false;
        }
        $obj = new $class_name($moduleID);
        return $obj;
    }

    static function getModuleType($moduleID)
    {
        global $config;
        foreach (self::$types as $type) {
            $dir = $config['LIB_DIR'] . 'modules' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $moduleID . DIRECTORY_SEPARATOR;
            if (file_exists($dir)) {
                return $type;
            }
        }
        return false;
    }

    function getModuleConfig()
    {
        return Module::getConfig($this->moduleID);
    }

    static function getConfig($moduleID)
    {
        global $config;
        $type = self::getModuleType($moduleID);

        $sys['status'] = Setting::type('combobox')->lab('Durum')->opt('inactive', '##Inactive##')->opt(
            'active',
            '##Active##'
        );

        if ($type == 'payment') {
            $sys['convert'] = Setting::type('db')->set('setting', 'convertto')
                    ->set('_db_table', 'settings_currencies')
                    ->set('_db_id', 'curID')
                    ->set('_db_title', 'code')
                    ->build()->lab('Kur')->desc('(Modüle gelen ödemeler bu kura çevrilir)');

            /*        $sys['min_amount'] = Setting::type('textbox')->lab('Min. Miktar')
                                                                 ->desc('(Bu modulün aktif olabilmesi için gerekli en az tutar)')
                                                                 ->width(100);
                    $sys['max_amount'] = Setting::type('textbox')->lab('Maks. Miktar')
                                                                 ->desc('(Bu modulün aktif olabilmesi için gerekli en fazla tutar)')
                                                                 ->width(100);*/

            $sys['commission_rate'] = Setting::type('textbox')->lab('Komisyon')
                    ->predesc('%')
                    ->desc('(% Komisyon Miktarı)')
                    ->width(50)->val(0);


        }

        $dir = $config['LIB_DIR'] . 'modules' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $moduleID . DIRECTORY_SEPARATOR;
        if (! file_exists($dir . 'config.php')) {
            return false;
        }
        require($dir . 'config.php');
        foreach ((array)$srvc as $key => $item) {
            $srvc2[$moduleID . '_' . $key] = $item;
        }
        $sys['debug'] = Setting::type('checkbox')->lab('Debug Mod')->desc(
            'Bu modül işlemlerini log penceresinde görmek için tıklayınız'
        );
        return array(
            'type' => (array)$type,
            'sys' => (array)$sys,
            'srvc' => (array)$srvc2,
            'srvr' => (array)$srvr,
            'install' => (array)$install,
            'notes' => (array)$notes
        );
    }

    function setOrder(Order $Order)
    {
        //$Order->Client = new Client($Order->clientID);
        $Order->loadClient();
        $this->Order = $Order;
        $this->stype = $Order->Service->type;
        $this->setServer($Order->serverID);
        $Order->loadAttrs()->loadAddonAttrs();
        foreach ($Order->attrs as $key => $data) {
            $modkey = str_replace($this->moduleID . '_', '', $key);
            $this->attrs[$modkey] = (is_numeric(
                $data['value']
            )) ? $data['value'] + $Order->addon_attrs[$key]['value'] : $data['value'];
        }
        return $this;
    }

    function setServer($serverID)
    {
        $this->Server = new Server($serverID);
    }

    function setAttr($set, $val, $add = true)
    {
        if (is_numeric($val)) {
            $val -= $this->Order->addon_attrs[$this->moduleID . '_' . $set]['value'];
        }
        $this->attrs[$set] = $val;
        $this->Order->setAttr($set, $val, $add);
    }

    function setStatus($status)
    {
        $config = $this->getModuleConfig();
        $config = $config['install'];

        foreach ($config as $setting => $obj) {
            if ($status == 'active') {
                $obj->moduleID = $this->moduleID;
                $obj->install($this->moduleID . '_' . $setting);
            } else {
                $obj->uninstall($this->moduleID . '_' . $setting);
            }
        }
    }

    function set($setting, $value)
    {
        global $db;
        $sql = "INSERT INTO settings_modules (moduleID,module_type,setting,value)
            VALUES ('" . $this->moduleID . "','" . $this->type . "','" . $setting . "','" . $value . "')
            ON DUPLICATE KEY UPDATE value = '" . $value . "', module_type = '" . $this->type . "'";
        //$sql = "UPDATE settings_modules SET value = '".$value."' WHERE setting = '".$setting."' AND moduleID = '".$this->moduleID."'";
        $db->query($sql);
    }

    function get($setting)
    {
        global $db;
        $sql = "SELECT setting,value,encrypted FROM settings_modules WHERE moduleID = '" . $this->moduleID . "' AND setting = '" . $setting . "'";
        $data = $db->query($sql, SQL_INIT);
        if ($data['encrypted'] == '1') {
            return core::decrypt($data['value']);
        } else {
            return $data['value'];
        }
    }

    function getSettings()
    {
        global $db;
        $sql = "SELECT setting,value,encrypted FROM settings_modules WHERE moduleID = '" . $this->moduleID . "'";
        $settings = $db->query($sql, SQL_KEY, 'setting');
        foreach ($settings as $key => $data) {
            $this->settings[$key] = trim(($data['encrypted'] == '1') ? core::decrypt($data['value']) : $data['value']);
        }
        return $this->settings;
    }


    static function getModuleList($type)
    {
        global $config;
        $modules = array();
        $dir = $config["LIB_DIR"] . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . $type;
        if (! file_exists($dir)) {
            return false;
        }
        foreach (new DirectoryIterator($dir) as $file) {
            if (! $file->isDot() && $file->isDir()) {
                $name = $file->getFilename();
                if ($name == ".svn") {
                    continue;
                }
                $modConfig = Module::getConfig($name);
                if ($modConfig) {
                    $ret[$name] = $modConfig['sys']['title']->value;
                }
            }
        }
        return $ret;
    }


    static function getActiveModules($type)
    {
        global $db;
        $active = array();
        $all = self::getModuleList($type);

        $sql = "SELECT moduleID,value FROM settings_modules WHERE setting = 'status'";

        $status = $db->query($sql, SQL_KEY, 'moduleID');
        foreach ($all as $moduleID => $title) {
            if ($status[$moduleID] == 'active') {
                $active[$moduleID] = $title;
            }
        }

        return $active;
    }


    static function getModuleTitles($type = '')
    {
        global $db;
        $sql = "SELECT value,moduleID FROM settings_modules WHERE setting = 'title'";
        if ($type) {
            $sql .= " AND module_type = '" . $type . "'";
        }
        $titles = $db->query($sql, SQL_KEY, 'moduleID');
        return $titles;
    }

}


class PaymentModule extends Module
{
    public $type = 'payment';
}

class ServiceModule extends Module
{
    public $type = 'service';
}

class DomainModule extends Module
{
    public $type = 'domain';
}

class SystemModule extends Module
{
    public $type = 'system';
}
