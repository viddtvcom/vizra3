<?php

class mod_helm3 extends ServiceModule
{

    function getCmds()
    {
        $admin = array();
        $user = array();
        $server = array();
        return array('admin' => $admin, 'user' => $user, 'server' => $server);
    }

    function getLinks()
    {
        $links["TemporaryUrl"] = 'http://' . $this->attrs['domain'] . '.' . $this->attrs['tmp_url'] . '.yourwebservers.com';
        if ($this->stype == 'reseller') {
            $links["PanelUrl"] = 'http://' . $this->Server->hostname;
        } else {
            $links["PanelUrl"] = 'http://' . $this->Server->attrs['reseller_hostname'];
        }
        return $links;
    }


} // end of module class
