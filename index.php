<?php

//Report all errors
error_reporting(E_ALL);

//Require files
require_once("settings/configs.php");
require_once("scripts/process.php");
require_once("libs/solvemedia.php");
require_once("libs/kswallet.php");
require_once("database/db.php");
require_once("libs/albi_framework.php");
require_once("banned/ban_address.php");
require_once("extensions/ads.php");


//Start Session
session_start();

$session_name = "autoaddress".strtoupper($admin['currency'])."";


//Check if user is logged in
if (isset($_SESSION[$session_name])) {
    header("location: dashboard.php");
    exit;
}

//Start Kswallet
$KW = new Kswallet();
$V = new ValidateVariable();
$H = new Hash();

//Check if website is under maintenance
WebsiteMaintenance('1');
$random_hash = $H->Random(6);

//Get the User IP Address
$ip = getUserIP();
//Get IPHUB API
$iphub_api = $admin['iphub_api'];
//Get Global api key for the kswallet
$api_key = $admin['api_key'];

//Create random id for Pub ID
$pubbid = $H->Random(18);

//Check if user is refered
if (isset($_GET['r'])){
    //Clean The string
    $referal_lek = $V->CleanString($_GET['r']);
    //Check if this referal address exists in kswallet
    $CheckIfReferalExists = $KW->check_address($referal_lek);

    if ($CheckIfReferalExists == 1110){
        $referal = $V->CleanString($_GET['r']);
        setcookie("referal", $referal, time()+3600, "/", "", 0);
    }
}

//Create an array of errors
$errors = array();
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solvemedia_response = solvemedia_check_answer(
        $admin['privkey'],
        $_SERVER["REMOTE_ADDR"],
        $_POST["adcopy_challenge"],
        $_POST["adcopy_response"],
        $admin['hashkey']
    );
    if (!$solvemedia_response->is_valid) {
        $errors[] = '<div class="ui red message">Failed to verify captcha!</div>';
    } else {

      //Get variables and clean them
      $recieve_address = $V->CleanString($_POST['recieve_address']);

      //Check if address is valid
      if(empty($recieve_address)){
      $errors[] = '<div class="ui red message">Please enter address.</div>'; 
      }elseif ($KW->check_address($recieve_address) == 1110) {
      $errors[] = '<div class="ui red message">Invalid Recieve Address, Get your Recieve Address now - <a target="_BLANK" href="https://www.kswallet.net/register">Register to Kswallet</a>.</div>';
      }elseif (iphub($ip,$iphub_api) !== "good") {
      $errors[] = '<div class="ui red message">Your IP is marked as bad ip from IPHUB.</div>';
      }elseif(in_array($recieve_address,$system['banned_addresses'])){
      $errors[] = '<div class="ui red message">Your address is banned.</div>';
      }elseif (in_array($ip, $system['banned_ip'])) {
      $errors[] = '<div class="ui red message">Your ip is banned.</div>';
      }
    
    //Check if error free
    if (count($errors) == "0") {

    //Save address in cookie so user can access
    setcookie("user_addr", $recieve_address, time()+6548945, "/", "", 0);

    //Check if recieve_address is already in this faucet database
    $check_recieve_address = CheckRecieveAddress($recieve_address);

    //Validate login process
    if ($check_recieve_address == "ok") {

      //Check if admin is using shortener 
      if ($admin['shortener_status'] == true) {
      $random_hash = $H->Random(6);
      $shortener_url = $admin['shortener_url'];
      $shortener_apikey = $admin['shortener_api'];
      $domain = parse_url($admin['url'], PHP_URL_HOST);
      $url = "".$domain."/dashboard.php?key=$random_hash";
      $shorteee = ShortUrl($shortener_url,$shortener_apikey,$url);
      $shortenedlink = $shorteee['shortenedUrl'];

// Check if shortlink is created
      if ($shorteee['status'] == 'success') {
     $_SESSION['start'] = time();
     $_SESSION['short_key'] = $random_hash;
     $_SESSION['expire'] = $_SESSION['start'] + ($admin['session_timeout'] * 1);
     $_SESSION[$session_name] = $recieve_address;
     header("location: $shortenedlink");
   }else{
    $errors[] = '<div class="ui red message">Problem with shortener.</div>';
   }
 }else{
     $_SESSION['start'] = time();
     $_SESSION['expire'] = $_SESSION['start'] + ($admin['session_timeout'] * 1);
     $_SESSION[$session_name] = $recieve_address;
     header("location: dashboard.php");
 }
    }else{
      //Check if any referal isset
        if (!empty($_COOKIE['referal'])){
            $referal = $_COOKIE['referal'];
        }else{
            $referal = null;
        }
        //If setted referal is self referal null the cookie
        if ($referal == $recieve_address) {
          $referal = null;
        }

    //Add recieve address into database
     $insert_recieve_address = InsertRecieveAddress($recieve_address,$ip,$referal);
     if ($insert_recieve_address == "ok") {

//Check if admin is using shortener 
      if ($admin['shortener_status'] == true) {
      //Create env for the shortener
      $random_hash = $H->Random(6);
      $shortener_url = $admin['shortener_url'];
      $shortener_apikey = $admin['shortener_api'];
      $domain = parse_url($admin['url'], PHP_URL_HOST);
      $url = "".$domain."/dashboard.php?key=$random_hash";
      $shorteee = ShortUrl($shortener_url,$shortener_apikey,$url);
      $shortenedlink = $shorteee['shortenedUrl'];

     //manage sessions
// Check if shortlink is created
      if ($shorteee['status'] == 'success') {
     $_SESSION['short_key'] = $random_hash;      
     $_SESSION['start'] = time();
     $_SESSION['expire'] = $_SESSION['start'] + ($admin['session_timeout'] * 1);
     $_SESSION[$session_name] = $recieve_address;
     header("location: $shortenedlink");
   }else{
    $errors[] = '<div class="ui red message">Problem with shortener.</div>';
   }
 }else{
     $_SESSION['start'] = time();
     $_SESSION['expire'] = $_SESSION['start'] + ($admin['session_timeout'] * 1);
     $_SESSION[$session_name] = $recieve_address;
     header("location: dashboard.php");
 }
     }else{
      echo "Database Error";
     }

      }
    }
  }
}

