<?php


if (isset($_GET["ticketID"])) {
    $Ticket = new Ticket($_GET["ticketID"]);
    if (! isset($Ticket->clientID)) {
        core::raise('Böyle bir bilet kaydı bulunamadı', 'e', '?p=211');
    }
    if ($_SESSION['vadmin']->type != 'super-admin' && ! in_array($Ticket->depID, $_SESSION['vadmin']->getDeps())) {
        core::raise('Bağlı olmadığınız departmana ait bir bileti görüntüleyemezsiniz', 'e', '?p=211');
    }
}


switch ($_POST['action']) {
    case 'refresh_ticket':
        $offset = ($_POST["offset"] != "") ? $_POST["offset"] : 0;

        $sql = "SELECT tr.*,a.adminName,a.adminTitle 
                FROM ticket_responses tr
                    LEFT JOIN admins a ON tr.adminID = a.adminID 
                WHERE tr.ticketID = '" . $_GET["ticketID"] . "' AND tr.dateAdded > " . $offset . "
                ORDER BY tr.dateAdded ASC";
        $ret = (array)$db->query($sql, SQL_ALL);

        foreach ($ret as $key => $response) {
            if ($response["adminName"] == "" && $response["adminID"] == "0") {
                //$ret["$key"]["name"] = $_SESSION["vclient"]->name; // ????????????????????????????????
                $ret["$key"]["type"] = "";
                $ret["$key"]["title"] = lang("TicketDetails%AccountOwner");
            } else {
                $ret["$key"]["type"] = ($response['private']) ? 'priv' : 'admin';
                $ret["$key"]["name"] = $response["adminName"];
                $ret["$key"]["title"] = $response["adminTitle"];
            }
            $ret["$key"]["timestamp"] = $response["dateAdded"];
            //$ret["$key"]["response"] = htmlspecialchars($response["response"]);
            $ret["$key"]["response"] = linkify(nl2br(bb2html($response["response"])), true);

            $ret["$key"]["dateAdded"] = htmlspecialchars(formatDate($response["dateAdded"], "datetime"));
        }

        $details['status'] = $Ticket->status;
        $details['depID'] = $Ticket->depID;
        $details['adminID'] = $Ticket->adminID;
        $details['priority'] = $Ticket->priority;
        echo json_encode(array('messages' => $ret, 'details' => $details));
        exit();

    case 'update_ticket':
        // bilet atama
        if ($_POST['name'] == 'adminID' && $_POST['value'] != '-1') {
            $Ticket->assign($_POST['value']);
        } else {
            $Ticket->set($_POST['name'], $_POST['value']);
        }

        $ret['st'] = 'ok';
        $ret['id'] = $_POST['id'];
        echo json_encode($ret);
        exit();

    case 'ticket_details':
        $ret['status'] = $Ticket->status;
        $ret['depID'] = $Ticket->depID;
        $ret['adminID'] = $Ticket->adminID;
        $ret['priority'] = $Ticket->priority;
        echo json_encode($ret);
        exit();

    case 'add_response':
        if (! $_POST['message']) {
            exit();
        }

        if ($_POST['private'] == '1') {
            $Ticket->addResponse($_POST['message'], getAdminID(), 1);
        } else {
            $Ticket->setas_awaiting_reply = $_POST['setas_awaiting_reply'];
            $Ticket->addResponse($_POST['message'], getAdminID());


            $msg = '@<a target="mainframe" href="?p=212&ticketID=' . $Ticket->ticketID . '">' . $Ticket->ticketID . '</a>:';
            $msg .= ' <strong><span style="color:gray;">' . substr(
                sanitize($_POST["message"]),
                0,
                250
            ) . '</span></strong>';
            $sql = "INSERT INTO chat (adminID,message,dateAdded) 
                    VALUES (" . getAdminID() . ",'" . $msg . "',UNIX_TIMESTAMP())";
            $db->query($sql);
        }
        echo json_encode($ret);
        exit();

    case 'update_response':
        $ret = $Ticket->updateResponse($_POST['responseID'], $_POST['response']);
        $ret['id'] = $_POST['responseID'];
        echo json_encode($ret);
        exit();

    case 'upload_file':
        if ($_FILES["file"]["name"] != "") {
            $ret = $Ticket->attach(getAdminID());
        }
        echo json_encode($ret);
        exit();
}


$core->assign('T', $Ticket);


require('3rdparty/mobile_device_detect.php');
$core->assign('mobile', mobile_device_detect(true, true, true, true, true, true, true, false, false));

$deps = $db->query("SELECT * FROM departments ORDER BY depTitle", SQL_ALL);
$core->assign('deps', $deps);

$admins = $db->query("SELECT adminID,adminNick FROM admins WHERE status = 'active'  ORDER BY adminNick", SQL_ALL);
$core->assign('admins', $admins);

$core->assign("attachments", $Ticket->getAttachments());
$tpl_content = 'ticket_details';

$core->assign('qreps', $_SESSION['vadmin']->getQreps());

$page_title .= ' > <a href="index.php?p=212&ticketID=' . $Ticket->ticketID . '">' . $Ticket->ticketID . '</a>';



