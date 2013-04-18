<?php
class Probe extends base
{

    public $probeID;
    public $serverID;
    public $status;
    public $title;
    public $port;
    public $dateAdded;
    public $dateUpdated;
    public $dateNotified;

    public $Server;


    function __construct($id = 0)
    {
        $this->db_table = "server_probes";
        $this->ID_field = "probeID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array('serverID', 'status', 'title', 'port', 'dateAdded', 'dateUpdated', 'dateNotified');
        if ($id) {
            $this->load($id);
        }
        $this->Server = new Server();
    }


    function check($timeout = 9)
    {
        $this->Server->load($this->serverID);
        $chk = fsockopen($this->Server->mainIp, $this->port, $errno, $errstr, $timeout);
        if (! $chk) {
            if ($this->dateNotified < time() - (60 * 3)) {
                vzrlog('<' . $this->title . '> service DOWN (' . $errstr . ')', 'error', $this->Server->serverName);
                $this->dateNotified = time();
            }
        } elseif ($this->status == 'off') {
            vzrlog('<' . $this->title . '> service UP', 'info', $this->Server->serverName);
            $this->dateNotified = 0;
        }
        $this->status = ($chk) ? "on" : "off";
        $this->update();
    }

    function destroy()
    {
        global $db;
        $db->query("DELETE FROM server_probes WHERE probeID = " . $this->probeID);
    }


} // end of class





