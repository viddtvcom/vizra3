<?php
require('engine/init.user.php');

switch ($_get['p']) {
    case 'features';
        $tpl = 'custom/features.tpl';
        break;

    case 'demo';
        $tpl = 'custom/demo.tpl';
        break;

    case 'contact':
        $tpl = 'custom/contact.tpl';
        break;

    case 'bayiler':
        $tpl = 'custom/resellers.tpl';
        break;


}


displayPage($tpl);







