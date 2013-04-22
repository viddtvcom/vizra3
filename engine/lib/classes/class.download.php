<?php
class Download extends base
{

    public $fileID;
    public $catID;
    public $adminID;
    public $title;
    public $description;
    public $downloads;
    public $origname;
    public $sysname;
    public $size;
    public $extension;
    public $dateAdded;
    public $dateUpdated;

    function Download($id = 0)
    {
        $this->db_table = "dc_files";
        $this->ID_field = "fileID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array(
            'catID',
            'adminID',
            'title',
            'description',
            'origname',
            'sysname',
            'size',
            'extension',
            'downloads',
            'dateAdded',
            'dateUpdated'
        );
        if ($id) {
            $this->load($id);
        }
    }

    function load($id = '')
    {
        parent::load($id);
        $this->parent = Download::getCategory($this->catID);
    }

    function update()
    {
        parent::update();
        Download::updateEntryCount(Download::getCategoryTree(0));
    }

    function upload_web($fieldname)
    {
        global $config;
        $source = $_FILES[$fieldname]['tmp_name'];

        $f_info = core::formatFilename(strtolower($_FILES[$fieldname]['name']));
        $this->origname = $f_info['name'];
        $this->sysname = $f_info['sysname'];
        $this->extension = $f_info['ext'];
        $dest = $config['DOWNLOADS_DIR'] . $this->sysname . '.' . $this->extension;

        if (move_uploaded_file($source, $dest)) {
            $this->size = filesize($dest);
            return array('st' => true);
        } else {
            return array('st' => false);
        }
    }

    function upload_ftp($filename)
    {
        global $config;
        $source = $config['TMP_DIR'] . $filename;
        if (! file_exists($source)) {
            return array('st' => false, 'msg' => 'Dosya bulunamadı');
        }

        $f_info = core::formatFilename(strtolower($filename));
        $this->origname = $f_info['name'];
        $this->sysname = $f_info['sysname'];
        $this->extension = $f_info['ext'];
        $dest = $config['DOWNLOADS_DIR'] . $this->sysname . '.' . $this->extension;

        if (rename($source, $dest)) {
            $this->size = filesize($dest);
            return array('st' => true);
        } else {
            return array('st' => false);
        }
    }

    function permcheck()
    {
        if ($this->parent['visibility'] == 'admin' && getClientID()) {
            global $db;
            $sql = "SELECT DISTINCT(file_cats) FROM orders o
                    INNER JOIN services s ON s.serviceID = o.serviceID
                WHERE o.clientID = " . getClientID() . " AND o.status = 'active' AND s.file_cats != ''";
            $cats = $db->query($sql, SQL_ALL);
            foreach ($cats as $c) {
                $c = explode(',', $c['file_cats']);
                if (in_array($this->catID, $c)) {
                    return true;
                }
            }
        }
        if ((! getClientID() && $this->parent['visibility'] != 'everyone') || $this->parent['visibility'] == 'admin'
        ) {
            return false;
        }
        return true;
    }

    function downloaded()
    {
        if (in_array($this->fileID, (array)$_SESSION['downloaded'])) {
            return;
        }
        $_SESSION['downloaded'][] = $this->fileID;
        $this->set('downloads', ++$this->downloads);
    }

    function destroy()
    {
        global $db, $config;
        $db->query("DELETE FROM dc_files WHERE fileID = " . $this->fileID);
        Download::updateEntryCount(Download::getCategoryTree(0));
        unlink($config['DOWNLOADS_DIR'] . $this->sysname . '.' . $this->extension);
    }

    static function getCategories($parentID = 0)
    {
        global $db;
        $sql = "SELECT * FROM dc_cats WHERE parentID = " . (int)$parentID . " AND visibility IN ('" . implode(
            "','",
            self::getVisibility()
        ) . "')  ORDER BY title";
        $cats = $db->query($sql, SQL_KEY, 'catID');
        return $cats;
    }

    static function getVisibility()
    {
        $vis = array('everyone');
        if (CLIENTID > 0) {
            $vis[] = 'client';
        } elseif (ADMINID > 0) {
            $vis[] = 'client';
            $vis[] = 'admin';
        }
        return $vis;
    }

    static function getCategoryTree($parentID = 0, $exclude = 0)
    {
        global $db;
        $cats = self::getCategories($parentID);
        foreach ($cats as $catID => $c) {
            if ($catID == $exclude) {
                continue;
            }
            $cats[$catID]['children'] = self::getCategoryTree($catID, $exclude);
        }
        return $cats;
    }

    static function getCategoryList($arr, $ptitle = 'Genel', $depth = 0)
    {
        global $db;
        if (! $depth) {
            $ret[] = array('catID' => 0, 'title' => 'Genel', 'depth' => 0);
        }
        $depth ++;
        foreach ($arr as $catID => $c) {
            $c['title'] = $ptitle . " &raquo; " . $c['title'];
            $ret[] = array('catID' => $catID, 'title' => $c['title'], 'depth' => $depth);
            if (count($c['children'])) {
                $ret = array_merge($ret, self::getCategoryList($c['children'], $c['title'], $depth));
            }
        }
        return $ret;
    }

    static function updateEntryCount($arr)
    {
        global $db;
        foreach ($arr as $catID => $c) {
            $cnt = $db->query("SELECT COUNT(fileID) AS cnt FROM dc_files WHERE catID = " . $catID, SQL_INIT, 'cnt');
            $c['entries'] = $cnt + self::updateEntryCount($c['children']);
            $db->query("UPDATE dc_cats SET entries = " . $c['entries'] . " WHERE catID = " . $catID);
            $sum += $c['entries'];
        }
        return $sum;
    }

    static function getSubFiles($arr)
    {
        global $db;
        $ret = array();
        foreach ($arr as $catID => $c) {
            //$files = $db->query("SELECT * FROM dc_files WHERE catID = ".$catID,SQL_KEY,'fileID');
            $files = self::getFiles($catID);
            $subfiles = self::getSubFiles($c['children']);
            $ret = $ret + $files + $subfiles;
        }
        return $ret;
    }

    static function getCategoryFiles($catID)
    {
        $subfiles = Download::getSubFiles(Download::getCategoryTree($catID));
        $files = self::getFiles($catID);
        return $subfiles + $files;
    }

    static function removeCategory($arr)
    {
        global $db;
        foreach ($arr as $catID => $c) {
            self::deleteCategoryFiles($catID);
            $db->query("DELETE FROM dc_cats WHERE catID = " . $catID);
            self::removeCategory($c['children']);
        }
    }

    static function deleteCategoryFiles($catID)
    {
        global $db;
        $files = self::getFiles($catID);
        foreach ($files as $fileID => $f) {
            $DC = new Download($fileID);
            $DC->destroy();
        }
        //$db->query("DELETE FROM dc_files WHERE catID = ".$catID);
    }

    static function getFiles($catID, $exclude = 0)
    {
        global $db;
        $sql = "SELECT * FROM dc_files WHERE catID = " . $catID . " AND fileID != " . (int)$exclude . " ORDER BY dateAdded DESC";
        $entries = $db->query($sql, SQL_KEY, 'fileID');
        return $entries;
    }

    static function getBreadcrumbs($catID, $str = '', $base = '?p=230')
    {
        global $db;
        $cat = self::getCategory($catID);
        $str = ' &raquo; <a href="' . $base . '&catID=' . $catID . '">' . $cat['title'] . '</a>' . $str;
        if ($cat['parentID']) {
            return self::getBreadcrumbs($cat['parentID'], $str);
        } else {
            return $str;
        }
    }

    static function getCategory($catID)
    {
        global $db;
        if (! $catID) {
            return array('visibility' => 'everyone');
        }
        return $db->query("SELECT * FROM dc_cats WHERE catID = " . (int)$catID, SQL_INIT);
    }


} // end of class





