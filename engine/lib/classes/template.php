<?php
class tmp extends base
{

    public $ID;


    function admin($id = 0)
    {
        $this->db_table = "admins";
        $this->ID_field = "adminID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = explode(",", "");
        if ($id) {
            $this->load($id);
        }
    }


} // end of class





