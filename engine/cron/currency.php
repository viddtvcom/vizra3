<?php
require(dirname(__FILE__) . '/../init.php');
require_once("func.admin.php");

$MOD = Module::getInstance('currency');
$MOD->update();

$db->query("UPDATE crons SET dateStart = UNIX_TIMESTAMP() WHERE filename = 'currency.php'");