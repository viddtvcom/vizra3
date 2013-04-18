<?php

class mod_directadmin extends ServiceModule
{

    function getCmds()
    {
        $admin = array('create', 'suspend', 'unsuspend', 'terminate');
        $user = array('setPassword');
        $server = array();
        return array('admin' => $admin, 'user' => $user, 'server' => $server);
    }

    function getLinks()
    {
        $user = $this->attrs['username'];
        $pass = $this->attrs['password'];
        $links['IpAddress'] = $this->Server->mainIp;
        $links["PanelUrl"] = linkify("http://" . $this->Server->hostname . ":2222/", 1);
        return $links;
    }


    function cmd_create()
    {
        if ($this->attrs['username'] == "") {
            $mat = array_reverse(explode('.', $this->attrs['domain']));
            if (strlen($mat[0]) == '2' && strlen($mat[1]) == '3') {
                $dom = $mat[2];
            } else {
                $dom = $mat[1];
            }
            $this->attrs['username'] = substr(preg_replace('/[\.*-]/', '', $dom), 0, 8);
            $this->setAttr('username', $this->attrs['username'], false);
        }
        if ($this->attrs['password'] == "") {
            $this->attrs['password'] = core::generateCode("8");
            $this->setAttr('password', $this->attrs['password'], false);
        }
        if (substr($this->attrs['domain'], 0, 4) == 'www.') {
            $this->attrs['domain'] = str_replace('www.', '', $this->attrs['domain']);
            $this->setAttr('domain', $this->attrs['domain'], false);
        }

        $data['action'] = 'create';
        $data['add'] = 'Submit';
        $data['domain'] = $this->attrs['domain'];
        $data['username'] = $this->attrs['username'];
        $data['passwd'] = $this->attrs['password'];
        $data['passwd2'] = $this->attrs['password'];
        $data['quota'] = $this->attrs['quota'];
        $data['bandwidth'] = $this->attrs['bandwidth'];
        $data['package'] = $this->attrs['package'] == '' ? null : $this->attrs['package'];
        $data['vdomains'] = $this->attrs['vdomains'];

        if ($this->stype == 'reseller') {
            $cmd = 'CMD_API_ACCOUNT_RESELLER';
            $data['serverip'] = 'ON';
            $data['ip'] = 'shared';
            $data['ips'] = 1;
        } else {
            $cmd = 'CMD_API_ACCOUNT_USER';
            $data['ip'] = $this->Server->mainIp;
        }

        $data['email'] = $this->Order->Client->email;
        $ret = $this->call($cmd, $data);

        if (strstr($ret['msg'], 'created')) {
            $this->Order->setStatus('active');
            $ret['msg'] = 'Hesap açıldı';
            $ret['st'] = true;
        } else {
            $ret['st'] = false;
        }
        return $ret;
    }

    function cmd_terminate()
    {
        $data['confirmed'] = 'Confirm';
        $data['delete'] = 'yes';
        $data['select0'] = $this->attrs['username'];
        $cmd = 'CMD_API_SELECT_USERS';
        $ret = $this->call($cmd, $data, 'POST');
        if (strstr($ret['msg'], 'Removed')) {
            $this->Order->setStatus('deleted');
            $ret['msg'] = 'Hesap silindi';
        }
        return $ret;
    }

    function cmd_suspend()
    {
        $data['select0'] = $this->attrs['username'];
        $data['dosuspend'] = '1';
        $cmd = 'CMD_API_SELECT_USERS';
        $ret = $this->call($cmd, $data, 'POST');

        if (strstr($ret['msg'], 'suspended')) {
            $this->Order->setStatus('suspended');
            $ret['msg'] = 'Hesap askıya alındı';
        }
        return $ret;
    }

    function cmd_unsuspend()
    {
        $data['select0'] = $this->attrs['username'];
        $data['dounsuspend'] = '1';
        $cmd = 'CMD_API_SELECT_USERS';
        $ret = $this->call($cmd, $data, 'POST');

        if (strstr($ret['msg'], 'unsuspended')) {
            $this->Order->setStatus('active');
            $ret['msg'] = 'Hesap askıdan alındı';
        }
        return $ret;
    }

