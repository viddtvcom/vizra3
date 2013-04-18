<?php

class Queue extends base
{
    public $jobID;
    public $dateAdded;
    public $dateUpdated;
    public $code;

    protected $orderID;
    protected $paymentID;
    protected $status;
    protected $job;
    protected $params;
    protected $params_decoded;

    function __construct($id = 0)
    {
        $this->db_table = 'queue';
        $this->ID_field = 'jobID';
        $this->_dateAdded = 'dateAdded';
        $this->_dateUpdated = 'dateUpdated';
        $this->db_members = array(
            'orderID',
            'paymentID',
            'status',
            'code',
            'job',
            'params',
            'result',
            'dateAdded',
            'dateUpdated',
            'dateFire'
        );
        if ($id) {
            $this->load((int)$id);
        }
    }

    static function createJob($job)
    {
        $Queue = new Queue();
        $Queue->create();
        $Queue->code = core::generateCode(64);
        $Queue->job = $job;

        return $Queue;
    }

    function load($id = '')
    {
        parent::load($id);

        $params = (array)unserialize($this->params);
        foreach ($params as $key => $value) {
            $this->params_decoded[$key] = base64_decode($value);
        }
    }

    function update()
    {
        foreach ($this->params_decoded as $key => $value) {
            $params[$key] = base64_encode($value);
        }
        $this->params = sanitize(serialize($params));

        parent::update();

        return $this;
    }

    function start()
    {
        global $db;
        // odeme bekliyor ve zamanlanmis bir gorev ise..
        if (in_array($this->status, array('pending-payment', 'scheduled'))) {
            return true;
        }

        // ip cozuluyorsa fsockopen ile islemi yap
        if (QUEUE_MODE == 'async' || (gethostbyname(BASEHOST) != BASEHOST && ! defined('QUEUE_MODE'))) {
            $fp = fsockopen(BASEHOST, 80, $errno, $errstr, 30);
            if ($fp == true) {
                $url .= "/" . BASEDIR . "/index.php?p=queue&jobID=" . $this->jobID . "&code=" . $this->code;

                $out = "GET $url HTTP/1.1\r\n";
                $out .= "Host: " . BASEHOST . "\r\n";
                $out .= "Connection: Close\r\n\r\n";

                stream_set_blocking($fp, false);
                stream_set_timeout($fp, 86400);
                fwrite($fp, $out);

                return true;
            }
        }


        // CRON calisiyorsa, birak cron ile yapilsin
        if (QUEUE_MODE == 'cron' || ! defined('QUEUE_MODE')) {
            $cron_last_run = $db->query(
                "SELECT dateStart FROM crons WHERE filename = 'minutely.php'",
                SQL_INIT,
                'dateStart'
            );
            if (time() - $cron_last_run < 330) {
                $this->set('status', 'pending-cron');
                return true;
            }
        }

        // hicbiri olmadiysa mecburen direk yap
        if (QUEUE_MODE == 'direct' || ! define('QUEUE_MODE')) {
            $this->process();
        }

    }

    function process()
    {

        $func = 'do_' . $this->job;
        if (method_exists($this, $func)) {
            try {
                $this->set('status', 'inprocess');

                $this->$func();

                $this->setStatus('completed');
            } catch (Exception $e) {
                vzrlog($e->getMessage(), 'error');

                $this->setStatus('error');
                $this->result = $e->getMessage();
            }
        }

        $this->update();

    }

    function destroy()
    {
        global $db;
        $sql = "DELETE FROM queue WHERE jobID = '" . $this->jobID . "'";
        $db->query($sql);
    }


    function do_sendmail()
    {
        $params = $this->params_decoded;
        core::mailsender(
            $params['subject'],
            $params['to'],
            $params['body'],
            $params['fromName'],
            $params['fromEmail'],
            $params['cc'],
            $params['bcc']
        );

        $this->destroy();
    }

    function do_sendmsn()
    {
        $params = $this->params_decoded;
        core::send_msn($params['text'], $params['email'], false);

        $this->destroy();
    }

    function do_sendsms()
    {
        $params = $this->params_decoded;
        core::send_sms($params['text'], $params['gsm'], false);

        $this->destroy();
    }

    function do_domregister()
    {
        $params = $this->params_decoded;

        $Domain = new Domain($params['domainID']);
        if ($Domain->domainID == false) {
            throw new Exception('Domain bulunamadı');
        }

        $ret = $Domain->register($params['period']);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }

    function do_domrenew()
    {
        $params = $this->params_decoded;

        $Domain = new Domain($params['domainID']);
        if ($Domain->domainID == false) {
            throw new Exception('Domain bulunamadı');
        }

        $ret = $Domain->renew($params['period']);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }

