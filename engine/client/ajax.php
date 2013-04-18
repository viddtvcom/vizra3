<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');

switch ($_post["action"]) {

    case 'lookup_domain':
        require("func.domain.php");
        $ret["st"] = lookupDomain($_post["domain"]);
        $ret["domain"] = $_post["domain"];
        $ret["key"] = $_post["key"];
        if ($_post['select'] == 'true') {
            $dom = explodeDomain($_post['domain']);
            $ret['select'] = getDomainPriceSelectBox($dom['ext']);
        }
        break;

    case 'refresh_ticket':
        secure();
        $offset = ($_post["offset"] != "") ? $_post["offset"] : 0;

        $sql = "SELECT tr.*,a.adminName,a.adminTitle,a.dateAdded as adateAdded 
                FROM ticket_responses tr
                    INNER JOIN tickets t ON (t.ticketID = tr.ticketID AND t.clientID = " . getClientID() . " )
                    LEFT JOIN admins a ON tr.adminID = a.adminID 
                WHERE tr.ticketID = '" . $_post["ticketID"] . "' AND tr.dateAdded > " . $offset . "
                    AND private = '0'
                ORDER BY tr.dateAdded ASC";
        $ret = (array)$db->query($sql, SQL_ALL);

        foreach ($ret as $key => $resp) {
            if ($resp["adminName"] == "" && $resp["adminID"] == "0") {
                $ret["$key"]["name"] = $_SESSION["vclient"]->name;
                $ret["$key"]["type"] = "";
                $ret["$key"]["title"] = lang("TicketDetails%AccountOwner");
                $ret["$key"]["response"] = nl2br($resp["response"]);
                $ret["$key"]["avatar"] = $_SESSION['vclient']->getAvatarName();
            } else {
                $ret["$key"]["type"] = "admin";
                $ret["$key"]["name"] = $resp["adminName"];
                $ret["$key"]["title"] = $resp["adminTitle"];
                $ret["$key"]["response"] = nl2br($resp["response"]);
                $ret["$key"]["avatar"] = md5('AD' . $resp['adminID'] . "-" . $resp['adateAdded']) . ".jpg";
            }
            $ret["$key"]["response"] = linkify(bb2html($ret["$key"]["response"]));
            $ret["$key"]["timestamp"] = $resp["dateAdded"];
            $ret["$key"]["dateAdded"] = htmlspecialchars(formatDate($resp["dateAdded"], "datetime"));
        }
        break;

    case 'uploadFile':
        secure();
        $T = new ticket($_post["ticketID"]);
        $T->checkOwner();
        if ($_FILES["file"]["name"] != "") {
            $ret = $T->attach(getClientID());
        }
        break;

    case 'uploadAvatar':
        secure();
        $ret = core::uploadImage($_FILES["avatar"]["tmp_name"], $_SESSION["vclient"]->getAvatarName(), 'avatar');
        break;
}

echo json_encode($ret);
