<?php

require('engine/init.php');
set_include_path(get_include_path() . PATH_SEPARATOR . $config["BASE_PATH"] . 'engine/client');
require_once("func.user.php");


if ($_GET['src'] == 'dc') {
    $DC = new Download($_GET['id']);
    if (! $DC->permcheck()) {
        die('Not found');
    }
    $DC->downloaded();
    $syspath = $config['DOWNLOADS_DIR'] . $DC->sysname . '.' . $DC->extension;
    $origname = $DC->origname . '.' . $DC->extension;

} else {
    secure();
    $sql = "SELECT * FROM files WHERE clientID = " . getClientID() . " AND fileID = " . intval($_GET["id"]);
    $sql = "SELECT f.* FROM files f
                INNER JOIN ticket_attachments ta ON ta.fileID = f.fileID 
                INNER JOIN tickets t ON t.ticketID = ta.ticketID
            WHERE t.clientID = " . getClientID() . " AND f.fileID = " . intval($_GET["id"]);

    $file = $db->query($sql, SQL_INIT);

    if (! $file) {
        die('Not found');
    }
    $syspath = $config['UPLOADS_DIR'] . 'ticket' . DIRECTORY_SEPARATOR . $file['sysname'];
    $origname = $file['origname'];
}


if (! file_exists($syspath)) {
    die('Not found!');
}


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header("Content-Disposition: attachment; filename=\"" . $origname . "\"");
header("Content-length: " . filesize($syspath));

ob_clean();
flush();
readfile($syspath);