    function cmd_setPassword($data)
    {
        $newpassword = ($data['password'] != "") ? $data['password'] : $this->attrs['password'];
        $data['username'] = $this->attrs['username'];
        $data['passwd'] = $newpassword;
        $data['passwd2'] = $newpassword;
        $cmd = 'CMD_API_USER_PASSWD';
        $ret = $this->call($cmd, $data, 'POST');

        if (strstr($ret['msg'], 'changed')) {
            $this->setAttr('password', $newpassword, false);
        }
        return $ret;
    }

    function cmd_setDiskQuota($data)
    {
        $quota = (intval($data['quota']) > 0) ? $data['quota'] : $this->attrs['quota'];
        $ret = $this->modifyUser('quota', $quota);
        if ($ret['st'] && intval($data['quota'])) {
            $this->setAttr('quota', $data['quota']);
        }
        return $ret;
    }

    function cmd_setDomainLimit($data)
    {
        $vdomains = (intval($data['vdomains']) > 0) ? $data['vdomains'] : $this->attrs['vdomains'];
        $ret = $this->modifyUser('vdomains', $vdomains);
        if ($ret['st'] && intval($data['vdomains'])) {
            $this->setAttr('vdomains', $data['vdomains']);
        }
        return $ret;
    }

    function cmd_setTraffic($data)
    {
        $bandwidth = (intval($data['bandwidth'])) ? $data['bandwidth'] : $this->attrs['bandwidth'];
        $ret = $this->modifyUser('bandwidth', $bandwidth);

        if ($ret["st"] && intval($data['bandwidth'])) {
            $this->setAttr('bandwidth', $data['bandwidth']);
        }
        return $ret;
    }

    function modifyUser($key, $val)
    {
        $data['action'] = 'customize';
        $data['user'] = $this->attrs['username'];
        $data[$key] = $val;
        if ($this->stype == 'reseller') {
            $cmd = 'CMD_API_MODIFY_RESELLER';
        } else {
            $cmd = 'CMD_API_MODIFY_USER';
        }
        $ret = $this->call($cmd, $data, 'POST');

        if (strstr($ret['msg'], 'error=1') && ! strstr($ret['msg'], 'no quota enabled')) {
            $ret['st'] = false;
        } else {
            $ret['st'] = true;
            $ret['msg'] = '';
        }
        return $ret;
    }

    function listaccts()
    {
        $req = 'listaccts';
        $ret = $this->whmreq($req);
        return $ret;
    }

    function loadavg()
    {
        $req = 'loadavg';
        $ret = $this->whmreq($req);
        $XML = new XmlToArray($ret['raw']);
        $arr = $XML->createArray();
        return array($arr['loadavg']['one'], $arr['loadavg']['five'], $arr['loadavg']['fifteen']);
    }

    function call($cmd, $data, $method = 'GET')
    {
        /* server check */
        if (! $this->Server->serverID) {
            return array('msg' => 'Sunucu seçilmemiş');
        }

        /* Reseller or Shared ? */
        if (is_object($this->Order) && is_object($this->Order->Service) && $this->Order->Service->type == 'shared') {
            $_username = $this->Server->attrs['reseller_username'];
            $_password = $this->Server->attrs['reseller_password'];
        } else {
            $_username = $this->Server->username;
            $_password = $this->Server->password;
        }

        /* server port */
        $port = ($this->Server->attrs['port']) ? $this->Server->attrs['port'] : '2222';

        require_once('directadmin.php');
        $protocol = ($this->Server->attrs['use_ssl'] == '1') ? 'https' : 'http';
        $DA = new DirectAdmin($protocol . '://' . $_username . ':' . $_password . '@' . $this->Server->mainIp . ':' . $port);
        $args = array('command' => $cmd, 'method' => $method, 'data' => $data);

        $retrieved = $DA->retrieve($args);
        if ($this->get('debug') == '1') {
            vzrlog($args, 'info', $this->Server->serverName . '(request)');
            vzrlog($retrieved, 'info', $this->Server->serverName . '(response)');
        }
        $ret['st'] = true;
        $ret['msg'] = $retrieved;


        return $ret;


    }


} // end of module class
