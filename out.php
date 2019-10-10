<?php

// Initialize the session
session_start();
//Regenerate Session Id
session_regenerate_id();
//Report All Errors
error_reporting(0);

//Require files
require_once("settings/configs.php");

//Get session
$session_name = "autoaddress".strtoupper($admin['currency'])."";

// If session variable is not set it will redirect to login page
if(!isset($_SESSION[$session_name]) || empty($_SESSION[$session_name])){
  header("location: index.php");
  exit;
}else {
          $now = time(); // Checking the time now when home page starts.
        //Checking if session ended
        if ($now > $_SESSION['expire']) {
            session_destroy();
            header("location: index.php");      
        }
}

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();

header("location: index.php");

?>
