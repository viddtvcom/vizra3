<?php
class base
{

    protected $db_members;
    protected $fields_allowed;
    protected $db_table;
    protected $ID_field;

    function __construct($id = "")
    {
        if (is_array($id)) {
            $this->objectFromArray($id);
        } elseif ($id) {
            $this->load($id);
        }
    }

    function create($generateID = false)
    {
        global $db;
        $id = ($generateID) ? generateID($this->db_table, $this->ID_field) : 'NULL';
        if ($this->_dateAdded != "") {
            $sql = "INSERT INTO " . $this->db_table . " ( " . $this->ID_field . "," . $this->_dateAdded . " ) VALUES ($id,UNIX_TIMESTAMP())";
        } else {
            $sql = "INSERT INTO " . $this->db_table . " ( " . $this->ID_field . " ) VALUES ($id)";
        }
        $db->query($sql);
        $this->{$this->ID_field} = ($generateID) ? $id : $db->lastInsertID();
        $this->load();
        return $this->{$this->ID_field};
    }

    function objectFromArray($array)
    {
        foreach ($array as $k => $v) {
            $this->$k = $v;
        }
        return $this;
    }

    static function factory($className, $id)
    {
        return new $className($id);
    }

    static function newInstance($className)
    {
        return new $className();
    }


    function load($id = "")
    {
        if ($id == "") {
            if ($this->{$this->ID_field} == "") {
                return false;
            } else {
                $this->{$this->ID_field} = $this->{$this->ID_field};
            }
        } else {
            $this->{$this->ID_field} = $id;
        }
        return $this->load_sub($this->{$this->ID_field}, $this->ID_field, $this->db_table, $this->db_members);
    }

    function load_sub($id, $ID_field, $db_table, $db_members)
    {
        global $db;
        $sql = "SELECT * FROM " . $db_table . " WHERE " . $ID_field . " = '" . $id . "'";
        $result = $db->query($sql, SQL_INIT);
        if (! $result) {
            $this->{$this->ID_field} = '';
            return false;
        }
        foreach ($db_members as $key => $value) {
            $this->$value = $result[$value];
        }
        return true;
    }

    function update($exclude = array())
    {
        return $this->update_sub(
            $this->{$this->ID_field},
            $this->ID_field,
            $this->db_table,
            $this->db_members,
            $exclude
        );

    }

    function update_sub($id, $ID_field, $db_table, $db_members, $exclude)
    {
        global $db;
        if ($this->_dateUpdated != "") {
            $this->{$this->_dateUpdated} = time();
        }
        $sql = "UPDATE " . $db_table . " SET ";
        foreach ($db_members as $key => $value) {
            if (in_array($value, $exclude)) {
                continue;
            }
            $sql .= "`$value` = '" . mysql_real_escape_string(stripslashes(trim($this->$value))) . "',";
        }
        $sql = rtrim($sql, ",");
        $sql .= " WHERE " . $ID_field . " = '" . $id . "'";
        $db->query($sql);
    }

    function replace($data, $db_members = "")
    {
        global $db;
        if ($db_members == "") {
            $db_members = $this->db_members;
        }
        foreach ($db_members as $key => $value) {
            if (array_key_exists("$value", $data) && $value != $this->ID_field) {
                $this->$value = $data["$value"];
            }
        }

        if ($this->db_checkboxes) {
            foreach ($this->db_checkboxes as $cb) {
                if (! isset($data[$cb])) {
                    $this->$cb = '0';
                }
            }
        }
        return $this;
    }

    function set($field, $value)
    {
        global $db;
        $sql = "UPDATE " . $this->db_table . " SET `$field`= '$value' WHERE `" . $this->ID_field . "` = '" . $this->{$this->ID_field} . "'";
        $db->query($sql);
        $this->$field = $value;
        return $this;
    }

    function get($field)
    {
        global $db;
        $sql = "SELECT $field as field FROM " . $this->db_table . " WHERE `" . $this->ID_field . "` = '" . $this->{$this->ID_field} . "'";
        return $db->query($sql, SQL_INIT, "field");
    }

    function filter_fields(&$data)
    {
        if (! isset($this->fields_allowed)) {
            return;
        }

        foreach ($data as $key => $value) {
            if (! in_array($key, $this->fields_allowed)) {
                unset($data[$key]);
            }
        }
    }

} // end of class base