    function do_orderstatus()
    {
        $params = $this->params_decoded;

        $Order = new Order($this->orderID);
        if ($Order->orderID == false) {
            throw new Exception('Sipariş bulunamadı');
        }

        $ret = $Order->setStatus($params['orderStatus']);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }

    function do_reneworder()
    {
        $params = $this->params_decoded;

        $Order = new Order($this->orderID);
        if ($Order->orderID == false) {
            throw new Exception('Sipariş bulunamadı');
        }

        $ret = $Order->renew(false, $this->paymentID, $params['multiplier']);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }

    function do_modulecmd()
    {
        $params = $this->params_decoded;

        $Order = new Order($this->orderID);
        if ($Order->orderID == false) {
            throw new Exception('Sipariş bulunamadı');
        }

        $ret = $Order->moduleRunCmd($params['cmd'], $params);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }

    function do_servercmd()
    {
        $params = $this->params_decoded;

        $Server = new Server($params['serverID']);
        if ($Server->serverID == false) {
            throw new Exception('Sunucu bulunamadı');
        }

        $ret = $Server->moduleRunCmd($params['cmd'], $params);
        if ($ret['st'] != true) {
            throw new Exception($ret['msg']);
        }
    }


    function setParams($params)
    {
        $this->params_decoded = $params;
        return $this;
    }

    function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    function setOrderID($orderID)
    {
        $this->orderID = $orderID;
        return $this;
    }

    function setPaymentID($paymentID)
    {
        $this->paymentID = $paymentID;
        return $this;
    }

    function setDateFire($timestamp)
    {
        $this->dateFire = $timestamp;
        return $this;
    }


    static function process_job($jobID, $code)
    {
        global $db;
        $job = $db->query("SELECT * FROM queue WHERE jobID = " . (int)$jobID . " AND code = '" . $code . "'", SQL_INIT);
        if ($job == false) {
            return false;
        }

        $prm2 = unserialize($job["params"]);

        foreach ($prm2 as $k => $v) {
            $prm[$k] = base64_decode($v);
        }

        $ret = self::run_job($job, $prm);

        if ($ret["st"] == false) {
            debug('Hata oluştu:');
            debug($ret);
        } else {
            debug('İşlem başarılı');
        }
    }

    static function run_job($j, $prm)
    {
        global $db;
        $Order = new Order($j['orderID']);

        $db->query("UPDATE queue SET status = 'inprocess' WHERE jobID = " . $j['jobID']);
        switch ($j["job"]) {
            case 'sendMail':
                $ret = core::mailsender($prm['subject'], $prm['to'], $prm['body'], $prm['fromName'], $prm['fromEmail']);
                if (! $ret['st']) {
                    vzrlog('Mail gönderim hatası: ' . $ret['msg'], 'error');
                }
                break;
            case 'send_msn':
                $ret['st'] = core::send_msn($prm['text'], $prm['email'], false);
                break;
            case 'send_sms':
                $ret['st'] = core::send_sms($prm['text'], $prm['gsm'], false);
                break;

            case 'domain':
                $Domain = new Domain($prm['domainID']);
                if (! $Domain->domainID) {
                    return false;
                }
                switch ($prm['cmd']) {
                    case 'register':
                        $ret = $Domain->register($prm['period']);
                        break;
                    case 'renew':
                        $ret = $Domain->renew($prm['period']);
                        break;
                    case 'setDNS':
                        $ret = $Domain->setDNS($prm['ns1'], $prm['ns2']);
                        break;
                }
                if (! $ret['st']) {
                    vzrlog(
                        '<' . $prm['cmd'] . '> işleminde hata: ' . $ret['msg'],
                        'error',
                        $Domain->domain,
                        $Domain->orderID
                    );
                }
                break;
            case 'moduleCmd':
                $Order = new Order($j['orderID']);
                if (! $Order->orderID) {
                    return false;
                }
                $ret = $Order->moduleRunCmd($prm['cmd'], $prm);
                break;
            case 'moduleServerCmd':
                $Server = new Server($prm['serverID']);
                if (! $Server->serverID) {
                    return false;
                }
                $ret = $Server->moduleRunCmd($prm['cmd'], $prm);
                break;
            case 'renewOrder':
                $Order = new Order($j['orderID']);
                if (! $Order->orderID) {
                    return false;
                }
                $ret = $Order->renew(false, $j['paymentID'], $prm['multiplier']);
                break;
            case 'setOrderStatus':
                $Order = new Order($j['orderID']);
                if (! $Order->orderID) {
                    return false;
                }
                $ret = $Order->setStatus($prm['orderStatus']);
                break;
        }

        $status = ($ret["st"] == true) ? "completed" : "error";
        $db->query(
            "UPDATE queue SET status = '" . $status . "', result = '" . sanitize(
                $ret["msg"]
            ) . "' WHERE jobID = " . $j["jobID"]
        );

        return $ret;
    }

}