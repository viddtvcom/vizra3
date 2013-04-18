<?php

class mod_hypervm extends ServiceModule
{

    function getCmds()
    {
        $admin = array('create', 'suspend', 'unsuspend', 'reboot', 'boot', 'poweroff', 'terminate');
        $user = array();
        $server = array('syncTemplates', 'syncServers', 'syncPlans');
        return array('admin' => $admin, 'user' => $user, 'server' => $server);
    }

    function getLinks()
    {
        return array();
    }

    function getResourceList($type)
    {
        $params['action'] = 'simplelist';
        $params['resource'] = $type;
        $list = $this->request($params);
        if ($list['st']) {
            foreach ($list['msg'] as $k => $v) {
                $ret['data'][] = $k;
            }
            $ret['st'] = true;
            return $ret;
        } else {
            return $list;
        }

    }

    function getProperty($type = 'v-coma_vmipaddress_a')
    {
        $params['action'] = 'getproperty';
        $params['class'] = 'vps';
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params[$type] = 'null';
        $prop = $this->request($params);
        if ($prop['st']) {
            $obj = $prop['msg'];
            return $obj->$type;
        } else {
            return false;
        }
    }

    function syncTemplates()
    {
        $v_type = $this->Server->getSetting('v-type');
        $templates = $this->getResourceList('ostemplate_' . $v_type);
        if (! $templates['st']) {
            return $templates;
        }
        $this->Server->setSetting('v-ostemplate', implode(',', $templates['data']));
        return array('st' => true);
    }

    function syncServers()
    {
        $v_type = $this->Server->getSetting('v-type');
        $servers = $this->getResourceList('vpspserver_' . $v_type);
        if (! $servers['st']) {
            return $servers;
        }
        $this->Server->setSetting('v-syncserver', implode(',', $servers['data']));
        return array('st' => true);
    }

    function syncPlans()
    {
        $v_type = $this->Server->getSetting('v-type');
        $plans = $this->getResourceList('resourceplan');
        if (! $plans['st']) {
            return $plans;
        }
        $this->Server->setSetting('v-plan_name', implode(',', $plans['data']));
        return array('st' => true);
    }

    function cmd_create()
    {

        $params = array();
        if ($this->attrs['password'] == "") {
            $this->attrs['password'] = core::generateCode("8");
            $this->setAttr('password', $this->attrs['password'], false);
        }
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'];
        $params['action'] = "add";
        $params['v-password'] = $this->attrs['password'];
        $params['v-type'] = $this->Server->getSetting('v-type');
        $params['v-num_ipaddress_f'] = $this->attrs['v-num_ipaddress_f'];
        $params['v-contactemail'] = $this->Order->Client->email;
        $params['v-send_welcome_f'] = "off";
        $params['v-ostemplate'] = $this->attrs['v-ostemplate'];
        $params['v-syncserver'] = $this->attrs['v-syncserver'];
        $params['v-plan_name'] = $this->attrs['v-plan_name'];

        $res = $this->request($params);
        if ($res['st']) {
            $ip = $this->getProperty('v-coma_vmipaddress_a');
            $ip = str_replace(',', "\n", $ip);
            $this->setAttr('ip_addresses', $ip);
        }

        return $res;


    }

    function cmd_terminate()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = "delete";
        return $this->request($params);
    }

    function cmd_suspend()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'disable';
        return $this->request($params);
    }

    function cmd_unsuspend()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'enable';
        return $this->request($params);
    }

    function cmd_reboot()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'reboot';
        return $this->request($params);
    }

    function cmd_boot()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'boot';
        return $this->request($params);
    }

    function cmd_poweroff()
    {
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'poweroff';
        return $this->request($params);
    }

    function cmd_setPassword($data)
    {
        $newpassword = ($data['password'] != "") ? $data['password'] : $this->attrs['password'];
        $params = array();
        $params['class'] = "vps";
        $params['name'] = $this->attrs['vmname'] . '.vm';
        $params['action'] = 'update';
        $params['subaction'] = 'rootpassword';
        $params['v-rootpassword'] = $newpassword;
        $ret = $this->request($params);
        if ($ret["st"]) {
            $this->setAttr('password', $newpassword, false);
        }
        return $ret;
    }

    function request($params)
    {
        $params['login-class'] = "client";
        $params['login-name'] = $this->Server->username;
        $params['login-password'] = $this->Server->password;
        $params['output-type'] = "json";

        $ch = curl_init();
        $protocol = ($this->Server->attrs['use_ssl'] == '1') ? 'https' : 'http';

        curl_setopt(
            $ch,
            CURLOPT_URL,
                $protocol . '://' . $this->Server->mainIp . ':' . $this->Server->getSetting('port') . '/webcommand.php'
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = (array)json_decode(curl_exec($ch));
        curl_close($ch);

        if ($this->get('debug') == '1') {
            $params['login-password'] = 'xxxxxxxx';
            vzrlog($params, 'info', $this->Server->serverName . '(request)');
            vzrlog($response, 'info', $this->Server->serverName . '(response)');
        }

        if ($response['return'] == "success") {
            $st = true;
            return array('st' => true, 'msg' => $response['result']);
        } else {
            return array('st' => false, 'msg' => $response['message']);
        }
        //return array('st'=>$st,'msg'=>$response['result']);
    }


} // end of module class
