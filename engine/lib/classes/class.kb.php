<?php
class Kb extends base
{

    public $entryID;
    public $catID;
    public $adminID;
    public $title;
    public $body;
    public $views;
    public $dateAdded;
    public $dateUpdated;

    function Kb($id = 0)
    {
        $this->db_table = "kb_entries";
        $this->ID_field = "entryID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = array('catID', 'adminID', 'title', 'body', 'views', 'dateAdded', 'dateUpdated');
        if ($id) {
            $this->load($id);
        }
    }

    function load($id = '')
    {
        parent::load($id);
        $this->parent = kb::getCategory($this->catID);
        //$this->body = linkify2($this->body,true);
    }

    function update()
    {
        parent::update();
        kb::updateEntryCount(kb::getCategoryTree(0));
    }

    function viewed()
    {
        $this->set('views', ++$this->views);
    }

    function destroy()
    {
        global $db;
        $db->query("DELETE FROM kb_entries WHERE entryID = " . $this->entryID);
        kb::updateEntryCount(kb::getCategoryTree(0));
    }

    static function getCategories($parentID = 0)
    {
        global $db;
        $sql = "SELECT * FROM kb_cats WHERE parentID = " . (int)$parentID . " AND visibility IN ('" . implode(
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
            $children = self::getCategoryTree($catID, $exclude);
            if ($children) {
                $cats[$catID]['children'] = $children;
            }
        }
        return $cats;
    }


    static function getCategoryList($arr, $ptitle = 'Genel', $depth = 0)
    {
        global $db;
        $arr = (array)$arr;

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
        $arr = (array)$arr;
        foreach ($arr as $catID => $c) {
            $cnt = $db->query("SELECT COUNT(entryID) AS cnt FROM kb_entries WHERE catID = " . $catID, SQL_INIT, 'cnt');
            $c['entries'] = $cnt + self::updateEntryCount($c['children']);
            $db->query("UPDATE kb_cats SET entries = " . $c['entries'] . " WHERE catID = " . $catID);
            $sum += $c['entries'];
        }
        return $sum;
    }

    static function removeCategory($arr)
    {
        global $db;
        $arr = (array)$arr;
        foreach ($arr as $catID => $c) {
            $db->query("DELETE FROM kb_entries WHERE catID = " . $catID);
            $db->query("DELETE FROM kb_cats WHERE catID = " . $catID);
            self::removeCategory($c['children']);
        }
    }

    static function getEntries($catID, $exclude = 0)
    {
        global $db;
        $sql = "SELECT * FROM kb_entries WHERE catID = " . $catID . " AND entryID != " . (int)$exclude . " ORDER BY title";
        $entries = $db->query($sql, SQL_KEY, 'entryID');
        return $entries;
    }

    static function getBreadcrumbs($catID, $str = '', $base = '?p=220')
    {
        global $db;
        $cat = self::getCategory($catID);
        $str = ' &raquo; <a href="' . $base . '&catID=' . $catID . '">' . $cat['title'] . '</a>' . $str;
        if ($cat['parentID']) {
            return self::getBreadcrumbs($cat['parentID'], $str, $base);
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
        return $db->query("SELECT * FROM kb_cats WHERE catID = " . (int)$catID, SQL_INIT);
    }


} // end of class





