<?php

class mod_whmsonic extends ServiceModule
{

    function getCmds()
    {
        $admin = array('create', 'suspend', 'unsuspend', 'terminate', 'start', 'stop');
        $user = array('start', 'stop');
        $server = array();
        return array('admin' => $admin, 'user' => $user, 'server' => $server);
    }

    function getLinks()
    {
        $user = $this->attrs['username'];
        $pass = $this->attrs['password'];
        $links['IpAddress'] = $this->Server->mainIp;
        $links["WHMSonic Url"] = "https://" . $this->Server->hostname . ":2083/login/?user=$user&pass=$pass";
        return $links;
    }

    function cmd_create()
    {


        $file = 'setup_external.php';
        $params['setup'] = 'yes';
        $params['c_user'] = str_replace('sc_', '', $this->attrs['username']);
        $params['c_pass'] = core::generateCode('8');
        $params['admin_pass'] = core::generateCode('8');
        $params['password'] = core::generateCode('8');
        $params['ip'] = $this->attrs['ip'] ? $this->attrs['ip'] : $this->Server->mainIp;
        $params['ac_bitrate'] = $this->attrs['max_bitrate'];
        $params['bw'] = $this->attrs['bw'];
        $params['auto_port'] = $this->attrs['port'] ? '' : 'yes';
        $params['limit'] = $this->attrs['max_listener'];
        $params['rad_dj'] = $this->attrs['autodj'] == '1' ? 'Yes' : 'No';
        $params['esend'] = 'no';
        /*    $params['esend'] = 'yes';
            $params['cemail'] = 'maselcuk@gmail.com';*/
        $params['destip'] = 'assigned';
        $params['public'] = 'default';
        $ret = $this->request($file, $params);

        if (strstr($ret, 'done=yes')) {
            $this->setAttr('username', 'sc_' . $this->attrs['username']);
            $this->setAttr('admin_pass', $params['admin_pass'], true);
            $this->setAttr('radio_pass', $params['password'], true);
            $this->setAttr('password', $params['c_pass'], true);
            $this->setAttr('ip', $params['ip'], true);
            $details = $this->getDetails();
            $this->setAttr('port', $details['port'], true);

            $this->Order->setStatus('active');
            return array('st' => true);
        } else {
            return $ret;
        }

    }

    function getDetails()
    {
        $file = 'edit.php';
        $params['user'] = $this->attrs['username'];

        $ret = $this->request($file, $params);
        $x = explode('name="rad_port" value="', $ret);
        $xx = explode('"', $x[1]);
        $details['port'] = $xx[0];
        return $details;

    }


    function cmd_start()
    {
        $file = 'control.php';
        $params['rad_username'] = $this->attrs['username'];
        $params['control'] = 'start';
        $ret = $this->request($file, $params);
        return array('st' => true);
    }

    function cmd_stop()
    {
        $file = 'control.php';
        $params['rad_username'] = $this->attrs['username'];
        $params['control'] = 'stop';
        $ret = $this->request($file, $params);
        return array('st' => true);
    }

    function cmd_suspend()
    {
        $file = 'suspend.php';
        $params['customer'] = $this->attrs['username'];
        $params['suspend'] = 'yes';
        $ret = $this->request($file, $params);
        $this->Order->setStatus('suspended');
        return array('st' => true);
    }

    function cmd_unsuspend()
    {
        $file = 'suspend.php';
        $params['customer'] = $this->attrs['username'];
        $params['unsuspend'] = 'yes';
        $ret = $this->request($file, $params);
        $this->Order->setStatus('active');
        return array('st' => true);
    }

    function cmd_terminate()
    {
        $file = 'removeradio.php';
        $params['customer'] = $this->attrs['username'];
        $params['remove'] = 'yes';
        $ret = $this->request($file, $params);
        $this->Order->setStatus('deleted');
        return array('st' => true);

    }


    function request($file, $params)
    {
        if ($this->Server->attrs['use_ssl'] == '1') {
            $query = "https://" . $this->Server->mainIp . ":2087/whmsonic/tools/" . $file;
        } else {
            $query = "http://" . $this->Server->mainIp . ":2086/whmsonic/tools/" . $file;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $header[0] = "Authorization: Basic " . base64_encode(
            $this->Server->username . ":" . $this->Server->password
        ) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $query);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        $result = curl_exec($curl);

        if ($this->get('debug') == '1') {
            vzrlog($query, 'info', $this->Server->serverName . '(request-query)');
            vzrlog($params, 'info', $this->Server->serverName . '(request-params)');
            vzrlog(strip_tags($result), 'info', $this->Server->serverName . '(response)');

            //debuglog($params, 'whmsonic');
            //debuglog($result, 'whmsonic');
        }

        if ($result == false) {
            $ret['st'] = false;
            $ret['msg'] = "curl_exec threw error \"" . curl_error($curl) . "\" for $query";
        } elseif (strstr($result, 'SSL encryption is required')) {
            $ret['st'] = false;
            $ret['msg'] = 'Bu sunucuya SSL ile bağlanmalısınız';
        } else {
            return $result;
        }
        curl_close($curl);

        return $ret;


    }


} // end of module class
