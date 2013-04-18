<?php

class mod_plesk extends ServiceModule
{
    public $packet;

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
        $links["PleskUrl"] = $this->substitude($this->get('plesk_url'));
        return $links;
    }

    function substitude($str)
    {
        $str = str_replace('{$server_hostname}', $this->Server->hostname, $str);
        $str = str_replace('{$server_mainip}', $this->Server->mainIp, $str);
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
            $this->attrs['password'] = core::generateCode("8");
            $this->setAttr('password', $this->attrs['password'], false);
        }
        if (substr($this->attrs['domain'], 0, 4) == 'www.') {
            $this->attrs['domain'] = str_replace('www.', '', $this->attrs['domain']);
            $this->setAttr('domain', $this->attrs['domain'], false);
        }
        if ($this->attrs['template_name']) {
            $template_name = "<template-name>" . $this->attrs['template_name'] . "</template-name>";
        }
        if ($this->stype != 'reseller') {
            $this->packet = "
        <domain>
           <add>
                <gen_setup>
                  <name>" . $this->attrs['domain'] . "</name>
                  <htype>vrt_hst</htype>
                  <ip_address>" . $this->Server->mainIp . "</ip_address>
                  <status>0</status> 
                </gen_setup>
               <hosting>
                  <vrt_hst>
                      <ftp_login>" . $this->attrs['username'] . "</ftp_login>
                      <ftp_password>" . $this->attrs['password'] . "</ftp_password>
                      <ip_address>" . $this->Server->mainIp . "</ip_address>
                   </vrt_hst>
                </hosting>
                    $template_name
                    $limits
           </add>
        </domain>";
        } else {
            // Reseller
            $this->packet = "
        <client>
        <add>
           <gen_info>
               <cname>" . $this->Order->Client->name . "</cname>
               <pname>" . $this->attrs['username'] . "</pname>
               <login>" . $this->attrs['username'] . "</login>
               <passwd>" . $this->attrs['password'] . "</passwd>
               <status>0</status>
               <email>" . $this->Order->Client->email . "</email>
           </gen_info>
           $template_name
        </add>
        </client>";
        }
        $ret = $this->process();

        if ($ret["st"]) {
            if ($this->stype == 'reseller') {
                $clientID = getXmlData("id", $ret['msg']);
                $this->setClientIp($clientID, $this->Server->mainIp);
                $this->setClientLimits();
            } else {
                $this->setDomainAdmin();
                $this->setDomainLimits();
            }
            $this->Order->setStatus('active');
        }
        return $ret;
    }

    function setDomainAdmin()
    {
        $this->packet = "
                    <domain>
                       <set>
                           <filter>
                              <domain_name>" . $this->attrs['domain'] . "</domain_name>
                           </filter>
                           <values>
                               <user>
                                  <enabled>true</enabled>
                                  <password>" . $this->attrs['password'] . "</password>
                                  <pname>" . $this->Order->Client->name . "</pname>
                                  <phone>" . $this->Order->Client->cell . "</phone>
                                  <email>" . $this->Order->Client->email . "</email>
                                  <multiply_login>false</multiply_login>
                               </user>  
                           </values>
                       </set>
                    </domain>";
        $ret = $this->process();
        return $ret;
    }

    function setDomainLimits()
    {
        if ($this->attrs['max_mssql_db']) {
            $max_mssql_db = "<max_mssql_db>" . $this->attrs['max_mssql_db'] . "</max_mssql_db>";
        }
        $this->packet = "
            <domain>
               <set>
                   <filter>
                      <domain_name>" . $this->attrs['domain'] . "</domain_name>
                   </filter>
                   <values>
                     <limits>
                          <disk_space>" . $this->attrs['disk_space'] * 1024 * 1024 . "</disk_space>
                          <max_traffic>" . $this->attrs['max_traffic'] * 1024 * 1024 . "</max_traffic>
                          <max_db>" . $this->attrs['max_db'] . "</max_db>
                          <max_box>" . $this->attrs['max_box'] . "</max_box>
                          <max_subdom>" . $this->attrs['max_subdom'] . "</max_subdom>
                          <max_dom_aliases>" . $this->attrs['max_dom_aliases'] . "</max_dom_aliases>
                          $max_mssql_db  
                    </limits>
                    <prefs>
                        <www>true</www>
                    </prefs>
                   </values>
               </set>
            </domain>";
        $ret = $this->process();
        return $ret;
    }

    function setClientLimits()
    {
        $this->packet = "
    <client>
    <set>
       <filter>
          <login>" . $this->attrs['username'] . "</login>
       </filter>
       <values>    
       <limits>
           <limit><name>disk_space</name><value>" . $this->attrs['disk_space'] * 1024 * 1024 . "</value></limit>
           <limit><name>max_traffic</name><value>" . $this->attrs['max_traffic'] * 1024 * 1024 . "</value></limit>
           <limit><name>max_dom</name><value>" . $this->attrs['max_dom'] . "</value></limit>
       </limits>
       </values>
    </set>
    </client>";
        return $this->process('1.5.2.0');
    }

    function setClientIp($clientID, $ip)
    {
        $this->packet = "
    <client>
       <ippool_add_ip>
           <client_id>" . $clientID . "</client_id>
           <ip_address>" . $ip . "</ip_address>
       </ippool_add_ip>
    </client>";
        return $this->process();
    }

    function cmd_terminate()
    {
        if ($this->stype == 'reseller') {
            $this->packet = "
        <client>
           <del>
              <filter>
                  <login>" . $this->attrs['username'] . "</login>
              </filter>
           </del>
        </client>";
        } else {
            $this->packet = "
        <domain>
           <del>
            <filter>
             <domain_name>" . $this->attrs['domain'] . "</domain_name>
            </filter>
           </del>
        </domain>";
        }
        $ret = $this->process();
        if ($ret["st"]) {
            $this->Order->setStatus('deleted');
        }
        return $ret;
    }

    function cmd_suspend()
    {
        if ($this->stype == 'reseller') {
            $this->packet = "
        <client>
        <set>
           <filter>
              <login>" . $this->attrs['username'] . "</login>
           </filter>
           <values>
              <gen_info>
                  <status>16</status>
              </gen_info>
           </values>
        </set>
        </client>";
        } else {
            $this->packet = "
        <domain>
           <set>
           <filter>
              <domain_name>" . $this->attrs['domain'] . "</domain_name>
           </filter>
           <values>
              <gen_setup>
                  <status>64</status>
              </gen_setup>
           </values>
        
           </set>
        </domain>";
        }
        $ret = $this->process();
        if ($ret["st"]) {
            $this->Order->setStatus('suspended');
        }
        return $ret;
    }

    function cmd_unsuspend()
    {
        if ($this->stype == 'reseller') {
            $this->packet = "
        <client>
        <set>
           <filter>
              <login>" . $this->attrs['username'] . "</login>
           </filter>
           <values>
              <gen_info>
                  <status>0</status>
              </gen_info>
           </values>
        </set>
        </client>";
        } else {
            $this->packet = "
        <domain>
           <set>
           <filter>
              <domain_name>" . $this->attrs['domain'] . "</domain_name>
           </filter>
           <values>
              <gen_setup>
                  <status>0</status>
              </gen_setup>
           </values>
        
           </set>
        </domain>";
        }
        $ret = $this->process();
        if ($ret["st"]) {
            $this->Order->setStatus('active');
        }
        return $ret;
    }

    function setClientPassword($newpass)
    {
        $this->packet = "
    <client>
    <set>
       <filter>
          <login>" . $this->attrs['username'] . "</login>
       </filter>
       <values>    
       <gen_info>
           <passwd>" . $newpass . "</passwd>
       </gen_info>
       </values>
    </set>
    </client>";
        return $this->process();
    }

    function cmd_setPassword($data)
    {
        $newpassword = ($data['password'] != "") ? $data['password'] : $this->attrs['password'];
        if ($this->stype == 'reseller') {
            $ret = $this->setClientPassword($newpassword);
        } else {
            /// 1.5.2.0 için
            $this->packet = "
        <domain>
           <set>
               <filter>
                  <domain_name>" . $this->attrs['domain'] . "</domain_name>
               </filter>
               <values>
               <hosting>
                  <vrt_hst>
                    <ip_address>" . $this->Server->mainIp . "</ip_address>
                    <property>
                        <name>php</name>
                        <value>false</value>
                    </property>
                  </vrt_hst>
               </hosting>
               </values>
           </set>
        </domain>";
            // 1.4.2.0 için
            $this->packet = "
        <domain>
           <set>
               <filter>
                  <domain_name>" . $this->attrs['domain'] . "</domain_name>
               </filter>
               <values>
               <hosting>
                  <vrt_hst>
                      <ftp_login>" . $this->attrs['username'] . "</ftp_login>
                      <ftp_password>" . $newpassword . "</ftp_password>
                      <ip_address>" . $this->Server->mainIp . "</ip_address>
                  </vrt_hst>
               </hosting>
                <user>
                    <password>" . $newpassword . "</password>
                  </user>
               </values>
           </set>
        </domain>";

            $ret = $this->process();
        }

        if ($ret["st"]) {
            $this->setAttr('password', $newpassword, false);
        }
        return $ret;
    }

    function cmd_setDiskQuota($data)
    {
        $disk_space = (intval($data['disk_space'])) ? $data['disk_space'] : $this->attrs['disk_space'];
        if ($this->stype == 'reseller') {
            // reseller
            $this->packet = "
        <client>
        <set>
           <filter>
              <login>" . $this->attrs['username'] . "</login>
           </filter>
           <values>    
           <limits>
               <limit><name>disk_space</name><value>" . $disk_space * 1024 * 1024 . "</value></limit>
           </limits>
           </values>
        </set>
        </client>";
            $ret = $this->process('1.5.2.0');
        } else {
            $this->packet = "
        <domain>
           <set>
               <filter>
                  <domain_name>" . $this->attrs['domain'] . "</domain_name>
               </filter>
               <values>
                 <limits>
                  <disk_space>" . $disk_space * 1024 * 1024 . "</disk_space>
                </limits>
               </values>
           </set>
        </domain>";
            $ret = $this->process();
        }
        if ($ret["st"] && intval($data['disk_space'])) {
            $this->setAttr('disk_space', $data['disk_space']);
        }
        return $ret;
    }

    function cmd_setTraffic($data)
    {
        $max_traffic = (intval($data['max_traffic'])) ? $data['max_traffic'] : $this->attrs['max_traffic'];
        if ($this->stype == 'reseller') {
            // Reseller
            $this->packet = "
        <client>
        <set>
           <filter>
              <login>" . $this->attrs['username'] . "</login>
           </filter>
           <values>    
           <limits>
               <limit><name>max_traffic</name><value>" . $max_traffic * 1024 * 1024 . "</value></limit>
           </limits>
           </values>
        </set>
        </client>";
            $ret = $this->process('1.5.2.0');
        } else {
            $this->packet = "
        <domain>
           <set>
               <filter>
                  <domain_name>" . $this->attrs['domain'] . "</domain_name>
               </filter>
               <values>
                 <limits>
                  <max_traffic>" . $max_traffic * 1024 * 1024 . "</max_traffic>
                </limits>
               </values>
           </set>
        </domain>";
            $ret = $this->process();
        }
        if ($ret["st"] && intval($data['max_traffic'])) {
            $this->setAttr('max_traffic', $max_traffic);
        }
        return $ret;
    }

    function listaccts()
    {
        $this->packet = "
    <domain>
    <get>
       <filter></filter>
        <dataset>
            <hosting/>
            <limits/>
        </dataset>
    </get> 
    </domain>";

        return $this->process();
    }

    function loadavg()
    {
        $this->packet = "
    <server>
    <get>
        <stat/>
    </get> 
    </server>";
        $ret = $this->process();
        $xml = new XmlToArray($ret['msg']);
        $arr = $xml->createArray();
        $arr = $arr['packet']['server'][0]['get'][0]['result'][0]['stat'][0]['load_avg'][0];
        if ($arr) {
            $ret['st'] = true;
        }
        $ret['msg'] = $arr; //array($arr['l1'],$arr['l5'],$arr['l15']);
        return $ret;
    }

    function process($version = '1.4.2.0')
    {
        /* server check */
        if (! $this->Server->serverID) {
            return array('msg' => 'Sunucu seçilmemiş');
        }

        $packet = '<packet version="' . $version . '">' . $this->packet . '</packet>';
        $this->response = $this->sendRequest($packet);

        if ($this->get('debug') == '1') {
            vzrlog($packet, 'info', $this->Server->serverName . '(request)');
            vzrlog($this->response, 'info', $this->Server->serverName . '(response)');
        }

        if (strpos($this->response, "<status>ok</status>")) {
            return array('st' => true, 'msg' => $this->response);
        } else {
            return array('st' => false, 'msg' => getXmlData('errtext', $this->response));
        }
    }

    function sendRequest($packet)
    {
        /* Reseller or Shared ? */
        if (is_object($this->Order) && is_object($this->Order->Service) && $this->Order->Service->type == 'shared') {
            $_username = $this->Server->attrs['reseller_username'];
            $_password = $this->Server->attrs['reseller_password'];
        } else {
            $_username = $this->Server->username;
            $_password = $this->Server->password;
        }

        /* server port */
        $port = ($this->Server->attrs['port']) ? $this->Server->attrs['port'] : '8443';

        $this->curlInit($this->Server->mainIp, $_username, $_password, $port);

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $packet);
        $result = curl_exec($this->curl);

        if ($result == false) {
            $ret = '<errtext>' . curl_error($this->curl) . '</errtext>';
        } else {
            $ret = $result;
        }

        curl_close($this->curl);

        return $ret;
    }

    function curlInit($host, $login, $password, $port)
    {
        $curl = curl_init();

        $protocol = ($port == '8443') ? 'https' : 'http';
        $url = $protocol . "://" . $host . ":" . $port . "/enterprise/control/agent.php";

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "HTTP_AUTH_LOGIN: " . $login,
                "HTTP_AUTH_PASSWD: " . $password,
                "HTTP_PRETTY_PRINT: TRUE",
                "Content-Type: text/xml"
            )
        );

        $this->curl = & $curl;
    }


} // end of module class
