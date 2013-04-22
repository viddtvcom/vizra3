<?php
require('engine/init.php');
if (! $_post) {
    $_post = $_get;
}

$clientID = Client::authenticate($_post["email"], $_post["password"]);

if ($clientID > 0) {
    $MOD = Module::getInstance($_post['module']);

    if (! method_exists($MOD, 'api')) {
        displayError('Not available');
    }
    if (! method_exists($MOD, 'api_' . $_post['cmd'])) {
        displayError('CMD Not available');
    }

    try {
        $_post['clientID'] = $clientID;
        $ret = $MOD->api($_post);
        displayData($ret);
    } catch (Exception $e) {
        displayError($e->getMessage());
    }

} else {
    displayError('Invalid credentials');
}


function displayError($error)
{
    displayJSON(array('status' => '0', 'error' => $error));
}

function displayData($data, $status = 1)
{
    displayJSON(array('status' => $status, 'data' => $data));
}
