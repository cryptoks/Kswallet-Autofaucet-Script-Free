<?php
// Initialize the session
session_start();
//Regenerate Session Id
session_regenerate_id();
//Report All Errors
error_reporting(0);

//Require files
require_once("settings/configs.php");
require_once("scripts/process.php");
require_once("libs/solvemedia.php");
require_once("database/db.php");
require_once("libs/albi_framework.php");
require_once("extensions/ads.php");


//Initialize classes
$V = new ValidateVariable();
$A = new AUTH();
$H = new Hash();

//time
$now = time();

//Get session
$session_name = "autoaddress".strtoupper($admin['currency'])."";

//Check if user is logged in and check if login session expired
$A->LoginAUTH($session_name, "index.php");
$A->SessionExpired($now, "index.php");

//Check if admin wanted to use shortener
if ($admin['shortener_status'] == true) {
//Check if the key is valid
if (isset($_GET['key'])) {
  $verify_key = $V->CleanString($_GET['key']);

  //Check if key is valid
  if ($verify_key !== $_SESSION['short_key']) {
    echo "Invalid Key , please visit shortlink";
    die();
  }else{
    $_SESSION['claim_status'] = true;
  }
}

//Check if the user have the access to claim
if ($_SESSION['claim_status'] !== true) {
  echo "No access please do the shortlink";
    die();
}
}

//Get recieve address
$recieve_address = $_SESSION[$session_name];

//Get data from this recieve address
$RAdd = GetUserDetails($recieve_address);
$address_referal_earnings = $RAdd[0];
$address_claims = $RAdd[1];
$address_balance = $RAdd[2];

//Check if website is under maintenance
WebsiteMaintenance('1');

//Get the User IP Address
$userip = getUserIP();

//Get the user Referal
$user_ref = GetUserReferal($recieve_address);

//Errors array
$erros = array();
//Successes array
$successes = array();

    //Variables Tees
    $currency = $admin['currency'];
    $kswallet_api = $admin['api_key'];
    $claim_timer = $admin['claim_timer'];
    $time = time();

    //Check if user can claim
    $check_claim_status = CheckIfUserCanClaim($recieve_address);
    if ($check_claim_status == "ok") {

    //Get total amount of coins to send + mystery bonus
    $totalreward = round($admin['claim_reward']);
    $pay_user = PayUser($recieve_address,$totalreward,$currency);

    //If user has an referal send to the referal
    if ($user_ref !== null) {
    //Get percentage from the amount and send to ref of this user
    $ref_percentage = ($admin['referal_percentage'] / 100) * $admin['claim_reward'];
    $ref_percentage = round($ref_percentage);
    $ref_percentage = ($ref_percentage == "0") ?: '1';
    $pay_referal = PayUser($user_ref,$ref_percentage,$currency);
    if ($pay_referal == "200") {
     $update_user_referal = UpdateUserReferalEarnings($user_ref,$ref_percentage);
    }
  }
    //Check if the user payment is made
    if ($pay_user == "200") {
    //Default values
    $time = time();  
    //Update user claims,balance,last claim
    $update_user_info = UpdateUserFInfo($recieve_address,$totalreward,$time);
      $successes[] = '<div class="ui green message">You got, '.number_decimal($totalreward).' '.$currency.'</div>';
    }else{
      $errors[] = '<div class="ui red message">Payment failed , please contact '.$admin['contact_email'].'</div>';
    }

   }else{
    //If user can not claim throw the wait time
    $check_claim_status = gmdate("H:i:s", $check_claim_status);
    $need = "<div class='ui violet icon message'>
    <i class='notched circle loading icon'></i>
    <div class='header'>
    <div class='header'>
      Please Wait
    </div>
    <p>You need to wait <b>$check_claim_status</b> minutes.</p>
    </div>
    </div>";
      }

?>
<head>
  <meta http-equiv="refresh" content="<?php echo $admin['refresh_seconds']; ?>;url=dashboard.php" />
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
    shuffle($ads120x600_3);
    $ads120x600_3 = array_slice($ads120x600_3, 0, 3);
    foreach($ads120x600_3 as $ads2){
      echo $ads2;
    }
    ?>
  </div>  
  <!-- Row 2 -->
  <div class="column">
  </div>
    <div class="box">
