<?php
require('engine/init.php');

$MOD = Module::getInstance($_GET['mod']);

if (! method_exists($MOD, 'callback')) {
    die();
}

$MOD->callback();


  
