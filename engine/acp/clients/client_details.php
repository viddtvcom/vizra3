<?php


if (isset($_GET['orderID'])) {
    require('orders/order_details.php');
    $Client = $Order->Client;
} else {
    $Client = new Client(trim($_GET["clientID"]));
}


if (! $Client->clientID) {
    core::raise('Böyle bir müşteri kaydı bulunamadı', 'e', '?p=310');
}

if ($Client->fnote != '') {
    core::raise($Client->fnote, 'w');
}

if ($_GET['act'] == 'login') {
    $_SESSION['vclient'] = $Client;
    redirect($config['HTTP_HOST'] . '/?p=user');
}

$Client->loadExtras();

if ($_POST['action'] == 'Sil') {
    if (! $_POST['selected']) {
        core::raise('En az bir sipariş seçmelisiniz', 'e', '?p=410');
    }
    foreach ($_POST['selected'] as $orderID) {
        $ORD = new Order($orderID);
        $ORD->destroy();
        core::raise($orderID . ' nolu sipariş (' . $ORD->title . ') sistemden silindi', 'm');
    }

} elseif ($_POST["action"] == "update") {
    $Client->replace($_POST)->replaceExtras($_POST)->update();
    if ($_POST['password'] != '') {
        $Client->setPassword($_POST['password']);
    }
    core::raise('Müşteri bilgileri güncellendi', 'm');

} elseif ($_POST['action'] == 'add_gen_bill') {
    if ((float)$_POST['amount'] <= 0) {
        core::raise(
            'Lütfen sıfırdan büyük bir sayı giriniz',
            'e',
                '?p=311&clientID=' . $Client->clientID . '&tab=bills'
        );
    }
    $OB = new OrderBill();
    $OB->type = 'onetime';
    $OB->paycurID = $_POST['paycurID'];
    $OB->create();
    $OB->clientID = $Client->clientID;
    $OB->dateStart = $OB->dateEnd = $OB->dateDue = time();
    $OB->amount((float)$_POST['amount']);
    $OB->paymentID = 0;
    $OB->status = 'unpaid';
    $OB->update();
    core::raise('Genel borç kaydı eklendi', 'm', '?p=516&billID=' . $OB->billID);

} elseif ($_POST['action'] == 'add_payment') {
    if ((float)$_POST['amount'] <= 0) {
        core::raise(
            'Lütfen sıfırdan büyük bir sayı giriniz',
            'e',
                '?p=311&clientID=' . $Client->clientID . '&tab=payments'
        );
    }
    $paymentID = Payment::addPayment(
        $Client->clientID,
        'offline',
        'pending',
        0,
        $_POST['amount'],
        $_POST['paycurID'],
        '',
        false
    );

    core::raise('Ödeme eklendi', 'm', '?p=511&paymentID=' . $paymentID);

} elseif ($_POST['action'] == 'add_ticket') {
    if (! $_POST["depID"]) {
        core::raise('Lütfen bir departman seçiniz', 'e', '');
    }
    $T = new Ticket();
    $T->create($_POST["subject"], $_post["response"], $_POST['priority'], $Client->clientID, $_POST["depID"]);
    $T->set('status', 'awaiting-reply');
    $EMT = new Email_template(6);
    $EMT->ticketID = $T->ticketID;
    $EMT->send();
    redirect('?p=212&ticketID=' . $T->ticketID);

} elseif ($_POST['action'] == 'sendmail') {
    $EM = new Email_template($_POST['templateID']);
    $EM->clientID = $_GET['clientID'];
    if ($_POST['templateID'] == 9) {
        $EM->replaces['Client_password'] = $Client->getPassword();
    }
    $EM->send();
    core::raise('Email gönderildi: ' . $EM->title, 'm', '');

} elseif ($_POST['action'] == 'sendbill') {
    OrderBill::sendbill($_POST['billID']);
    core::raise('Ödeme hatırlatma maili gönderildi', 'm', '');

} elseif ($_POST['action'] == 'delbill') {
    OrderBill::delete($_POST['billID']);
    core::raise('Borç kaydı sistemden silindi', 'm');
}

if ($_POST) {
    redirect('?p=311&clientID=' . $Client->clientID . '&tab=' . $_GET['tab']);
}

