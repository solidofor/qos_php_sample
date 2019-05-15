<?php 
  session_start();
 header("Access-Control-Allow-Origin: *");
		$message=array();
		$message['msisdn']=$_POST['msisdn'];
		$message['amount']=$_POST['amount'];
		$message['firstname']=$_POST['firstname'];
		$message['lastname']=$_POST['lastname'];
		$message['transref']=time();
		$message['clientid']=$_POST['clientid']; 
      $message['comment']="null";
		$context  = "http://example_url_or_ip";  
		$username = "user";  
		$password = "password";  
 
//////////////////////////////////////////////////////////
		function callAPI($method, $url, $data){
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      //'APIKEY: 111111111111111111111',
      'Content-Type: application/json',
      'Authorization: Basic '. base64_encode("$username:$password")
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}
////////////////////////////////////////////////////////////////////////////////
 $make_call = callAPI(
	'POST',
 	"$context/QosicBridge/user/requestpayment",
	json_encode($message)
 	);

 $response = json_decode($make_call, true);
/////////////////////////////////////////////////////////////////

  $_SESSION['transref']=$response['transref'];
  $_SESSION['clientid']=$message['clientid'];
?>