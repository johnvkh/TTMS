<?php
session_start();
$timestamp = date("Y-m-d h:i:s");
if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
    http_response_code(200);
    echo json_encode(array(
        'responseCode'=> "99998",
        'message' => 'Session exprie, Please login for access to your account.',
        'timestamp' => $timestamp
    ));
    exit;
}