switch ($_GET["tab"]) {

    case 'orders':
        if (isset($_GET['orderID'])) {

        } else {
            if ($_GET['status'] == 'valid') {
                $conditions .= " AND o.status IN ('active', 'suspended', 'pending-provision')";
            } elseif ($_GET['status'] != "all" && $_GET['status'] != '') {
                $conditions .= " AND o.status = '" . $_GET['status'] . "'";
            }
            if ($_GET['title'] != '') {
                $conditions .= " AND (o.title LIKE '%" . $_GET['title'] . "%' OR oa.value LIKE '%" . $_GET['title'] . "%')";
                $joins .= " LEFT JOIN order_attrs oa ON (oa.orderID = o.orderID)";
                $group_by = " GROUP BY o.orderID";
            }
            $sql = "SELECT o.* FROM orders o
                         $joins
                    WHERE o.clientID = " . $Client->clientID . " AND o.parentID = 0
                    $conditions
                    $group_by
                    ORDER BY o.dateAdded DESC";
            $pag = paging($sql, 0, 20, 'o.orderID');
            $pag['url'] = '?p=311&tab=orders&clientID=' . $Client->clientID;
            $core->assign('pag', $pag);
            $orders = $db->query($sql . $pag['limit'], SQL_ALL);

            $core->assign("orders", $orders);
            $core->assign('icons', Order::$icons);

        }
        $subtpl = "client_orders";
        break;

    case 'bills':
        $sql = "SELECT ob.*,o.title AS orderTitle FROM order_bills ob
                    LEFT JOIN orders o ON ob.orderID = o.orderID 
                WHERE (ob.clientID = " . $Client->clientID . " OR o.clientID = " . $Client->clientID . ")
                ORDER BY ob.status DESC, ob.dateDue ASC";
        $pag = paging($sql, 0, 20, 'ob.billID');
        $pag['url'] = '?p=311&tab=bills&clientID=' . $Client->clientID;
        $core->assign('pag', $pag);
        $bills = $db->query($sql . $pag['limit'], SQL_ALL);
        $core->assign('bills', $bills);
        $subtpl = "client_bills";
        break;

    case 'payments':
        $sql = "SELECT p.* FROM payments p WHERE p.clientID = " . $Client->clientID . " ORDER BY p.dateAdded DESC";
        $pag = paging($sql, 0, 20, 'p.paymentID');
        $pag['url'] = '?p=311&tab=payments&clientID=' . $Client->clientID;
        $core->assign('pag', $pag);
        $pag['url'] = '?p=311&tab=payments&clientID=' . $Client->clientID;
        $payments = $db->query($sql . $pag['limit'], SQL_ALL);
        $core->assign('payments', $payments);
        $core->assign('modules', Module::getModuleTitles('payment'));
        $subtpl = "client_payments";
        break;

    case 'domains':
        $sql = "SELECT * FROM domains d
                    INNER JOIN orders o ON d.orderID = o.orderID 
                WHERE o.clientID = " . $Client->clientID . "
                ORDER BY d.dateAdded DESC";
        $pag = paging($sql, 0, 20, 'd.domainID');
        $pag['url'] = '?p=311&tab=domains&clientID=' . $Client->clientID;
        $core->assign('pag', $pag);
        $domains = $db->query($sql . $pag['limit'], SQL_ALL);
        $core->assign("domains", $domains);
        $core->assign('icons', Domain::$icons);
        $subtpl = "client_domains";
        break;

    case 'tickets':
        $sql = "SELECT t.*, a.adminNick, d.depTitle FROM tickets t
                 INNER JOIN departments d ON t.depID = d.depID
                 LEFT JOIN admins a ON t.adminID = a.adminID
                 WHERE t.clientID = " . $Client->clientID . " ORDER BY t.dateUpdated DESC";
        $pag = paging($sql, 0, 20, 't.ticketID');
        $pag['url'] = '?p=311&tab=tickets&clientID=' . $Client->clientID;
        $core->assign('pag', $pag);
        $tickets = $db->query($sql . $pag['limit'], SQL_ALL);
        $core->assign('tickets', $tickets);
        $subtpl = 'client_tickets';
        break;

    case 'accflow':
        require('client_details_accflow.php');
        $subtpl = 'client_accflow';
        break;
    default:
        $core->assign('countries', getCountries());
        $subtpl = "client_general";
        break;
}

$tpl_content = "client_details";
$core->assign("subtpl", "clients/" . $subtpl . ".tpl");
$core->assign("client", $Client);
$core->assign("select_status", array2select($vars["STATUS_TYPES_CLIENT"], "status", - 1, "", $Client->status));

$core->assign(
    "select_currencies",
    array2select(core::get_currencies(), "paycurID", "curID", "symbol", $Service->paycurID)
);

$page_title = $Client->type == 'individual' ? $Client->name : $Client->company;

$deps = $db->query("SELECT * FROM departments ORDER BY depTitle", SQL_ALL);
$core->assign('deps', $deps);

$core->assign('emails', Email_template::getEmails(array('user', 'custom')));
