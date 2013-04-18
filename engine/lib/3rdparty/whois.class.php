<?php

class Whois_class
{
    var $serverList;

    function __construct()
    {
        $servers = file(dirname(__FILE__) . '/whois.servers.php');
        foreach ($servers as $s) {
            $s = explode("|", $s);
            $s[0] = trim($s[0], ".");
            $this->serverList[$s[0]]["server"] = trim($s[1]);
            $this->serverList[$s[0]]["response"] = trim($s[2]);
        }
    }

    function checkDomain($name, $top)
    {
        $domain = $name . "." . $top;

        $server = $this->serverList[$top]["server"];
        if ($server == '') {
            return false;
        }
        $findText = trim($this->serverList[$top]["response"]);

        try {
            $con = fsockopen($server, 43, $errno, $errstr, 20);
        } catch (Exception $e) {
            return false;
        }

        @fputs($con, $domain . "\r\n");

        $response = "";
        while (! @feof($con)) {
            $response .= @fgets($con, 128);
        }

        // removing all comments from the response
        // this is needed due to some *smart* whois, who have same text saying the domain is availible
        // along with the same text in comments, even if the domain is NOT availible (-;

        $response = preg_replace("/%.*\n/", "", $response);


        /*        echo "checking domain: " . $domain . " @[" . $server . "]" . '<br>';
                echo "looking for: -" . $findText . '-<br>';
                echo $response . '<br>';
                echo 'result: '.stripos($response, $findText,1) . '<br>';

                echo 'test:'.str_replace($findText,'sss',$response);*/

        fclose($con);
        if (stripos($response, $findText) === false) {
            return false;
        } else {
            return true;
        }
    }
}
