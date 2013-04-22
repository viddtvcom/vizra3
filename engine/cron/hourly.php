<?php


require_once(dirname(__FILE__) . '/../init.php');
require_once("func.admin.php");

cronLock('hourly');

$clients = $db->query("SELECT clientID FROM clients", SQL_ALL);

foreach ($clients as $cli) {
    $c = new Client($cli['clientID']);

    $bal = $c->getBalance();

    $sql = "UPDATE clients SET balance = $bal WHERE clientID = " . $cli['clientID'];
    $db->query($sql);
}

echo 'ok';


cronUnlock('hourly');