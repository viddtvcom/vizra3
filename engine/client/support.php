<?php
if (! $_SESSION['vclient']->doesQualifyForSupport()) {
    core::raise('Destek hizmeti içeren bir siparişiniz bulunmuyor', 'e', '?p=user&s=orders');
}

switch ($_get["a"]) {

    case 'viewTicket':
        $T = new Ticket($_get["tID"]);
        if (! $T->ticketID || $T->clientID != CLIENTID) {
            core::raise("##TicketDetails%TicketNotFound", "e");
            redirect("?p=user&s=support");
        }
        $T->set("unread", "0");
        $core->assign("T", $T);
        //$core->assign("responses",getTicketResponses($_get["tID"]));
        $core->assign("attachments", $T->getAttachments());
        $tplContent = "user/ticket_details.tpl";
        break;

    case 'addTicket':
        if ($_post) {
            if (strlen($_post["subject"]) < 5) {
                core::raise("##AddTicket%SubjectNotValid", "e");
                $err = true;
            }
            if (strlen($_post["response"]) < 5) {
                core::raise("##AddTicket%ResponseNotValid", "e");
                $err = true;
            }
            if (! $err) {
                $T = new Ticket();
                $T->create($_post["subject"], $_post["response"], $_post["priority"], CLIENTID, $_post["depID"]);
                if ($_FILES["file"]["name"] != "") {
                    $T->attach(CLIENTID);
                }
                redirect("?p=user&s=support&a=viewTicket&tID=" . $T->ticketID);
            }
        }
        $deps = $db->query("SELECT * FROM departments", SQL_ALL);
        $core->assign("deps", $deps);
        $tplContent = "user/add_ticket.tpl";
        break;

    case 'addResponse':
        // add response
        if (strlen($_post["response"]) > 2) {
            $T = new ticket($_post["ticketID"]);
            $T->checkOwner();
            $T->addResponse($_post["response"], 0, 0, 1);
        }
        break;


    default:
        $core->assign("ticketsInProgress", $_SESSION['vclient']->getClientTickets("!=", "closed", 0));
        $core->assign("lastClosedTickets", $_SESSION['vclient']->getClientTickets("=", "closed", 5));
        $core->assign("closedTickets", $_SESSION['vclient']->getClientTickets("=", "closed", 0));
        $tplContent = "user/support.tpl";
        break;
}
 
  
  