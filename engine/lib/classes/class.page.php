<?php
class Page extends base
{

    public $pageID;
    public $parentID;
    public $moduleID;
    public $actions;
    public $filename;
    public $showOnSubmenu;

    public $dateAdded;
    public $dateUpdated;

    function Page($id = "")
    {
        $this->db_table = "pages";
        $this->ID_field = "pageID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = explode(
            ",",
            "pageID,parentID,moduleID,actions,filename,showOnSubmenu,dateAdded,dateUpdated"
        );
        if ($id) {
            $this->load($id);
        }
    }


    function addPin()
    {
        global $db;
        $maxBit = $db->query(
            "SELECT MAX(bit) AS maxBit FROM pages WHERE moduleID = " . $this->moduleID,
            SQL_INIT,
            "maxBit"
        );
        $db->query("UPDATE pages SET bit = " . ($maxBit + 1) . " WHERE pageID = " . $this->pageID);
    }


} // end of class