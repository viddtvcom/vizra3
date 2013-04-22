<?php

function sm_format_date($params, &$smarty)
{
    return formatDate($params["date"], $params["mode"], $params["type"]);
}

function sm_readbit($params, &$smarty)
{
    if (priv_readbit($params["priv"], $params["bit"]) == 1) {
        return "CHECKED";
    }
}

function sm_prefilter_pre01($source, &$smarty)
{
    $lictype = $GLOBALS['_VLC_TYPE'];
    //$lictype = core::_vlc_gettype();
    if ($lictype != 'monthly' && $lictype != 'owned' && $lictype != 'nobrand') {
        $source = preg_replace('/<title>(.+?)<\/title>/', '<title>$1 - Vizra ' . VVERSION . '</title>', $source);
    }
    return preg_replace_callback('/##(.+?)##/', '_compile_lang', $source);
}

function _compile_lang($key)
{
    return lang($key[1]);
}

function sm_getCurrencyById($params, &$smarty)
{
    return core::getCurrencyById($params["id"]);
}

function sm_formatId($params, &$smarty)
{
    $id = substr($params["id"], 0, 3) . "." . substr($params["id"], 3, 2) . "." . substr($params["id"], 5, 2);
    return $id;
}

function sm_formdisplay($input)
{
    if (get_magic_quotes_gpc()) {
        $input = stripslashes($input);
    }
    $output = htmlspecialchars($input);
    return $output;
}


