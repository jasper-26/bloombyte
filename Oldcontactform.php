<?php
require('DBconfig.php');

  if(isset($_POST['Name'])){
     $name = $_POST['Name'];
     $email = $_POST['Email'];
     $mobil = $_POST['MobileNo'];
    //  $Latitude = $_POST['Latitude'];
    //  $Longitude = $_POST['Longitude'];
    $loc = $_POST['City'];
    $country = $_POST['Country'];
     $Bname = $_POST['BName'];
     $message = $_POST['Message'];
     $country = $_POST['Country'];
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

  function sendMailfun($Mname,$Memail,$Mmobil,$Mloc,$MBname,$Mcountry,$Mmessage){
         require('mailer/class.phpmailer.php');
            require('mailer/class.smtp.php');
            $sendmessage.= "<table border='1'><thead><tr>";
            $sendmessage.= "<td>Date</td>";
            $sendmessage.= "<td>Name</td>";
            $sendmessage.= "<td>Email ID </td>";
            $sendmessage.= "<td>Mobile Number</td>";
            $sendmessage.= "<td>Location</td>";
            $sendmessage.= "<td>Business Name</td>";
            $sendmessage.= "<td>Country</td>";
            $sendmessage.= "<td>Messages</td>";
            $sendmessage.= "</tr></thead>";
            $sendmessage.= "</tbody><tr>";
            $sendmessage.= "<td>".date("d-m-Y")."</td>";
            $sendmessage.= "<td>".$Mname."</td>";
            $sendmessage.= "<td>".$Memail."</td>";
            $sendmessage.= "<td>".$Mmobil."</td>";
            $sendmessage.= "<td>".$Mloc."</td>";
            $sendmessage.= "<td>".$MBname."</td>";
            $sendmessage.= "<td>".$Mcountry."</td>";
            $sendmessage.= "<td>".$Mmessage."</td>";
            $sendmessage.= "</tr></tbody></table>";
            
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true; 
            $mail->Username = "apply@takshilauniv.com"; 
            $mail->Password = "nzvrdiooahaswjjd";
    	    $mail->setFrom('apply@takshilauniv.com','Takshashila University');
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587;
            $mail->ContentType = "text/html";
            $mail->IsHTML(true);
            $mail->addAddress('website@mvit.edu.in', "");
            $mail->addBCC("purushoth@mvit.edu.in ");
            $mail->Subject = "Bloombyte Enquiry";
            $mail->Body = $sendmessage;
            if(!$mail->Send())
            {
            echo "<p><h4>Message could not be sent. </h4></p>";
            }$mail->ClearAllRecipients(); 
  }
  
    if(isset($_POST['username'])){
        session_start();
        $name = $_POST['username'];
        $pwd = $_POST['password'];
        $sql = $connDB->query("SELECT Status FROM bloombyte_rpt_login WHERE Status = 1 AND UserName = '".$name."' AND Password = '".$pwd."'");
        if ($sql->num_rows > 0) { 
            $_SESSION["username"] = $name;
            echo true;
            die;
        }else{
            echo false;
            die;
        }
    }
  
?>