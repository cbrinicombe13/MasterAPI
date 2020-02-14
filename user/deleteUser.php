<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/User.php';

$db = new Database();
$pdo = $db->connect();

$user = json_decode(file_get_contents('php://input'));
$newUser = new User($pdo, $user->username, $user->pwd, null);
$details = $newUser->getDetails();

if(isset($newUser)) {
    $newUser->deleteUser($details['username'], $details['pwd']);
    echo json_encode(array(
        'deleted' => 1
    ));
} else {
    echo json_encode(array(
        'deleted' => 0
    ));
}
