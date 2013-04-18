<?php

/**
 *   Vizra Servis Modülü, örnek dosya
 *
 *   Modul adiniz mymodule ise; (modul adlari bosluk ve turkce karater iceremez, sayi ile baslayamaz)
 *   1) modul dizin adi mymodule olmali
 *   2) modul dosyasinin adi mod.mymodule.php olmali
 *   3) class adi mod_mymodule olmalidir
 *
 *
 */

class mod_mymodule extends ServiceModule
{

    /**
     *   Modul islemlerinde kullanilacak komutlar burada belirlenir
     *   admin: ACP'de adminler tarafindan erisilebilen komutlar
     *   user: Musteri panelinde, siparis ozelliklerinde musteriler tarafindan calistiracak komutlar
     *   server: Bu modulden olusturulan sunucu ayarlarinda cikacak komutlar
     *   module: Modul ayari sayfasinda calistirilacak komutlar
     *
     *   bu komutlari cmd_komutadi seklinde class metodlarini olusturarak cagirabilirsiniz (ornek: cmd_create)
     */
    function getCmds()
    {
        $cmd['admin'] = array('create', 'suspend', 'unsuspend', 'terminate');
        $cmd['user'] = array('userCmd1');
        $cmd['server'] = array('serverCmd1');
        $cmd['module'] = array('moduleCmd1');

        return $cmd;
    }


    function getLinks()
    {

        $links['IpAddress'] = $this->Server->mainIp;
        $links["TemporaryUrl"] = $this->substitude($this->get('tmp_url'));
        $links["WebmailUrl"] = $this->substitude($this->get('webmail_url'));
        $links["CpanelUrl"] = $this->substitude($this->get('cpanel_url'));

        return $links;

    }


    function cmd_create()
    {

        debug($this->attrs);
        $this->setAttr('password', 'yenisifre');


        debug($this->Order);

        // hesap acma islemleri....
        // ..........

        // Siparis durumunu aktif et
        $this->Order->setStatus('active');

        // basarili sonuc dondurmek icin
        return array('st' => true);

        // hata dondurmek icin
        return array('st' => false, 'msg' => 'Hesap açma işleminde hata');


    }

    function cmd_terminate()
    {
        // hesap silme islemleri..
        // ...........

        // Siparis durumunu silinmis olarak guncelle
        $this->Order->setStatus('active');

        // basarili sonuc dondurmek icin
        return array('st' => true);

        // hata dondurmek icin
        return array('st' => false, 'msg' => 'Hesap silme işleminde hata');
    }

    function cmd_suspend()
    {
        // hesap askıya alma islemleri..
        // ...........

        // Siparis durumunu askıda olarak guncelle
        $this->Order->setStatus('suspended');

        // basarili sonuc dondurmek icin
        return array('st' => true);

        // hata dondurmek icin
        return array('st' => false, 'msg' => 'Hesap askıya alma işleminde hata');
    }

    function cmd_unsuspend()
    {
        // hesap askıdan alma islemleri..
        // ...........

        // Siparis durumunu aktif guncelle
        $this->Order->setStatus('active');

        // basarili sonuc dondurmek icin
        return array('st' => true);

        // hata dondurmek icin
        return array('st' => false, 'msg' => 'Hesap askıdan alma işleminde hata');
    }


    function cmd_setPassword($data)
    {
        $newpassword = ($data['password'] != "") ? $data['password'] : $this->attrs['password'];

        // sifre degistirme islemi..
        //  $ret = ....

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


} // end of module class
