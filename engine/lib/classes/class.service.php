<?php
class Service extends base
{
    public $serviceID;
    public $groupID;
    public $serverID;
    public $paycurID;
    public $moduleID;
    public $templateID;
    public $settingID;
    public $status;
    public $type;
    public $service_name;
    public $seolink;
    public $provisionType;
    public $moduleCmd;
    public $addon;
    public $description;
    public $details;
    public $notifyOnOrderDepID;
    public $setup, $setup_discount;
    public $file_cats;
    public $has_support;
    public $expires;
    public $dateAdded;
    public $dateUpdated;
    public $sfOrder;

    public $curSymbol;
    public $priceOptions;

    public static $provisionTypes = array("auto" => "##Automatic##", "manual" => "##Manual##");

    function service($id = "")
    {
        $this->db_table = "services";
        $this->ID_field = "serviceID";
        $this->db_members = array(
            'serviceID',
            'groupID',
            'serverID',
            'paycurID',
            'moduleID',
            'templateID',
            'settingID',
            'status',
            'type',
            'service_name',
            'seolink',
            'provisionType',
            'moduleCmd',
            'addon',
            'description',
            'details',
            'notifyOnOrderDepID',
            'setup',
            'setup_discount',
            'file_cats',
            'has_support',
            'expires',
            'dateAdded',
            'dateUpdated',
            'sfOrder',
            'rowOrder'
        );
        $this->db_checkboxes = array('has_support');

        parent::__construct($id);
    }

    function load($id = "")
    {
        parent::load($id);
        //$this->curSymbol = ($this->paycurID) ? core::getCurrencyById($this->paycurID) : "";
        $this->serviceType = ($this->groupID == 10) ? "domain" : "service";
        if ($this->type) {
            $this->{$this->type} = true;
        }
    }

    function loadServer()
    {
        if (! $this->serverID) {
            return false;
        }
        $this->Server = new Server($this->serverID);
        return $this->Server;
    }

    function create()
    {
        parent::create();
        $this->paycurID = MAIN_CUR_ID;
    }

    function bindModule($moduleID)
    {
        if (! $moduleID) {
            return false;
        }
        global $db;
        $db->query("DELETE FROM service_attrs WHERE serviceID = " . $this->serviceID);

        $this->set('settingID', 5);

        $modConfig = $this->getModuleConfig();

        foreach ((array)$modConfig['srvc'] as $setting => $data) {
            if ($this->addon == '1' && ! $data->addon) {
                continue;
            }
            if ($data->stype && $data->stype != $this->type) {
                continue;
            }
            $this->addAttr($setting, 'module', $data->default);
        }

        if ($this->addon == '1') {
            return;
        }

        $attrs = explode(',', $modConfig['sys']['attrs']->value);
        foreach ($attrs as $setting) {
            if (! $setting) {
                continue;
            }
            $this->addAttr($setting, 'custom-locked');
        }
        foreach ($modConfig['install'] as $setting => $obj) {
            $this->addAttr($moduleID . '_' . $setting, 'custom-locked');
        }

    }

    function getModuleConfig()
    {
        if (! $this->moduleID) {
            return false;
        }
        $MODULE = Module::getInstance($this->moduleID);
        if (! $MODULE) {
            return false;
        }
        if ($this->serverID) {
            $MODULE->setServer($this->serverID);
        }
        return $MODULE->getModuleConfig();
        //return Module::getConfig($this->moduleID);
    }

    function getAttributes($forCart = false)
    {
        global $db;
        $sql = "SELECT sat.*,sa.source FROM service_attrs sa INNER JOIN service_attr_types sat ON sa.setting = sat.setting
            WHERE sa.source != 'module' AND sa.serviceID = " . $this->serviceID;
        if ($forCart) {
            $sql .= " AND sat.valueBy = 'client'";
        }
        $attrs = $db->query($sql, SQL_KEY, 'setting');
        foreach ($attrs as $attr => $data) {
            $ret[$attr] = Setting::type($data['type'])->lab($data['label'])->desc($data['description'])->width(
                $data['width']
            )->source($data['source']);
            if ($attr == 'password') {
                $ret[$attr]->cmd('setPassword');
            }
            $ret[$attr]->settingID = $data['settingID'];
            $ret[$attr]->valueBy = $data['valueBy'];
            $ret[$attr]->options = $data['options'];
            $ret[$attr]->build();
        }
        return (array)$ret;
    }

    function getAttributeTypes()
    {
        global $db;
        $sql = "SELECT setting,label FROM service_attr_types";
        $types = $db->query($sql, SQL_KEY, 'setting');
        return $types;

    }

