<?php
class Ticket extends base
{

    public $ticketID;
    public $clientID;
    public $depID;
    public $adminID;
    public $subject;
    public $status;
    public $priority;
    public $unread;
    public $archived;
    public $sticky;
    public $responses;
    public $dateAdded;
    public $dateUpdated;

    public $clientName;
    //static public $status_types = array('closed', 'new', 'client-responded', 'awaiting-reply', 'investigating');

    // table names;
    private $tblResponses = "ticket_responses";

    function ticket($id = "")
    {
        $this->db_table = "tickets";
        $this->ID_field = "ticketID";
        $this->_dateAdded = "dateAdded";
        $this->_dateUpdated = "dateUpdated";
        $this->db_members = explode(
            ",",
            "ticketID,clientID,depID,adminID,subject,status,priority,unread,dateAdded,dateUpdated"
        );
        if ($id) {
            $this->load($id);
        }
    }

    function load($id = "")
    {
        parent::load($id);
        $this->Client = new Client($this->clientID);
        //$this->clientName = client::factory("client",$this->clientID)->get("name");
    }

    function create($subject, $response, $priority, $clientID, $depID = 1)
    {
        global $db;
        $this->ticketID = $this->generateTicketID();
        $sql = sprintf(
            "INSERT INTO " . $this->db_table . "(ticketID,clientID,depID,subject,priority,dateUpdated,dateAdded)
                    VALUES ('%s',%s,%s,'%s','%s',UNIX_TIMESTAMP(),UNIX_TIMESTAMP())",
            $this->ticketID,
            $clientID,
            $depID,
            sanitize($subject),
            $priority
        );
        $db->query($sql);
        $this->load();
        if (VZUSERTYPE == 'admin') {
            $this->addResponse($response, getAdminID(), 0, 0, true);
        } else {
            $this->addResponse($response, 0, 0, 0, true);
            // notify department
            $Dep = new Department($depID);
            $Dep->notifyNewTicket($this);
            $this->notify();
        }
        return $ticketID;
    }

    function create2($ticketID)
    {
        global $db;
        $db->query("INSERT INTO tickets (ticketID) VALUES ('" . $ticketID . "')");
        $this->ticketID = $ticketID;
    }

    function checkOwner()
    {
        if (isset($_SESSION["vclient"]) && $_SESSION["vclient"]->clientID != $this->clientID) {
            die("authentication error..");
        }
    }

    function addResponse($response, $adminID, $private = "0", $notify = "1", $newticket = false)
    {
        global $db;
        $origStatus = $this->status;
        if ($adminID > 0) {
            // islemi yapan admin
            if (! $private) {
                if ($this->unread == "0" && $notify == "1") {
                    $EMT = new Email_template(8);
                    $EMT->ticketID = $this->ticketID;
                    $ret = $EMT->send();
                }
                if ($this->status != 'closed') {
                    if ($this->setas_awaiting_reply == '1') {
                        $this->status = "awaiting-reply";
                    }
                }
                $this->unread = "1";
            }
        } else {
            // islemi yapan musteri
            if ($this->status != "new") {
                $this->status = "client-responded";
            }
            $this->notify_update($response);
            $adminID = 0;
        }
        $sql = "INSERT INTO " . $this->tblResponses . " (ticketID,adminID,response,dateAdded,private,ip)
            VALUES ('" . $this->ticketID . "',$adminID,'" . $response . "',UNIX_TIMESTAMP(),'" . $private . "','" . getenv(
            REMOTE_ADDR
        ) . "')";

        //debug($sql,1);
        $db->query($sql);


        $this->responses += 1;

        if ($newticket) {
            $this->status = "new";
        }
        /*    if ($this->sticky == "yes" && $this->status != "closed") {
                $this->status = $origStatus;
            }*/
        $this->update();
        return $this;


    }

    function updateResponse($responseID, $response)
    {
        global $db;
        /*    $adminID = $db->query("SELECT adminID FROM ticket_responses WHERE responseID = ".$responseID,SQL_INIT,'adminID');
            if (!priv2(6,0) && $adminID != $_SESSION['User']['uid']) {
                return array('st'=>false,'msg'=>'Size ait olmayan bir cevabı güncelleyemezsiniz!!');
            }*/
        $sql = "UPDATE ticket_responses SET response = '" . $response . "' WHERE responseID =" . $responseID;
        $db->query($sql);
        return array('st' => true);
    }

