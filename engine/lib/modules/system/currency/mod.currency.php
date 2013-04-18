<?php

class mod_currency extends SystemModule
{

    function getCmds()
    {
        $module = array('update');
        return array('module' => $module);
    }

    function update()
    {
        global $config, $db;
        $col = $this->get('col') + 1;
        $offset = $this->get('offset');
        $url = 'http://www.tcmb.gov.tr/kurlar/today.html';

        $remote = core::curlpost($url);
        if (! $remote['st']) {
            vzrlog($url . ' ile bağlantı kurulamadı', 'e', 'Currency');
            return false;
        }
        $data = explode("\n", $remote['data']);
        $ndata = array('TRY' => 1);

        foreach ($data as $d) {
            if (strstr($d, '/TRY')) {
                $d = explode(' ', $d);
                $key = str_replace('/TRY', '', $d[0]);
                $nd = array();
                foreach ($d as $line) {
                    if (strstr($line, '.')) {
                        $nd[] = $line;
                    }
                }
                $ndata[$key] = $nd[$col] + $offset;
            }
        }

        $mainCurCode = $config['CURTABLE'][MAIN_CUR_ID]['code'];
        $mainCurRatio = $ndata[$mainCurCode];

        foreach ($ndata as $code => $curData) {
            $ndata[$code] = $ndata[$code] / $mainCurRatio;
        }

        foreach ($config['CURTABLE'] as $curID => $curData) {
            $ratio = $ndata[$curData['code']];
            $this->update_cur($curID, $ratio);
        }

        if ($this->get('debug') == '1') {
            vzrlog($ndata, 'info', 'Currency');
        }

    }

    function update_cur($curID, $ratio)
    {
        global $db;
        $db->query("UPDATE settings_currencies SET ratio = '" . $ratio . "' WHERE curID = " . $curID);
    }


    function update_date($ts)
    {
        global $config, $db;
        $data = $this->fetch($ts);

        if (! $data) {
            return false;
        }

        foreach ($config['CURTABLE'] as $curID => $curData) {
            if ($curData['status'] != 'active') {
                continue;
            }
            $ratio = $data[$curData['code']];
            $sql = "INSERT INTO currency_history (day,curID,maincurID,ratio)
                VALUES ('" . date('Y-m-d', $ts) . "'," . $curID . "," . MAIN_CUR_ID . "," . $ratio . ")
                ON DUPLICATE KEY UPDATE ratio = " . $ratio . ", maincurID = " . MAIN_CUR_ID;
            $db->query($sql);

        }

        return true;


    }

    function fetch($ts)
    {
        if ($ts > time()) {
            $ts = time();
        }
        global $config;
        $col = $this->get('col') + 1;
        $offset = $this->get('offset');
        $url = 'http://www.tcmb.gov.tr/kurlar/' . date('Ym/dmY', $ts) . '.html';

        vzrlog($url);

        $remote = core::curlpost($url);
        $this->tries ++;
        if (strstr($remote['data'], 'Sayfa Goruntulenemedi - Page Not Found')) {
            if ($this->tries > 7) {
                return false;
            }
            return $this->fetch($ts - (60 * 60 * 24));
        }
        if (! $remote['st']) {
            vzrlog($url . ' ile bağlantı kurulamadı', 'e', 'Currency');
            return false;
        }
        $data = explode("\n", $remote['data']);
        $ndata = array('TRY' => 1);

        foreach ($data as $d) {
            if (strstr($d, '/TRY')) {
                $d = explode(' ', $d);
                $key = str_replace('/TRY', '', $d[0]);
                $nd = array();
                foreach ($d as $line) {
                    if (strstr($line, '.')) {
                        $nd[] = $line;
                    }
                }
                $ndata[$key] = $nd[$col] + $offset;
            }
        }

        $mainCurCode = $config['CURTABLE'][MAIN_CUR_ID]['code'];
        $mainCurRatio = $ndata[$mainCurCode];

        foreach ($ndata as $code => $curData) {
            $ndata[$code] = $ndata[$code] / $mainCurRatio;
        }

        foreach ($config['CURTABLE'] as $curID => $curData) {
            if ($curData['status'] != 'active') {
                continue;
            }
            $ratio = $ndata[$curData['code']];
            $mdata[$curID] = number_format($ratio, 5, '.', '');
        }

        return $mdata;
    }


} // end of class

