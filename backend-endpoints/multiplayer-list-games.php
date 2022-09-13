<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


// A ddos booby trap
session_start();

// get ip address
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// set number of saves sent
if (!isset($_SESSION[$ip]) && !empty($_SESSION[$ip])) {
   $_SESSION[$ip] = 0;
} else {
  $_SESSION[$ip]++;
}
// a ddos booby trap

// Get files
$dir    = '.';
$files1 = scandir($dir);
$outputMe = [];

foreach ($files1 as $value) {
    if (strpos($value, 'GAME-') !== false) {
        array_push($outputMe, $value);
    }
}

// Return file name
echo json_encode(array(
    "done" => "thank you",
    "files" => $outputMe,
));
