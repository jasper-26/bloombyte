<?php
require('DBconfig.php');

if (isset($_POST['Name'])) {
    try {
        $ip = get_client_ip();
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $mobil = $_POST['MobileNo'];
        $Bname = $_POST['BName'];
        $skype = $_POST['skypeid'];
        $message = $_POST['Message'];
        $sendmessage = "";
        
        $utmid  =   $_POST['utmid'];
        $utmSource = $_POST['utmSource'];
        $utmMedium = $_POST['utmMedium'];
        $utmCampaign = $_POST['utmCampaign'];

        $ipinfo_url = "https://ipinfo.io/{$ip}/json";
        $response = file_get_contents($ipinfo_url);
        $data = json_decode($response);
        $city = isset($data->city) ? $data->city : "none";
        $country = isset($data->country) ? $data->country : "none";

        $resData = array(
            "data" => array(
                "name" => $name,
                "email" => $email,
                "phoneno" => $mobil,
                "location" => $city,
                "businessname" => $Bname,
                "skypeid" => $skype,
                "country" => $country,                
                "description" => $message,
                "utmid" => $utmid,
                "utmSource" => $utmSource,
                "utmMedium" => $utmMedium,
                "utmCampaign" => $utmCampaign,
                "pageid" => 'Demo'
            ),
            "user" => 'User',
            "key" => 'c2h4u6tin9bfvlz67kTWgdi4t5sO'
        );

        $params = array(
            "method" => 'demo_requestNew',
            "input_type" => "JSON",
            "response_type" => "JSON",
            "rest_data" => json_encode($resData),
        );

        $Crmapicall = apiCall('https://crm.bloombyte.io/custom/service/v4_1_custom/rest.php', $params);
        echo $Crmapicall;
        exit;
    } catch (Exception $e) {
        return false;
    }
}

function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function apiCall($url, $arguments)
{
    try {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $arguments);
        $result = curl_exec($curl);
        if (!curl_errno($curl)) {
            curl_close($curl);
            return json_decode($result, 1);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}


function restRequest($url, $arguments)
{
    try {
        $headers = array('Content-Type: application/json');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $arguments);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        if (!curl_errno($curl)) {
            curl_close($curl);
            return json_decode($result, 1);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['username'])) {
    try {
        session_start();
        $name = $_POST['username'];
        $pwd = $_POST['password'];
        $sql = $connDB->query("SELECT Status FROM bloombyte_rpt_login WHERE Status = 1 AND UserName = '" . $name . "' AND Password = '" . $pwd . "'");
        if ($sql->num_rows > 0) {
            $_SESSION["username"] = $name;
            echo true;
            die;
        } else {
            echo false;
            die;
        }
    } catch (Exception $e) {
        return false;
    }
}
