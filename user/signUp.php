<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../config/mail.config.php';
require '../vendor/autoload.php';

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));
$user = new User($pdo, $input->username, $input->pwd, $input->email);
$requestedUser = $user->getSingleUser()->fetch(PDO::FETCH_ASSOC);

if($requestedUser) {
    echo json_encode(array(
        'error' => 'Username already exists.'
    ));
    return; // Somehow an empty row is inserted??
} else {
    if($user->createUser()) {
        $mail = new Mail();
        echo json_encode(array(
            'created' => true,
            'sentAuthentication' => $mail->sendAuthentication($input->username, $input->email) 
        ));
    } else {
        echo json_encode(array(
            'error' => 'Could not add user.'
        ));
    }
}