<?php


function getDomainPriceSelectBox($ext)
{
    $e = getDomainExtensions($ext);
    $e = $e[$ext];
    for ($i = 1; $i < $e['periodMax'] + 1; $i ++) {
        $opt .= "<option value='" . $i . "'>" . $i . ' ' . lang('Years') . ' - ' . number_format(
            $e['priceRegister'] + (($i - 1) * $e['priceRenew']),
            2
        ) . " " . $e['symbol'] . "</option>";
    }
    return $opt;
}

function checkDomainAvailability($names, $extensions)
{
    $result = array();

    if (! is_array($names)) {
        $names = array($names);
    }

    foreach ($names as $name) {
        $name = strtolower($name);
        foreach ($extensions as $key => $value) {
            $result["$key"]["domain"] = $name . "." . $value["extension"];
            $result["$key"]["cur"] = $value["symbol"];
            $result["$key"]["priceRegister"] = $value["priceRegister"];
            $result["$key"]["paycurID"] = $value["paycurID"];
            //$result["$key"]["status"] = lookupDomain($result["$key"]["domain"],$result["$key"]["server"]);
        }
    }
    return $result;
}


function lookupDomain($domain)
{
    //return true;
    $dom = explodeDomain($domain);
    if ($dom['domain'] == '' || $dom['ext'] == '') {
        return false;
    }
    require("3rdparty/whois.class.php");
    $whois = new Whois_class();
    if ($whois->checkDomain($dom["domain"], $dom["ext"]) == true) {
        return true;
    } else {
        return false;
    }
}

function explodeDomain($fulldomain)
{
    $dom = explode(".", trim($fulldomain));
    $ret["domain"] = $dom[0];
    if (count($dom) > 2) {
        $ret["ext"] = implode(".", array_slice($dom, 1));
    } else {
        $ret["ext"] = $dom[1];
    }
    return $ret;
}
