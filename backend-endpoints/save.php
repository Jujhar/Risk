<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

// Generate file name
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 5; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}

// Create file
$myfile = fopen("$randomString", "w") or die("Unable to open file!");

// Write data
foreach ($_POST as $key => $value) {
    $txt = $txt . $key;
    $txt = $txt . $value;
}
fwrite($myfile, $txt);
fclose($myfile);

// Return file name
echo json_encode(array(
    "file" => $randomString,
    "done" => "thank you",
));
