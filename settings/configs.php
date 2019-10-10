<?php

//Faucet base cryptocurrency settings - please check at https://www.kswallet.net/crypto all supported coins
$admin['currency'] = "BTC";
$admin['currency_name'] = "Bitcoin";
$admin['website_name'] = "Kscoins";
$admin['contact_email'] = "contact@kswallet.net";

//Faucet Settings
$admin['web_title'] = "".$admin['website_name']." - ".$admin['currency_name']." AutoFaucet";
$admin['web_description'] = "".$admin['currency_name']." - ".$admin['currency']." autofaucet , for kswallet.";
$admin['web_keywords'] = "".$admin['currency']." autofaucet,".$admin['currency_name']." autofaucet,earn ".$admin['currency_name'].", kswallet ".$admin['currency']." autofaucet, kswallet autofaucets, kswallet";
$admin['url'] = "http://www.localhost/";
$admin['contact_email'] = "";

// config your reward
$admin['claim_reward'] = 1; // your faucet's reward
$admin['claim_timer'] = 60;  // time to wait beetwen 2 claims, in second.
$admin['referal_percentage'] = "10"; //Referal percentage
$admin['session_timeout'] = 60; //Set a timeout for the user session where he is forced to do a login again (prevent bots in this case) IN SECONDS
$admin['refresh_seconds'] = 20; //Refresh dashboard page based on these seconds

//Iphub API Key
$admin['iphub_api'] = "";

//Kswallet API Key
$admin['api_key'] = "";

//Shortener settings
$admin['shortener_status'] = false; // true if you want to use shortener false if you do not want to use shortener
$admin['shortener_url'] = "http://met.bz/api/"; //include the shortener url (API PATH) ex.http://met.bz/api/
$admin['shortener_api'] = ""; //Shortener API

//Solvemedia settings
$admin['privkey'] ="";
$admin['hashkey'] ="";
$admin['public_key'] = "";

//Maintenance mode Settings -- "1" (maintenance mode) -- "0" (Normal mode)
$website_maintenance_status = "0";


?> 