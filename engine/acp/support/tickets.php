<?php


$core->assign('admin_deps', $_SESSION['vadmin']->getDeps());
$core->assign('show_tickets', $_SESSION['vadmin']->settings['ticketListing_status']);


$tpl_content = "tickets";