    function generateTicketID()
    {
        global $db;
        mt_srand((double)microtime() * 1000000);
        $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        while (strlen($ticketID) < 3) {
            $ticketID .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }
        $ticketID .= "-";
        $possible = "0123456789";
        while (strlen($ticketID) < 9) {
            $ticketID .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }
        $row = $db->query("SELECT ticketID FROM " . $this->db_table . " WHERE ticketID = '$ticketID'", SQL_INIT);
        if ($row != "") {
            return $this->generateTicketID();
        } else {
            return $ticketID;
        }
    }

    function attach($uploaderID)
    {
        $ret = core::uploadFile("file", "ticket", $uploaderID);
        if ($ret["st"]) {
            global $db;
            $db->query(
                "INSERT INTO ticket_attachments (fileID,ticketID) VALUES ('" . $ret["fileID"] . "','" . $this->ticketID . "')"
            );
        }
        return $ret;
    }

    function getAttachments()
    {
        global $db;
        $sql = "SELECT * FROM ticket_attachments ta
                INNER JOIN files f ON f.fileID = ta.fileID
            WHERE ta.ticketID = '" . $this->ticketID . "'";
        $files = $db->query($sql, SQL_ALL);
        return $files;
    }

    function notify()
    {
        global $config;
        $notify = getSetting('notify_newticket', true);
        if ($notify[0] == '1') {
            $body = "Yeni bilet açıldı (" . $this->subject . "), detaylar için <a href='" . $config['HTTP_HOST'] . "acp/?p=212&ticketID=" . $this->ticketID . "'>buraya</a> tıklayınız";
            core::send_mail('Yeni bilet açıldı', getSetting('notify_notifymail'), $body);
        }
        if ($notify[1] == '1') {
            core::send_sms("Yeni bilet açıldı (" . $this->subject . ")", getSetting('notify_notifycell'));
        }
        if ($notify[2] == '1') {
            $body = "Yeni bilet açıldı (" . $this->subject . "), detaylar için: " . $config['HTTP_HOST'] . "acp/?p=212&ticketID=" . $this->ticketID;
            core::send_msn($body, getSetting('notify_notifymsn'));
        }
    }

    function notify_update($response)
    {
        if (! $this->adminID) {
            return;
        }
        global $db, $config;
        $adminMsn = Admin::getPropertyByAdminId($this->adminID, 'adminMsn');
        if (! $adminMsn) {
            return;
        }

        $body = "(" . $this->subject . ") konulu bilete cevap verildi:";
        $body .= "\n" . $response;
        $body .= "\n\nDetaylar için: " . $config['HTTP_HOST'] . "acp/?p=212&ticketID=" . $this->ticketID;
        core::send_msn($body, $adminMsn);
    }

    function assign($adminID)
    {
        $this->set('adminID', $adminID);

        // msn notify? (atayan kendim degilse)
        if (getSetting('notify_ticket_assignment') == '1' && getAdminID() != $adminID) {
            global $db, $config;
            $adminMsn = Admin::getPropertyByAdminId($adminID, 'adminMsn');
            if (! $adminMsn) {
                return;
            }

            $prev_admin = Admin::getPropertyByAdminId(getAdminID(), 'adminNick');

            $body = "(" . $this->subject . ") konulu bilet " . $prev_admin . " tarafından size atandı:";
            core::send_msn($body, $adminMsn);
        }
    }

    function destroy()
    {
        global $db, $config;
        $sql = "SELECT sysname FROM files f INNER JOIN ticket_attachments ta ON f.fileID = ta.fileID WHERE ta.ticketID = '" . $this->ticketID . "'";
        $files = $db->query($sql, SQL_KEY, 'sysname');
        foreach ($files as $sysname) {
            unlink($config['UPLOADS_DIR'] . 'ticket' . DIRECTORY_SEPARATOR . $sysname);
        }
        $db->query(
            "DELETE t, tr, ta, f FROM tickets t
                                LEFT JOIN ticket_attachments ta ON t.ticketID = ta.ticketID
                                LEFT JOIN ticket_responses tr ON tr.ticketID = t.ticketID
                                LEFT JOIN files f ON f.fileID = ta.fileID
                                WHERE t.ticketID = '" . $this->ticketID . "'"
        );

    }


} // end of class ticket