    function addAttr($setting, $source = 'custom', $default = '')
    {
        global $db;
        $sql = "INSERT INTO service_attrs (serviceID,source,setting,value)
            VALUES (" . $this->serviceID . ",'" . $source . "','" . $setting . "','" . $default . "')
            ON DUPLICATE KEY UPDATE setting = '" . $setting . "', value = '" . $default . "'";
        $db->query($sql);
    }

    function delAttr($setting)
    {
        global $db;
        $sql = "DELETE FROM service_attrs WHERE serviceID = " . $this->serviceID . " AND setting = '" . $setting . "'";
        $db->query($sql);
    }


    function getAddons($orderPayType = "")
    {
        global $db;
        $sql = "SELECT s.* FROM services s
                INNER JOIN service_groups sg ON s.groupID = sg.groupID
                LEFT JOIN service_groups sg2 ON sg.groupID = sg2.parentID
                LEFT JOIN service_price_options spo ON spo.serviceID = s.serviceID  
            WHERE s.status = 'active'
                AND s.paycurID = " . $this->paycurID . "
                AND (s.moduleID = '" . $this->moduleID . "' OR s.moduleID = '')
                AND s.addon = '1' 
                AND (sg.groupID = " . $this->groupID . " OR sg2.groupID = " . $this->groupID . ")
            GROUP BY s.serviceID";

        $addons = $db->query($sql, SQL_ALL);
        return $addons;
    }

    function hasAttr($setting)
    {
        global $db;
        $attrID = $db->query(
            "SELECT attrID FROM service_attrs WHERE serviceID = " . $this->serviceID . " AND setting = '" . $setting . "'",
            SQL_INIT,
            'attrID'
        );
        return $attrID;
    }


    function getServers()
    {
        if ($Service->moduleID === '') {
            return;
        }
        global $db;
        $servers = $db->query(
            "SELECT * FROM servers WHERE status = 'active' AND moduleID = '" . $this->moduleID . "'",
            SQL_ALL
        );
        return $servers;
    }

    function getNameServers()
    {
        $this->loadServer();
        if (! $this->Server) {
            return array(getSetting('domains_ns1'), getSetting('domains_ns2'));
        }
        $this->Server->loadSettings();
        return array($this->Server->settings['ns1'], $this->Server->settings['ns2']);
    }

    function getExpirationTime($ts)
    {
        if ($this->expires != 'never') {
            $expires_p = substr($this->expires, - 1, 1);
            $expires_a = str_replace($expires_p, '', $this->expires);
            $expires = addDate($ts, $expires_a, $expires_p);
            return $expires;
        } else {
            return 0;
        }
    }

// finance functions

    function addPriceOption($period, $price)
    {
        global $db;
        if (! intval($price) || ! intval($period)) {
            return;
        }
        $sql = "INSERT service_price_options (serviceID,period,price) VALUES (" . $this->serviceID . "," . $period . ",'" . $price . "')
            ON DUPLICATE KEY UPDATE price = '" . $price . "'";
        $db->query($sql);
        // Eğer bu tek ise default yap:
        $cnt = $db->query(
            "SELECT COUNT(period) AS cnt FROM service_price_options WHERE serviceID = " . $this->serviceID,
            SQL_INIT,
            'cnt'
        );
        if ($cnt == 1) {
            $db->query(
                "UPDATE service_price_options SET `default` = '1' WHERE serviceID = " . $this->serviceID . " AND period = '" . $period . "'"
            );
        }
    }

    function removePriceOption($period)
    {
        global $db;
        // bu default muydu?
        $isdef = $db->query(
            "SELECT `default` FROM service_price_options WHERE serviceID = " . $this->serviceID . " AND period = " . $period,
            SQL_INIT,
            'default'
        );
        $sql = "DELETE FROM service_price_options WHERE period = " . $period . " AND serviceID = " . $this->serviceID;
        $db->query($sql);
        if ($isdef == '1') {
            $db->query(
                "UPDATE service_price_options SET `default` = '1' WHERE serviceID = " . $this->serviceID . "  ORDER BY period ASC LIMIT 1"
            );
        }
    }


    function getPriceOptions()
    {
        global $db, $vars;
        $sql = "SELECT (price-discount) AS price,period FROM service_price_options WHERE serviceID = " . $this->serviceID . " ORDER BY period ASC";
        $this->priceOptions = $db->query($sql, SQL_KEY, "period");
        if (! $this->priceOptions) {
            return false;
        }
        return $this->priceOptions;
    }

    function getDefaultPriceOptionPeriod()
    {
        global $db;
        $period = $db->query(
            "SELECT period FROM service_price_options WHERE serviceID = " . $this->serviceID . " AND `default` = '1'",
            SQL_INIT,
            'period'
        );
        return $period;
    }

