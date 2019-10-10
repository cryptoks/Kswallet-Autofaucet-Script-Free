<?php
//Get the user IP
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}
//Create a random and unique ID
function RandomId($length)
{
    $str = "";
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[ rand(0, $size - 1) ];
    }
    return $str;
}
//Check if user ip is blocked in iphub
function iphub($ip,$iphub_api)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'http://v2.api.iphub.info/ip/'.$ip);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Key: ' . $iphub_api));
    $result = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($result, true);
    if ($obj['block'] == '1') {
        return 'bad';
    } else {
        return 'good';
    }
}
//Get Current Time
function getTime()
{
    $time = time();
    return $time;
}
//Get time when an action was done
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
//Turn number into decimal
function number_decimal($number)
{
    $number = $number / 100000000;
    $number = number_format($number, 8);
    return $number;
}
//Randomize a number from a number to another number example from 1 to 10000
function RandomNumberRoll($int1, $int2)
{
    $randomized_nr = (rand($int1, $int2));

    return $randomized_nr;
}
//Check if website is under maintenance
function WebsiteMaintenance($level)
{
    global $website_maintenance_status;

    if ($website_maintenance_status == "1") {
        switch ($level) {
            case 1:
        echo file_get_contents("maintenance");
        die();
                break;
            case 2:
        echo file_get_contents("../maintenance");
        die();
                break;
        }
    }
}
//Check if recieve_address is already registerd in faucet database
function CheckRecieveAddress($recieve_address)
{
        global $pdo;
        
        //Use PDO to fetch needed data
        $sql = "SELECT recieve_address FROM users WHERE recieve_address = :recieve_address";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recieve_address', $recieve_address);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //Get Data
        if (!$row === false) {
          return "ok";
        } else {
          return "not";
        }
}
//Insert into database user recieve_address
function InsertRecieveAddress($recieve_address,$ip,$referal)
{
    global $pdo;

        //Insert the transaction into Kswallet Database
        $sql = "INSERT INTO users (recieve_address,ip,referal) VALUES (:recieve_address,:ip,:referal)";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recieve_address', $recieve_address);
        $statement->bindValue(':ip', $ip);
        $statement->bindValue(':referal', $referal);
        $inserted = $statement->execute();

        if($inserted){
        //Inserted
            return "ok";
        }else{
        //Error
            return "error";
        }
}
//Check if user clan claim from faucet
function CheckIfUserCanClaim($address) 
{
    global $pdo;
    global $claim_timer;
    global $time;

        //Use PDO to fetch needed data
        $sql = "SELECT last_claim FROM users WHERE recieve_address = :recieve_address";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recieve_address', $address);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //Get Data
        if (!$row === false) {
          $last_time_claim = $row['last_claim'];
          $rmn = $time - $last_time_claim;
          if ($rmn > $claim_timer) {
          return "ok";
          }else{
          $wait = $last_time_claim + $claim_timer - $time;
          return $wait;
          }
        } else {
          return "not";
        }
}
//Get user referal
function GetUserReferal($recieve_address)
{
    global $pdo;

        //Use PDO to fetch needed data
        $sql = "SELECT referal FROM users WHERE recieve_address = :recieve_address";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recieve_address', $recieve_address);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //Get Data
        if (!$row === false) {
          $ref = $row['referal'];
          return $ref;
        } else {
          return "not";
        } 
}
//Update user claims,balance,last claim
function UpdateUserFInfo($recieve_address,$reward,$last_claim)
{
    global $pdo;

        $sql = "UPDATE users SET balance =(balance+:reward), last_claim = :last_claim, claims =(claims+1) where recieve_address = :recieve_address";
 
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':reward', $reward);
        $statement->bindValue(':recieve_address', $recieve_address);
        $statement->bindValue(':last_claim', $last_claim);
        $update = $statement->execute();
        if($update){
        //Suceed
        }else{
          echo "Error 52145";
          die();
        }
}
//Update user referal earnings
function UpdateUserReferalEarnings($user_ref,$ref_reward)
{
    global $pdo;

        $sql = "UPDATE users SET referal_earnings =(referal_earnings+:ref_reward) where recieve_address = :user_ref";
 
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':ref_reward', $ref_reward);
        $statement->bindValue(':user_ref', $user_ref);
        $update = $statement->execute();
        if($update){
        //Suceed
        }else{
          echo "Error 52145";
          die();
        }

}
//Get User details
function GetUserDetails($recieve_address)
{
    global $pdo;

        $sql = "SELECT * FROM users WHERE recieve_address = :recieve_address";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recieve_address', $recieve_address);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        //Get Data
        if (!$row === false) {
          $referal_earnings = $row['referal_earnings'];
          $claims = $row['claims'];
          $balance = $row['balance'];
          $ip = $row['ip'];
          $data = array($referal_earnings,$claims,$balance,$ip);
          return $data;
        } else {
          return "not";
        }  
}
//Pay user via kswallet
function PayUser($recieve_address,$amount,$currency)
{
    global $kswallet_api;

    $url = "https://www.kswallet.net/api/send?";

       //The data you want to send via POST
       $fields = [
       'api_key'      => $kswallet_api,
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
         return $result["status"];
       }
       return $result["message2"];
}
//Get a shortener url
function ShortUrl($shortener_url,$shortener_apikey,$url)
{
    $result = @json_decode(file_get_contents(''.$shortener_url.'?api='.$shortener_apikey.'&url='.$url.''), true);
  if($result['status'] === 'error')
    return "not";
  else
    return $result; 
}

