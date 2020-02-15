<?php

include_once '../config/Database.php';
require '../config/mail.config.php';
include_once '../models/User.php';
require '../vendor/autoload.php';

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

$db = new Database();
$pdo = $db->connect();

$user = json_decode(file_get_contents('php://input'));
$newUser = new User($pdo, $user->username, $user->pwd, $user->email);

$details = $newUser->getDetails();
$checkUser = $newUser->getSingleUser()->fetch(PDO::FETCH_ASSOC);

if($checkUser) {
    extract($checkUser);
    echo json_encode(array(
        'error' => "Username '".$username."' already exists."
    ));
    return;
} else {
    $mail = new Mail();
    if($newUser->createUser()) {
        echo json_encode(array(
            'created' => 1,
            'authenticationSent' => $mail->sendAuthentication($details['username'], $details['email'])
        ));
    } else {
        echo json_encode(array(
            'error' => 'Could not create user.'
        ));
    }
    return;
}
