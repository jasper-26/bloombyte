<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
require('DBconfig.php');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $results = [
        'message' => 'Only POST requests are allowed',
        'status' => false
    ];
    // Return the results as JSON
    echo json_encode($results);
    die;
}

$data = file_get_contents("php://input");
$phpObj = json_decode($data, true);

// Get the user data from the request
$email = $phpObj['email'];
$comments = $phpObj['comments'];
if ($email == '') {
    $results = [
        'message' => 'Email is empty',
        'status' => false
    ];
    // Return the results as JSON
    echo json_encode($results);
    die;
}
$insQuery = $connDB->query("INSERT INTO bloombyte_faq(`email_id`,`comments`) VALUES ('$email','$comments')", true);
if ($insQuery) {
    $results = [
        'message' => 'Query received. Thank you!',
        'status' => true
    ];
} else {
    $results = [
        'message' => 'Query is not submitted, Try again!',
        'status' => false
    ];
}

// Return the results as JSON
echo json_encode($results);
die;