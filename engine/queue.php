<?php

define('VZUSERTYPE', 'admin');

$Queue = new Queue($_get['jobID']);

if ($Queue->code != $_get['code']) {
    die('Not found');
}

$Queue->process();
