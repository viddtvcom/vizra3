<?php
require_once(dirname(__FILE__) . '/../engine/init.php');

if ($_GET['key'] != get_cron_key()) {
    header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}


switch ($_GET['t']) {
    case 'minutely':
        require(dirname(__FILE__) . '/../engine/cron/minutely.php');
        break;

    case 'daily':
        require(dirname(__FILE__) . '/../engine/cron/daily.php');
        break;
}      
    






