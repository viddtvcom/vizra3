<?php
class Setting extends base
{
    public $settingID;
    public $groupID;
    public $moduleID;
    public $valueBy;
    public $setting;
    public $label;
    public $type; // textbox, checkbox, combobox, db, textarea, hidden
    public $options;
    public $description;
    public $width;
    public $height;
    public $encrypted = '0';
    public $validation;
    public $validation_info;

    public $value;
    public $depends;
    public $group;
    public $cmd;
    public $source = 'module';
    public $addon = false;
    public $default;
    public $stype;

    static $types = array(
        'textbox' => 'Textbox',
        'textarea' => 'Textarea',
        'checkbox' => 'Evet/Hayır',
        'combobox' => 'Combobox',
        'db' => 'Veritabanı',
        'hidden' => 'Gizli'
    );


    function __construct($id = 0)
    {
        $this->type = $type;
        $this->db_table = "service_attr_types";
        $this->ID_field = "settingID";

        $this->db_members = array(
            'groupID',
            'moduleID',
            'valueBy',
            'setting',
            'label',
            'type',
            'options',
            'description',
            'width',
            'height',
            'encrypted',
            'validation',
            'validation_info'
        );
        if ($id) {
            $this->load($id);
        }
    }

    /** @return Setting */
    function lab($lab)
    {
        $this->label = $lab;
        return $this;
    }

    /** @return Setting */
    function opt($key, $val)
    {
        $this->options[$key] = $val;
        return $this;
    }

    /** @return Setting */
    function val($val)
    {
        $this->value = $val;
        return $this;
    }

    /** @return Setting */
    function desc($client, $admin = '')
    {
        $this->description = $client;
        $this->desc_admin = $admin;
        return $this;
    }

    /** @return Setting */
    function predesc($desc)
    {
        $this->predescription = $desc;
        return $this;
    }

    /** @return Setting */
    function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /** @return Setting */
    function height($height)
    {
        $this->height = $height;
        return $this;
    }

    /** @return Setting */
    function cmd($cmd)
    {
        $this->cmd = $cmd;
        return $this;
    }

    /** @return Setting */
    function depends($key, $val)
    {
        $this->depends[$key] = $val;
        return $this;
    }

    /** @return Setting */
    function encrypted($isEncrypted)
    {
        $this->encrypted = ($isEncrypted) ? 1 : 0;
        return $this;
    }

    /** @return Setting */
    function source($source)
    {
        $this->source = $this->valueBy = $source;
        return $this;
    }

    /** @return Setting */
    function validation($validation, $info)
    {
        $this->validation = $validation;
        $this->validation_info = $info;
        return $this;
    }

    /** @return Setting */
    function set($key, $val)
    {
        $this->$key = $val;
        return $this;
    }

    /** @return Setting */
    function def($val)
    {
        $this->default = $val;
        return $this;
    }

    /** @return Setting */
    function stype($val)
    {
        $this->stype = $val;
        return $this;
    }

    /** @return Setting */
    function callback($func)
    {
        $this->callback = $func;
        return $this;
    }

    function build($params = array())
    {
        if ($this->type == 'function') {
            $function = $this->callback;
            $items = $function();
            foreach ($items as $item) {
                $this->opt($item, $item);
            }
            $this->type = 'combobox';
        } elseif ($this->type == 'db') {
            global $db;
            if (! $this->_db_table) {
                $items = explode(',', $this->options);
                $this->_db_table = $items[0];
                $this->_db_id = $items[1];
                $this->_db_title = $items[2];
            }
            $items = $db->query("SELECT * FROM " . $this->_db_table, SQL_ALL);
            $this->options = array();
            foreach ($items as $item) {
                $this->opt($item[$this->_db_id], $item[$this->_db_title]);
            }
            $this->type = 'combobox';
        } elseif ($this->type == 'combobox') {
            $items = explode(',', $this->options);
            $this->options = array();
            foreach ($items as $item) {
                $itt = explode('=>', $item);
                if (count($itt) == 2) {
                    $this->opt($itt[0], $itt[1]);
                } else {
                    $this->opt($item, $item);
                }
            }
        } elseif ($this->type == 'server') {
            $Server = new Server($params['serverID']);
            $opts = $Server->getSetting($params['setting']);
            $opts = explode(',', $opts);
            foreach ($opts as $opt) {
                $this->opt($opt, $opt);
            }
            $this->type = 'combobox';
        }
        return $this;
    }

    function checkdep(&$all)
    {
        foreach ($this->deps as $k => $v) {
            if ($all[$k] != $v) {
                return false;
            }
        }
        return true;
    }

    function install($setting)
    {
        global $db;
        $sql = "INSERT INTO service_attr_types (setting) VALUES ('" . $setting . "')";
        $db->query($sql);
        $this->settingID = $db->lastInsertID();
        $this->setting = $setting;
        $this->update();
    }

    function uninstall($setting)
    {
        global $db;
        $db->query("DELETE FROM service_attr_types WHERE setting = '" . $setting . "' ");
    }

    static function type($type)
    {
        $Setting = new Setting();
        $Setting->type = $type;
        return $Setting;
    }

    static function getEncryptedSettings()
    {
        global $db;
        $encs = $db->query("SELECT setting FROM service_attr_types WHERE encrypted = '1'", SQL_KEY, 'setting');
        return $encs;
    }


} // end of class