?>
<head>
  <?php include_once("structure/header.php"); ?>

</head>
<!-- login register -->
<br>
<br>
<br>
<body>
<center>
<!-- Container with 3 rows -->
<div class="ui three column doubling stackable grid container">
  <!-- Row 1 -->
  <div class="column">
   <?php
    shuffle($ads120x600_1);
    $ads120x600_1 = array_slice($ads120x600_1, 0, 2);
    foreach($ads120x600_1 as $ads3){
      echo $ads3;
    }
    ?>
  </div>  
  <!-- Row 2 -->
  <div class="column">
    <?php
    shuffle($ads468x60_1);
    $ads468x60_1 = array_slice($ads468x60_1, 0, 4);
    foreach($ads468x60_1 as $ads1){
      echo $ads1;
    }
    ?>
<?php
$lele = round($admin['claim_reward']);
?>
<br>
<div class="earnup"><p>Earn up to <b><?php echo number_decimal($lele); ?> <?php echo strtoupper($admin['currency']); ?></b> every <b><?php echo floor($admin['claim_timer']/60); ?> minutes</b></p>
</div>
<br>
  <div class="ui horizontal divider" style="color:white;margin-bottom: -25px;">
    <p>Log In</p>
  </div>
<br>
<?php
foreach ($errors as $err) {
  echo $err;
}
?>  
<br>    
<form action="" method="POST" class="ui inverted form" autocomplete="off">
  <div class="field">
    <label>Please enter your Kswallet recieve address below to get started...</label>
    <input type="text" value="<?php if(isset($_COOKIE['user_addr'])){ echo $_COOKIE['user_addr'];} ?>" name="recieve_address" placeholder="Recieve Address">
  </div>
  <div class="field">
<?php echo solvemedia_get_html($admin['public_key']); ?>
  </div>
  <button class="ui blue button" type="submit">Log In</button>
</form>
<h3>Powered by: <a target="_BLANK" href="https://www.kswallet.net"><img height="auto" width="150" src="assets/images/logo.png" alt="Kswallet"></h3></a></h3>
<div class="column">
<?php
    shuffle($ads468x60_2);
    $ads468x60_2 = array_slice($ads468x60_2, 0, 8);
    foreach($ads468x60_2 as $ads2){
      echo $ads2;
    }
    ?>
</div>
  </div>
  <!-- Row 3 -->
  <div class="column">
    <?php
    shuffle($ads120x600_2);
    $ads120x600_2 = array_slice($ads120x600_2, 0, 2);
    foreach($ads120x600_2 as $ads4){
      echo $ads4;
    }
    ?>
    <br>
</div>  
</center>
</body>
<?php
include "extensions/anti-adblock.php";
include "extensions/popads.php";
?>
