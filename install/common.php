<?php
require_once('../engine/lib/functions/func.common.php');
require_once('../engine/lib/classes/class.core.php');

$versions = array(
    '3.0.B1' => 'Vizra3 Beta1',
    '3.0.B2' => 'Vizra3 Beta2',
    '3.0.B3' => 'Vizra3 Beta3',
    '3.0.B4' => 'Vizra3 Beta4',
    '3.0.B5' => 'Vizra3 Beta5',
    '3.0.0'  => 'Vizra 3.0.0',
    '3.0.7'  => 'Vizra 3.0.7',
    '3.0.8'  => 'Vizra 3.0.8',
    '3.0.9'  => 'Vizra 3.0.9',
    '3.1.0'  => 'Vizra 3.1.0',
    '3.1.1'  => 'Vizra 3.1.1',
    '3.1.2'  => 'Vizra 3.1.2'
);

$latest = array('version' => '3.1.3', 'title' => 'Vizra 3.1.3');

$dirs = array(
    array('dir' => 'engine/config'),
    array('dir' => 'tmp'),
    array('dir' => 'tmp/smarty'),
    array('dir' => 'logs'),
    array('dir' => 'uploads'),
    array('dir' => 'uploads/avatar'),
    array('dir' => 'uploads/client'),
    array('dir' => 'uploads/service'),
    array('dir' => 'uploads/ticket'),
    array('dir' => 'uploads/files'),
    array('dir' => 'uploads/banners'),
);


function mysql_import_file($filename)
{
    // --------------
    // Open SQL file.
    // --------------
    if (! ($fd = fopen($filename, "r"))) {
        return array('st' => false, 'msg' => 'Dosya açılamadı: ' . $filename);
    }

    // --------------------------------------
    // Iterate through each line in the file.
    // --------------------------------------
    while (! feof($fd)) {

        // -------------------------
        // Read next line from file.
        // -------------------------
        $line = fgets($fd);
        $stmt = "$stmt$line";

        // -------------------------------------------------------------------
        // Semicolon indicates end of statement, keep adding to the statement.
        // until one is reached.
        // -------------------------------------------------------------------
        if (! preg_match("/;/", $stmt)) {
            continue;
        }

        // ----------------------------------------------
        // Remove semicolon and execute entire statement.
        // ----------------------------------------------
        $stmt = preg_replace("/;/", "", $stmt);

        // ----------------------
        // Execute the statement.
        // ----------------------
        // echo '<pre>'.$stmt.'</pre>';
        if (! mysql_query($stmt)) {
            $GLOBALS['errors'][] = $filename . ' dosyasında SQL Hatası: ' . mysql_error();
            //return array('st'=>false,'msg'=>$filename. 'dosyasında SQL Hatası: '.mysql_error ());
        }

        $stmt = "";
    }

    // ---------------
    // Close SQL file.
    // ---------------
    fclose($fd);
    return array('st' => 'true');
}