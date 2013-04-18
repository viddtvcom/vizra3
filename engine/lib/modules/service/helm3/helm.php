<?

function helm_configoptions()
{
    $configarray = array(
        'Package ID'       => array('Type' => 'text', 'Size' => '25'),
        'Package Name'     => array('Type' => 'text', 'Size' => '25'),
        'Reseller Plan ID' => array('Type' => 'text', 'Size' => '25', 'Description' => 'Only if reseller account')
    );
    return $configarray;
}

function helm_clientarea($params)
{
    global $_LANG;
    $code = '<form action="http://' . $params['serverip'] . '/" method="post" target="_blank"><input type="submit" value="Helm" class="button"></form>';
    return $code;
}

function helm_adminlink($params)
{
    $code = '<form action="http://' . $params['serverip'] . '/" method="post" target="_blank"><input type="submit" value="Helm"></form>';
    return $code;
}

function helm_createaccount($params)
{
    global $debug_output;
    if ($params['clientsdetails']['country'] == 'UK') {
        $params['clientsdetails']['country'] = 'GB';
    }

    $url = 'http://' . $params['serverip'] . '/billing_api.asp';
    $query_string = 'action=AddUser&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
    $query_string .= '&FirstName=' . urlencode($params['clientsdetails']['firstname']);
    $query_string .= '&LastName=' . urlencode($params['clientsdetails']['lastname']);
    $query_string .= '&PrimaryEmail=' . $params['clientsdetails']['email'];
    $query_string .= '&Address1=' . urlencode($params['clientsdetails']['address1']);
    $query_string .= '&PostCode=' . urlencode($params['clientsdetails']['postcode']);
    $query_string .= '&CountryCode=' . $params['clientsdetails']['country'];
    $query_string .= '&CompanyName=' . urlencode($params['clientsdetails']['companyname']);
    $query_string .= '&Town=' . urlencode($params['clientsdetails']['city']);
    $query_string .= '&County=' . urlencode($params['clientsdetails']['state']);
    $query_string .= '&HomePhone=' . $params['clientsdetails']['phonenumber'];
    $query_string .= '&NewAccountNumber=' . $params['username'];
    $query_string .= '&NewAccountPassword=' . urlencode($params['password']);
    if ($params['configoption3']) {
        $query_string .= '&ResellerPlanId=' . $params['configoption3'];
    } else {
        $query_string .= '&ResellerPlanId=0';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $data = curl_error($ch);
    }

    curl_close($ch);
    $data = xmltoarray($data);
    if ($debug_output == 'on') {
        echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
        print_r($data);
        echo '</textarea>';
    }

    if ($data['H1']) {
        return $data['H1'];
    }

    if ($data['RESULTS']['RESULTCODE'] == '0') {
        $query = 'UPDATE tblhosting SET username=\'' . $data['RESULTS']['RESULTDATA'] . '\' WHERE id=\'' . $params['accountid'] . '\'';
        $result = mysql_query($query);
        $url = 'http://' . $params['serverip'] . '/billing_api.asp';
        $query_string = 'action=AddPackage&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
        $query_string .= '&UserAccountNumber=' . $data['RESULTS']['RESULTDATA'];
        $query_string .= '&PackageTypeId=' . $params['configoption1'];
        $query_string .= '&FriendlyName=' . $params['configoption2'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $data = curl_error($ch);
        }

        curl_close($ch);
        $data = xmltoarray($data);
        if ($debug_output == 'on') {
            echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
            print_r($data);
            echo '</textarea>';
        }

        $packageid = $data['RESULTS']['RESULTDATA'];
        $url = 'http://' . $params['serverip'] . '/billing_api.asp';
        $query_string = 'action=AddDomain&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
        $query_string .= '&PackageId=' . $packageid;
        $query_string .= '&DomainName=' . $params['domain'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $data = curl_error($ch);
        }

        curl_close($ch);
        $data = xmltoarray($data);
        if ($debug_output == 'on') {
            echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
            print_r($data);
            echo '</textarea>';
        }

        return 'success';
    }

    return $data['RESULTS']['RESULTCODE'] . ' - ' . $data['RESULTS']['RESULTDESCRIPTION'];
}

function helm_terminateaccount($params)
{
    global $debug_output;
    $url = 'http://' . $params['serverip'] . '/billing_api.asp';
    $query_string = 'action=DeleteUser&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
    $query_string .= '&UserAccountNumber=' . $params['username'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $data = curl_error($ch);
    }

    curl_close($ch);
    $data = xmltoarray($data);
    if ($debug_output == 'on') {
        echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
        print_r($data);
        echo '</textarea>';
    }

    if ($data['RESULTS']['RESULTCODE'] == '0') {
        return 'success';
    }

    return $data['RESULTS']['RESULTCODE'] . ' - ' . $data['RESULTS']['RESULTDESCRIPTION'];
}

function helm_suspendaccount($params)
{
    global $debug_output;
    $url = 'http://' . $params['serverip'] . '/billing_api.asp';
    $query_string = 'action=SuspendUser&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
    $query_string .= '&UserAccountNumber=' . $params['username'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $data = curl_error($ch);
    }

    curl_close($ch);
    $data = xmltoarray($data);
    if ($debug_output == 'on') {
        echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
        print_r($data);
        echo '</textarea>';
    }

    if ($data['RESULTS']['RESULTCODE'] == '0') {
        return 'success';
    }

    return $data['RESULTS']['RESULTCODE'] . ' - ' . $data['RESULTS']['RESULTDESCRIPTION'];
}

function helm_unsuspendaccount($params)
{
    global $debug_output;
    $url = 'http://' . $params['serverip'] . '/billing_api.asp';
    $query_string = 'action=UnsuspendUser&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
    $query_string .= '&UserAccountNumber=' . $params['username'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $data = curl_error($ch);
    }

    curl_close($ch);
    $data = xmltoarray($data);
    if ($debug_output == 'on') {
        echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
        print_r($data);
        echo '</textarea>';
    }

    if ($data['RESULTS']['RESULTCODE'] == '0') {
        return 'success';
    }

    return $data['RESULTS']['RESULTCODE'] . ' - ' . $data['RESULTS']['RESULTDESCRIPTION'];
}

function helm_changepassword($params)
{
    global $debug_output;
    $url = 'http://' . $params['serverip'] . '/billing_api.asp';
    $query_string = 'action=UpdateUserPassword&Username=' . $params['serverusername'] . '&Password=' . $params['serverpassword'];
    $query_string .= '&UserAccountNumber=' . $params['username'];
    $query_string .= '&NewAccountPassword=' . $params['password'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $data = curl_error($ch);
    }

    curl_close($ch);
    $data = xmltoarray($data);
    if ($debug_output == 'on') {
        echo(('' . '<textarea cols=120 rows=10>' . $query_string . '
') . '
');
        print_r($data);
        echo '</textarea>';
    }

    if ($data['RESULTS']['RESULTCODE'] == '0') {
        return 'success';
    }

    return $data['RESULTS']['RESULTCODE'] . ' - ' . $data['RESULTS']['RESULTDESCRIPTION'];
}

?>