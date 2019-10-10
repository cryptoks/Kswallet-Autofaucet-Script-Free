<?php
    
$kswallet_v = "1.0";
    
/* 
Kswallet PHP API Library
https://www.kswallet.net
Our API Documentation
https://www.kswallet.net/documentation
*/

class Kswallet
{
    //This function is used to make payments
    public function pay_user($recieve_address,$amount,$currency){

        global $api_key;
        $url = "https://www.kswallet.net/api/send?";

       //The data you want to send via POST
       $fields = [
       'api_key'      => $api_key,
       'to'      => $recieve_address,
       'amount'      => $amount,
       'c'      => $currency,
       ];
       //url-ify the data for the POST
       $fields_string = http_build_query($fields);

       //open connection
       $ch = curl_init();
       //set the url, number of POST vars, POST data
       curl_setopt($ch,CURLOPT_URL, $url);
       curl_setopt($ch,CURLOPT_POST, true);
       curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
       //So that curl_exec returns the contents of the cURL; rather than echoing it
       curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
       //execute post
       $result = curl_exec($ch);
       $result = json_decode($result, true);
       if ($result["status"] == 200) {
         echo $result["status"];
       }
       echo $result["message2"];
    }
    //This function is used to check balance of any coin
    public function check_balance($currency){

        global $api_key;
        $url = "https://www.kswallet.net/api/getbalance?api_key=".$api_key."&c=".$currency."";

       //open connection
       $ch = curl_init();
       //set the url
       curl_setopt($ch,CURLOPT_URL, $url);
       //So that curl_exec returns the contents of the cURL; rather than echoing it
       curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
       //execute post
       $result = curl_exec($ch);
       echo $result;
    }
    //This function is used to check if an address is a valid one
    public function check_address($recieve_address){

        global $api_key;
        $url = "https://www.kswallet.net/api/checkaddress?api_key=".$api_key."&recieveaddress=".$recieve_address."";

       //open connection
       $ch = curl_init();
       //set the url
       curl_setopt($ch,CURLOPT_URL, $url);
       //So that curl_exec returns the contents of the cURL; rather than echoing it
       curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
       //execute post
       $result1 = curl_exec($ch);
       $result = json_decode($result1, true);
       return $result["status"];
    }    
}
