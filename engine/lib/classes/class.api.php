<?php
class api
{
    /* @return Client */
    private $CLIENT;
    private $clientID;

    private $MODULE;

    function __construct($clientID)
    {
        $this->clientID = $clientID;
    }

    function loadClient()
    {
        $this->CLIENT = new Client($this->clientID);
    }

    function loadModule($moduleID)
    {
        if (! preg_match('/^[a-zA-Z-_0-9]{1,50}$/', $moduleID)) {
            $this->displayError('Unknown module ' . $moduleID);
        }
        $MODULE = module::getInstance($moduleID);
        if (! $MODULE) {
            $this->displayError('Unknown module ' . $moduleID);
        } else {
            $this->MODULE = $MODULE;
        }
    }

    function runModuleCmd($method)
    {
        if (! method_exists($this->MODULE, $method)) {
            $this->displayError('Unknown method ' . $method);
        }
        $data = $this->MODULE->$method();
        $this->displayData($data);
    }

    function cmd_sync($data)
    {
        global $db;
        $this->loadModule($data['module']);

        switch ($data['action']) {
            case 'sync_commands':
                $this->runModuleCmd('getCmds');
            case 'sync_config':
                $this->runModuleCmd('getModuleConfig');
            case 'sync_services':
                $sql = "SELECT serviceID, service_name FROM services WHERE groupID = '" . (int)$data['groupID'] . "' ORDER BY service_name";
                $services = $db->query($sql, SQL_KEY, 'serviceID');
                $this->displayData($services);

        }

        $this->displayError('Unknown action ' . $data['action']);
    }

    function cmd_run($data)
    {
        global $db;
        $this->loadModule($data['module']);

        $ORDER = new Order($data['orderID']);
        if (! $ORDER) {
            $this->displayError('Unknown order ' . $data['orderID']);
        }

        switch ($data['action']) {
            case 'sync_commands':
                $this->runModuleCmd('getCmds');
            case 'sync_config':
                $this->runModuleCmd('getModuleConfig');
        }

        $this->displayError('Unknown action ' . $data['action']);
    }


    function displayError($error)
    {
        displayJSON(array('status' => '0', 'error' => $error));
    }

    function displayData($data)
    {
        displayJSON(array('status' => '1', 'data' => $data));
    }


} // end of class