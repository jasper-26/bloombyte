
<?php
  if(isset($_POST['Name'])){
      
      $ip = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
        // Make a request to ipinfo.io to get location data based on the user's IP address
        $ipinfo_url = "https://ipinfo.io/{$ip}/json";
        $response = file_get_contents($ipinfo_url);
        
        // Decode the JSON response
        $data = json_decode($response);
        
        // Extract location information
        $city = isset($data->city) ? $data->city : "N/A";
        $region = isset($data->region) ? $data->region : "N/A";
        $country = isset($data->country) ? $data->country : "N/A";
      
     $name = $_POST['Name'];
     $email = $_POST['Email'];
     $mobil = $_POST['MobileNo'];
    //  $Latitude = $_POST['Latitude'];
    //  $Longitude = $_POST['Longitude'];
    $loc = $city;
    $country = $country;
     $Bname = $_POST['BName'];
     $message = $_POST['Message'];
     $sendmessage="";


        $resData = array(
            "data" => array(
                "name" => $name,
                "email" => $email,
                "phoneno" => $mobil,
                "location" => $loc,
                "businessname" => $Bname,
                "country" => $country,
                "description" => $message
            ),
            "user" => 'User',
            "key" => 'c2h4u6tin9bfvlz67kTWgdi4t5sO'
        );

        $params = array(
            "method" => 'demo_request',
            "input_type" => "JSON",
            "response_type" => "JSON",
            "rest_data" => json_encode($resData),
       );
        

       $Crmapicall = apiCall('https://crm.bloombyte.io/custom/service/v4_1_custom/rest.php',$params);
       if($Crmapicall != 'User registered'){
            echo $Crmapicall;
            exit;
       }

        $MutParams = '{"query":"query RegisterAndSendDemoEmail($email: String!, $name: String!) {registerAndSendDemoEmail(email: $email, name: $name) {success message}}",  "variables": {"email": "' .$email . '", "name": "' . $name . '" }}';
        $API_response = restRequest('https://demoapp.mymedicalshop.com/serviceformms/',$MutParams);
        $resp =  $API_response['data']['registerAndSendDemoEmail']['message'];        
    	echo $resp;

  }


  function apiCall($url,$arguments)
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

  
  function restRequest($url,$arguments)
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

?>