<?php
    shuffle($ads468x60_4);
    $ads468x60_4 = array_slice($ads468x60_4, 0, 3);
    foreach($ads468x60_4 as $ads2){
      echo $ads2;
    }
    ?>
      <br>
<img height="90" width="86" src="assets/images/crypto_logos/<?php echo strtolower($admin['currency']);?>.png">
    <br>
    <br>
<p>You are signed in as : <b><strong><?php echo $recieve_address; ?></strong></b></p>
<p>Current Claim Reward : <b><strong><?php echo number_decimal($admin['claim_reward']); ?> <?php echo strtoupper($admin['currency']); ?></strong></b></p>
<p>You must wait at least <b><?php echo floor($admin['claim_timer']/60); ?></b> mins between autoclaims</p>
<h5><a href="out.php">Log Out</a></h5>
<br>
<?php
foreach ($successes as $succ) {
  echo $succ;
}
echo $need;
foreach ($errors as $err) {
  echo $err;
}
?>
<br>
<?php
    shuffle($ads468x60_3);
    $ads468x60_3 = array_slice($ads468x60_3, 0, 5);
    foreach($ads468x60_3 as $ads2){
      echo $ads2;
    }
    ?>
</div>
<div class="column">
    <div class="ui skyscraper test ad skymobile skyads" data-text="Skyscraper">
      <?php
    shuffle($ads120x600_4);
    $ads120x600_4 = array_slice($ads120x600_4, 0, 3);
    foreach($ads120x600_4 as $ads2){
      echo $ads2;
    }
    ?>
   </div>
   </div>
   </div>
<br>
<?php
    shuffle($ads728x90_1);
    $ads728x90_1 = array_slice($ads728x90_1, 0, 3);
    foreach($ads728x90_1 as $ads2){
      echo $ads2;
    }
    ?>
    <br>
    <?php
    shuffle($ads468x60_5);
    $ads468x60_5 = array_slice($ads468x60_5, 0, 4);
    foreach($ads468x60_5 as $ads2){
      echo $ads2;
    }
    ?>
    <br>
    <?php
    shuffle($ads728x90_2);
    $ads728x90_2 = array_slice($ads728x90_2, 0, 3);
    foreach($ads728x90_2 as $ads2){
      echo $ads2;
    }
    ?>
<br>
  <!-- Row 3 -->
  <div class="column">
    <div class="ui centered banner test ad ads-dash"></div>
    <div class="ui centered banner test ad ads-dash"></div>

   </div>
  <!-- GG -->
  <div class="box2">
<div class="ui two column doubling stackable grid container">
  <div class="column">
    <p>Total Claims</p>
    <p><?php echo $address_claims; ?></p>
  </div>
  <div class="column">
    <p>Referal Earnings</p>
    <p><?php echo number_decimal($address_referal_earnings); ?></p>
  </div>
</div>
<br>
<br>
<h1>Your Referal Link:</h1>
<p>Earn <?php echo $admin['referal_percentage']; ?> % of all your referal claims.</p>
<div class="ui normal input">
  <input type="text" size="75" value="<?php echo $admin['url'];?>?r=<?php echo $recieve_address; ?>" placeholder="Recieve Address" readonly="">
</div>
<br>
<br>
<br>
<center>
<?php
    shuffle($ads728x90_1);
    $ads728x90_1 = array_slice($ads728x90_1, 0, 8);
    foreach($ads728x90_1 as $ads2){
      echo $ads2;
    }
    ?>
<br>
<?php
    shuffle($ads468x60_1);
    $ads468x60_1 = array_slice($ads468x60_1, 0, 8);
    foreach($ads468x60_1 as $ads2){
      echo $ads2;
    }
    ?>
<br>
</center>
</div>  
</center> 
</body>
<?php
include "extensions/anti-adblock.php";
include "extensions/popads.php";
?>