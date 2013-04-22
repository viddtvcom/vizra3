<?php

class mod_cpanel extends ServiceModule
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

        $links['IpAddress'] = $this->Server->mainIp;
        $links["TemporaryUrl"] = $this->substitude($this->get('tmp_url'));
        $links["WebmailUrl"] = $this->substitude($this->get('webmail_url'));
        $links["CpanelUrl"] = $this->substitude($this->get('cpanel_url'));
        if ($this->stype == 'reseller') {
            $links["WhmUrl"] = $this->substitude($this->get('whm_url'));
        }
        return $links;


    }

    function substitude($str)
    {
        $str = str_replace('{$user}', $this->attrs['username'], $str);
        $str = str_replace('{$pass}', $this->attrs['password'], $str);
        $str = str_replace('{$domain}', $this->attrs['domain'], $str);
        $str = str_replace('{$server_hostname}', $this->Server->hostname, $str);
        $str = str_replace('{$server_mainip}', $this->Server->mainIp, $str);
        $str = str_replace('{$customer_domain}', $this->attrs['domain'], $str);

        return $str;
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
            $this->attrs['password'] = core::generatePassword(12);
            $this->setAttr('password', $this->attrs['password'], false);
        }
        if (substr($this->attrs['domain'], 0, 4) == 'www.') {
            $this->attrs['domain'] = str_replace('www.', '', $this->attrs['domain']);
            $this->setAttr('domain', $this->attrs['domain'], false);
        }

        $req = "createacct?contactemail=" . $this->Order->Client->email;

        $modConfig = $this->getModuleConfig();
        foreach ($this->attrs as $key => $val) {
            if ($modConfig['srvc'][$this->moduleID . '_' . $key]->stype == 'reseller' && $this->stype != 'reseller') {
                continue;
            }
            $req .= '&' . $key . '=' . $val;
        }

        if ($this->stype == 'reseller') {
            $req .= '&reseller=1';
        }
        $ret = $this->whmreq($req);
        if ($ret["st"]) {
            if ($this->stype == 'reseller') {
                $this->setupReseller();
            }
            $this->Order->setStatus('active');
        } elseif (strstr($ret["msg"], "a group for that username already exists") && $this->retries ++ < 2) {
            $_username = substr($this->attrs['username'], 0, 6) . rand(0, 9) . rand(0, 9);
            $this->setAttr('username', $_username, false);
            return $this->cmd_create();
        }
        return $ret;
    }

    function setupReseller()
    {
        $req = 'setresellerlimits?user=' . $this->attrs['username'];
        if ($this->attrs['account_limit'] > 0) {
            $req .= '&enable_account_limit=1&account_limit=' . $this->attrs['account_limit'];
        }
        if ($this->attrs['enable_resource_limits'] == '1') {
            $req .= '&enable_resource_limits=1&bandwidth_limit=' . $this->attrs['bandwidth_limit'];
            $req .= '&diskspace_limit=' . $this->attrs['diskspace_limit'];
        }
        $req .= '&enable_overselling_bandwidth=' . $this->attrs['enable_overselling_bandwidth'];
        $req .= '&enable_overselling_diskspace=' . $this->attrs['enable_overselling_diskspace'];
        $ret = $this->whmreq($req);

        if ($this->attrs['acllist'] != "") {
            $req = 'setacls?reseller=' . $this->attrs['username'];
            $req .= '&acllist=' . $this->attrs['acllist'];
            $ret = $this->whmreq($req);
        }

    }

    function cmd_terminate()
    {
        if ($this->stype == 'reseller') {
            $req = 'terminatereseller?terminatereseller=1&reseller=' . $this->attrs['username'];
            $req .= '&verify=I%20understand%20this%20will%20irrevocably%20remove%20all%20the%20accounts%20owned%20by%20the%20reseller%20' . $this->attrs['username'];
        } else {
            $req = 'removeacct?user=' . $this->attrs['username'];
        }
        $ret = $this->whmreq($req);
        if ($ret["st"]) {
            $this->Order->setStatus('deleted');
        }
        return $ret;
    }

    function cmd_suspend()
    {
        if ($this->stype == 'reseller') {
            $req = 'suspendreseller?user=' . $this->attrs['username'];
        } else {
            $req = 'suspendacct?user=' . $this->attrs['username'];
        }
        $ret = $this->whmreq($req);
        if ($ret["st"]) {
            $this->Order->setStatus('suspended');
        }
        return $ret;
    }

    function cmd_unsuspend()
    {
        if ($this->stype == 'reseller') {
            $req = 'unsuspendreseller?user=' . $this->attrs['username'];
        } else {
            $req = 'unsuspendacct?user=' . $this->attrs['username'];
        }
        $ret = $this->whmreq($req);
        if ($ret["st"]) {
            $this->Order->setStatus('active');
        }
        return $ret;
    }

    function cmd_setPassword($data)
    {
        $newpassword = ($data['password'] != "") ? $data['password'] : $this->attrs['password'];
        $req = 'passwd?user=' . $this->attrs['username'] . '&pass=' . $newpassword;
        $ret = $this->whmreq($req);
        if ($ret["st"]) {
            $this->setAttr('password', $newpassword, false);
        }
        return $ret;
    }

    function cmd_setDiskQuota($data)
    {
        if ($this->stype == 'reseller') {
            $diskspace_limit = (intval(
                $data['diskspace_limit']
            )) ? $data['diskspace_limit'] : $this->attrs['diskspace_limit'];
            $req = 'setresellerlimits?user=' . $this->attrs['username'] . '&diskspace_limit=' . $diskspace_limit;
            $ret = $this->whmreq($req);
            if ($ret["st"] && intval($data['diskspace_limit'])) {
                $this->setAttr('diskspace_limit', $data['diskspace_limit']);
            }
        } else {
            $quota = (intval($data['quota']) > 0) ? $data['quota'] : $this->attrs['quota'];
            $req = 'editquota?user=' . $this->attrs['username'] . '&quota=' . $quota;
            $ret = $this->whmreq($req);
            if ($ret["st"] && intval($data['quota'])) {
                $this->setAttr('quota', $data['quota']);
            }
        }

        return $ret;
    }

    function cmd_setTraffic($data)
    {
        if ($this->stype == 'reseller') {
            $bandwidth_limit = (intval(
                $data['bandwidth_limit']
            )) ? $data['bandwidth_limit'] : $this->attrs['bandwidth_limit'];
            $req = 'setresellerlimits?user=' . $this->attrs['username'] . '&bandwidth_limit=' . $bandwidth_limit;
            $ret = $this->whmreq($req);
            if ($ret["st"] && intval($data['bandwidth_limit'])) {
                $this->setAttr('bandwidth_limit', $data['bandwidth_limit']);
            }
        } else {
            $bwlimit = (intval($data['bwlimit'])) ? $data['bwlimit'] : $this->attrs['bwlimit'];
            $req = 'limitbw?user=' . $this->attrs['username'] . '&bwlimit=' . $bwlimit;
            $ret = $this->whmreq($req);
            if ($ret["st"] && intval($data['bwlimit'])) {
                $this->setAttr('bwlimit', $data['bwlimit']);
            }
        }
        return $ret;
    }

    function listaccts($owner = '')
    {
        $req = 'listaccts?viewall=1searchtype=owner&search=' . $owner;
        $ret = $this->whmreq($req);
        return $ret;
    }

    function showbw($user)
    {
        $req = 'showbw?searchtype=user&search=' . $user;
        $ret = $this->whmreq($req);
        $XML = new XmlToArray($ret['raw']);
        $bw = $XML->createArray();
        $bw = $bw['showbw']['bandwidth'][0];
        $nret['limit'] = $bw['acct'][0]['limit'] / (1024 * 1024);
        $nret['used'] = $bw['totalused'];
        return $nret;
    }

    function loadavg()
    {
        $req = 'loadavg';
        $ret = $this->whmreq($req);

        $XML = new XmlToArray($ret['raw']);
        $arr = $XML->createArray();
        if ($arr['loadavg']) {
            $cpu_count = (int)$this->Server->getSetting('cpu_count');
            if (! $cpu_count) {
                $cpu_count = 1;
            }
            foreach ($arr['loadavg'] as $k => $v) {
                $arr['loadavg'][$k] = intval($v / $cpu_count * 100);
            }
            $ret['msg'] = array($arr['loadavg']['one'], $arr['loadavg']['five'], $arr['loadavg']['fifteen']);
            $ret['st'] = true;
        }

        return $ret;
    }

    function whmreq($request)
    {
        /* server check */
        if (! $this->Server->serverID) {
            return array('msg' => 'Sunucu seçilmemiş');
        }

        /* Reseller or Shared ? */
        if ((is_object($this->Order) && is_object($this->Order->Service) && $this->Order->Service->type == 'shared')
                || (in_array($request, array('loadavg', 'listaccts')) && $this->Server->username == '')
        ) {
            $_auth = $this->Server->attrs['reseller_auth'];
            $_username = $this->Server->attrs['reseller_username'];
            $_password = $this->Server->attrs['reseller_password'];
            $_server_hash = $this->Server->attrs['reseller_server_hash'];
        } else {
            $_auth = $this->Server->attrs['auth'];
            $_username = $this->Server->username;
            $_password = $this->Server->password;
            $_server_hash = $this->Server->attrs['server_hash'];
        }

        if ($this->Server->attrs['use_ssl'] == '1') {
            $query = "https://" . $this->Server->mainIp . ":2087/xml-api/" . $request;
        } else {
            $query = "http://" . $this->Server->mainIp . ":2086/xml-api/" . $request;
        }
        $query = str_replace(' ', '+', $query);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($_auth == 'hash') {
            $header[0] = "Authorization: WHM " . $_username . ":" . preg_replace("'(\r|\n)'", "", $_server_hash);
        } else {
            $header[0] = "Authorization: Basic " . base64_encode($_username . ":" . $_password) . "\n\r";
        }


        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $query);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        $result = curl_exec($curl);

        if ($this->get('debug') == '1') {
            vzrlog($query, 'info', $this->Server->serverName . '(request)');
            vzrlog($result, 'info', $this->Server->serverName . '(response)');
        }

        if ($result == false) {
            $ret['st'] = false;
            $ret['msg'] = "curl_exec threw error \"" . curl_error($curl) . "\" for $query";
        } else {
            if (strstr($result, 'Access denied')) {
                $ret['st'] = false;
                $ret['msg'] = 'Access denied';
            } elseif (strstr($result, 'SSL encryption is required')) {
                $ret['st'] = false;
                $ret['msg'] = 'Bu sunucuya SSL ile bağlanmalısınız';
            } else {
                $ret['st'] = intval(getXmlData('status', $result));
                $ret['msg'] = getXmlData('statusmsg', $result);
                $ret['raw'] = $result;
            }
        }
        curl_close($curl);

        return $ret;


    }


} // end of module class
