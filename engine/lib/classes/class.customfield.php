<?php
class CustomField extends base
{
    public $attrID;
    public $label;
    public $type;
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
    public $default;


    static $types = array('textbox'  => 'Textbox',
                          'textarea' => 'Textarea',
                          'checkbox' => 'Evet/Hayır',
                          'combobox' => 'Combobox',
                          'db'       => 'Veritabanı'
    );
    static $_visibility = array('required' => 'Zorunlu', 'hidden' => 'Gizli', 'optional' => 'Opsiyonel');

    function __construct($id = 0)
    {
        $this->type = $type;
        $this->db_table = "attrs";
        $this->ID_field = "attrID";

        $this->db_members = array(
            'label',
            'visibility',
            'type',
            'client_type',
            'options',
            'description',
            'width',
            'height',
            'encrypted',
            'validation',
            'validation_function',
            'validation_info'
        );
        if ($id) {
            $this->load($id);
        }
    }

    /** @return SomeSetting */
    function lab($lab)
    {
        $this->label = $lab;
        return $this;
    }

    /** @return SomeSetting */
    function opt($key, $val)
    {
        $this->options[$key] = $val;
        return $this;
    }

    /** @return SomeSetting */
    function val($val)
    {
        $this->value = $val;
        return $this;
    }

    /** @return SomeSetting */
    function desc($desc)
    {
        $this->description = $desc;
        return $this;
    }

    /** @return SomeSetting */
    function predesc($desc)
    {
        $this->predescription = $desc;
        return $this;
    }

    /** @return SomeSetting */
    function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /** @return SomeSetting */
    function height($height)
    {
        $this->height = $height;
        return $this;
    }

    /** @return SomeSetting */
    function encrypted($isEncrypted)
    {
        $this->encrypted = ($isEncrypted) ? 1 : 0;
        return $this;
    }

    /** @return SomeSetting */
    function visibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /** @return SomeSetting */
    function set($key, $val)
    {
        $this->$key = $val;
        return $this;
    }


    function destroy()
    {
        global $db;
        $db->query("DELETE FROM client_extras WHERE attrID = " . $this->attrID);
        $db->query("DELETE FROM attrs WHERE attrID = " . $this->attrID);
    }


    function build($params = array())
    {
        if ($this->type == 'db') {
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

    static function type($type)
    {
        $CS = new CustomField();
        $CS->type = $type;
        return $CS;
    }

    static function getAttrs($client = false)
    {
        global $db;
        $sql = "SELECT * FROM attrs WHERE visibility != 'system'";
        if ($client) {
            $sql .= " AND visibility != 'hidden'";
        }

        $attrs = $db->query($sql, SQL_KEY, 'attrID');
        foreach ($attrs as $attrID => $data) {
            $ret[$attrID] = CustomField::type($data['type'])->lab($data['label'])
                    ->desc($data['description'])
                    ->width($data['width'])
                    ->visibility($data['visibility'])
                    ->encrypted($data['encrypted']);
            $ret[$attrID]->attrID = $attrID;
            $ret[$attrID]->client_type = $data['client_type'];
            $ret[$attrID]->options = $data['options'];
            $ret[$attrID]->build();
        }

        return $ret;
    }

    static function validate($data, $client_type, $decrypt = false)
    {
        global $db;
        $attrs = $db->query("SELECT * FROM attrs", SQL_KEY, 'attrID');
        foreach ($attrs as $attrID => $attr) {
            $valid = true;

            if ($decrypt == true && $attr['encrypted'] == '1') {
                $data[$attrID] = core::decrypt($data[$attrID]);
            }

            if ($attr['client_type'] != 'all' && $attr['client_type'] != $client_type) {
                continue;
            }

            if ($attr['visibility'] == 'required' || ($attr['visibility'] == 'optional' && $data[$attrID] != '')) {
                if ($attr['validation_function'] != '') {
                    require('extend/class.attrs.php');
                    if (method_exists('attrs', $attr['validation_function'])) {
                        if (Attrs::$attr['validation_function']($data[$attrID]) != true) {
                            $valid = false;
                        }
                    }
                } elseif ($attr['validation'] != '' && ! @preg_match('/' . $attr['validation'] . '/', $data[$attrID])) {
                    $valid = false;
                }
            }
            if ($valid == false) {
                $errors['extra_' . $attrID] = $attr['validation_info'];
            }
        }

        if (isset($errors) == false) {
            return array('st' => true, 'errors' => array());
        } else {
            return array('errors' => $errors);
        }


    }

    static function getValidationFunctions()
    {
        require('extend/class.attrs.php');
        $functions = get_class_methods('attrs');
        return $functions;
    }


} // end of class





