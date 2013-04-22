<?php
require("3rdparty/Smarty-2.6.26/libs/Smarty.class.php");
class vSmarty extends Smarty
{
    function vSmarty($scope)
    {
        global $config, $vars;

        $this->scope = $scope;

        $tpl_dir = ($scope == 'client') ? getSetting('portal_tpl') : 'default';
        //if ($_SESSION["vadmin"] && $scope == 'client') $tpl_dir = 'vizraweb';
        $config["turl"] = $config['HTTP_HOST'] . 'themes/' . $scope . '/' . $tpl_dir . '/';
        $this->template_dir = $config['BASE_PATH'] . 'themes' . DIRECTORY_SEPARATOR . $scope . DIRECTORY_SEPARATOR . $tpl_dir;
        $this->compile_dir = $config["BASE_PATH"] . 'tmp' . DIRECTORY_SEPARATOR . 'smarty';

        $this->assign("config", $config);
        $this->assign("vars", $vars);
        $this->assign("vurl", $config['HTTP_HOST']);
        $this->assign("turl", $config["turl"]);
        $this->assign('VVERSION', VVERSION);


        $this->register_function('format_date', 'sm_format_date');
        $this->register_function('readbit', 'sm_readbit');
        $this->register_function('getCurrencyById', 'sm_getCurrencyById');
        $this->register_function('formatId', 'sm_formatId');
        $this->register_outputfilter('sm_prefilter_pre01');
        $this->register_modifier("formdisplay", "sm_formdisplay");


        //$this->debugging  = true;
        $this->compile_check = true;
        $this->force_compile = true;

        //debug($this,1);
    }

    function iassign($item, $extra = "")
    {
        global $db;
        switch ($item) {
            case 'service_groups':
                $select_groups = $db->query("SELECT * FROM service_groups ORDER BY groupID ASC", SQL_ALL);
                $this->assign(
                    "select_groups",
                    array2select($select_groups, "groupID", "groupID", "group_name", $extra)
                );
                break;
            case 'service_quota_types':
                $service_quota_types = $db->query("SELECT * FROM service_quota_types", SQL_ALL);
                $this->assign(
                    "select_service_quota_types",
                    array2select($service_quota_types, "quotaID", "quotaID", "quota_name", $extra)
                );
                break;
            case 'servers':
                $servers = $db->query("SELECT * FROM servers", SQL_ALL);
                $this->assign("select_servers", array2select($servers, "serverID", "serverID", "server_name", $extra));
                break;


        }
    }
}
