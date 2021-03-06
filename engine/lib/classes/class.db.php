<?php

define('SQL_NONE', 1);
define('SQL_ALL', 2);
define('SQL_INIT', 3);
define('SQL_KEY', 4);
define('SQL_MKEY', 5);

class DB
{
    public $success = true;
    public $debug = false;
    private static $instance;

    function DB()
    {

    }

    function debug()
    {
        $this->debug = true;
    }

    function debuglog()
    {
        $this->debuglog = true;
    }

    function connect($host, $db, $user, $pass)
    {
        $this->link = mysql_pconnect($host, $user, $pass);
        mysql_select_db($db, $this->link);
    }

    function pconnect($host, $db, $users, $pass)
    {
        $this->link = mysql_pconnect($host, $users, $pass);
        if ($this->link) {
            mysql_select_db($db, $this->link);
            return true;
        } else {
            throw new Exception('Sunucuya bağlanılamadı, lütfen daha sonra tekrar deneyiniz');
        }

    }

    function vconnect()
    {
        if (MYSQL_PCONNECT) {
            $this->link = mysql_pconnect(DBHOST, DBUSER, DBPASS);
        } else {
            $this->link = mysql_connect(DBHOST, DBUSER, DBPASS);
        }
        mysql_select_db(DBNAME, $this->link);
        mysql_query("SET NAMES 'utf8'", $this->link);
        register_shutdown_function(array(&$this, 'close'));
    }

    function changedb($db)
    {
        mysql_select_db($db, $this->link);
    }

    function query($query, $type = SQL_NONE, $iassoc = '', $ikey = '')
    {
        $ret = array();
        $this->sql = $query;
        if ($this->debug) {
            debug($query);
        }
        if ($this->debuglog) {
            debuglog($query, 'db_query');
        }

        $result = mysql_query($query, $this->link);
        if (mysql_errno($this->link) != 0) {
            // mysql error
            $this->errorMsg = mysql_error($this->link);
            $this->success = false;
            if ($this->debug || DEBUG == true) {
                debug(array($query, $this->errorMsg));
                debugTrace();
            } else {
                //debuglog(array($query,$this->errorMsg),'dberror');
            }
        } else {
            if ($type == SQL_NONE) {

            } elseif ($type == SQL_ALL) {
                while ($row = mysql_fetch_assoc($result)) {
                    if ($iassoc == "") {
                        $ret[] = $row;
                    } else {
                        $ikey = $row["$iassoc"];
                        unset($row["$iassoc"]);
                        $ret["$ikey"] = $row;
                    }
                }
                return $ret;
            } elseif ($type == SQL_INIT) {
                $row = mysql_fetch_assoc($result);
                if (! $row) {
                    return false;
                }
                foreach ($row as $key => $value) {
                    $ret["$key"] = $value;
                }
                if (! $iassoc == "") {
                    $ret = $ret["$iassoc"];
                }
            } elseif ($type == SQL_KEY) {
                while ($row = mysql_fetch_assoc($result)) {
                    if ($iassoc == "") {
                        $ret[] = $row;
                    } else {
                        $ikey = $row[$iassoc];
                        unset($row[$iassoc]);
                        if (empty($row)) {
                            $ret[] = $ikey;
                        } elseif (count($row) == 1) {
                            $ret["$ikey"] = array_pop($row);
                        } else {
                            $ret["$ikey"] = $row;
                        }
                    }
                }
            } elseif ($type == SQL_MKEY) {
                while ($row = mysql_fetch_assoc($result)) {
                    $arr = array();
                    foreach ($row as $k => $v) {
                        if ($k == $iassoc) {
                            continue;
                        }
                        $arr[$k] = $v;
                    }
                    if ($ikey) {
                        $ret[$row[$iassoc]][$row[$ikey]] = $arr;
                        unset($ret[$row[$iassoc]][$row[$ikey]][$ikey]);
                        if (count($ret[$row[$iassoc]][$row[$ikey]]) == 1) {
                            $ret[$row[$iassoc]][$row[$ikey]] = $arr[$k];
                        }
                    } else {
                        $ret[$row[$iassoc]][] = $arr;
                    }

                }
            }
            $this->success = true;
        }
        @mysql_free_result($row);
        if ($this->debug) {
            debug($ret);
        }
        return $ret;
    }

    function insert($table, $data)
    {
        $data = $this->escape($data);
        $names = array_keys($data);
        $names = implode('`, `', $names);

        $values = array_values($data);
        $values = implode("', '", $values);

        $sql = "INSERT INTO `$table` (`$names`) VALUES ('$values')";

        $this->query($sql);

        return $this->lastInsertID();
    }

    function escape($input)
    {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = $this->escape($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }

            $output = mysql_real_escape_string($input);
        }
        return $output;
    }


    function lastInsertID()
    {
        return mysql_insert_id($this->link);
    }

    function close()
    {
        //mysql_close($this->link);
    }

}