    function getSinglePriceOption($period, $return = "")
    {
        global $db;
        if ($period > - 1) {
            $where = " AND po.period = $period";
            $queryType = SQL_INIT;
        } else {
            $queryType = SQL_ALL;
        }
        $key = ($return != "") ? $return : "period";
        $sql = "SELECT * FROM service_price_options spo
                INNER JOIN price_options po ON spo.optionID = po.optionID 
            WHERE spo.serviceID = " . $this->serviceID . " $where ORDER BY po.period ASC";
        $result = $db->query($sql, $queryType, $key);
        if ($period > - 1 && $return != "") {
            return $result;
        } else {
            return $result;
        }
    }

    function updatePriceOptions($options)
    {
        global $db;
        if (! $options) {
            return;
        }
        foreach ($options as $period => $data) {
            $sql = "UPDATE service_price_options SET price = '" . $data['price'] . "', discount = '" . $data['discount'] . "', `default` = '" . $data['default'] . "'
                WHERE period = " . $period . " AND serviceID = " . $this->serviceID;
            $db->query($sql);
        }
    }

    function getFiles()
    {
        if (! $this->file_cats) {
            return array();
        }
        $cats = explode(',', $this->file_cats);
        if (! $cats) {
            return array();
        }
        $files = array();
        foreach ($cats as $catID) {
            $files += Download::getCategoryFiles($catID);
        }
        return $files;
    }

    function getAvatarName()
    {
        return md5($this->serviceID . "-" . $this->dateAdded) . ".jpg";
    }

    function duplicate()
    {
        global $db;
        $oserviceID = $this->serviceID;
        $attrs = $this->getAttributes();
        $db->query("INSERT INTO services (serviceID) VALUES (NULL)");
        $this->serviceID = $db->lastInsertID();
        $this->service_name .= ' (Kopya)';
        $this->update();
        $this->setRowOrder();

        $attrs = (array)$db->query("SELECT * FROM service_attrs WHERE serviceID = " . $oserviceID, SQL_ALL);
        foreach ($attrs as $a) {
            $this->addAttr($a['setting'], $a['source'], $a['value']);
        }

        $options = (array)$db->query("SELECT * FROM service_price_options WHERE serviceID = " . $oserviceID, SQL_ALL);
        foreach ($options as $o) {
            $this->addPriceOption($o['period'], $o['price']);
        }
    }

    function setRowOrder()
    {
        global $db;

        $maxro = $db->query(
            "SELECT MAX(rowOrder) AS maxro FROM services WHERE groupID = " . $this->groupID,
            SQL_INIT,
            'maxro'
        );
        $this->set('rowOrder', ($maxro + 1));
    }


    function destroy()
    {
        global $db;
        $db->query("DELETE FROM service_attrs WHERE serviceID = " . $this->serviceID);
        $db->query("DELETE FROM service_price_options WHERE serviceID = " . $this->serviceID);
        $db->query("DELETE FROM services WHERE serviceID = " . $this->serviceID);
        if ($this->groupID == 10) {
            $db->query("DELETE FROM domain_extensions WHERE serviceID = " . $this->serviceID);
        }
    }


////////// Static

    static function newInstance($serviceID, $load = true)
    {
        $Service = new self();
        $Service->serviceID = $serviceID;
        if ($load) {
            $Service->load($serviceID);
        }
        return $Service;
    }

    static function moveRow($table, $IDstr, $id, $dir, $groupID)
    {
        global $db;
        $rowOrder = $db->query("SELECT rowOrder FROM $table WHERE $IDstr = '$id'", SQL_INIT, "rowOrder");
        if ($dir == "down") {
            $row = $db->query(
                "SELECT * FROM $table WHERE rowOrder > $rowOrder AND groupID = $groupID AND addon = '0' ORDER BY rowOrder ASC LIMIT 1",
                SQL_INIT
            );
        } else {
            $row = $db->query(
                "SELECT * FROM $table WHERE rowOrder < $rowOrder AND groupID = $groupID AND addon = '0' ORDER BY rowOrder DESC LIMIT 1",
                SQL_INIT
            );
        }
        if ($row && $row['rowOrder']) {
            $db->query("UPDATE $table SET rowOrder = '" . $row["rowOrder"] . "' WHERE $IDstr = $id");
            $db->query("UPDATE $table SET rowOrder = '$rowOrder' WHERE $IDstr = " . $row["$IDstr"]);
            /*        debug("UPDATE $table SET rowOrder = '".$row["rowOrder"]."' WHERE $IDstr = $id");
                    debug("UPDATE $table SET rowOrder = '$rowOrder' WHERE $IDstr = ".$row["$IDstr"]); */

        }
    }


} // end of class service
