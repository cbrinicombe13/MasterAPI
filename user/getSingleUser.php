<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/User.php';

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));
$user = new User($pdo, $input->username);
$requestedUser = $user->getSingleUser();
$num = $requestedUser->rowCount();

if($num > 0) {
    $row = $requestedUser->fetch(PDO::FETCH_ASSOC);
    extract($row);
    echo json_encode(array(
        'data' => array(
            'id' => $id,
            'username' => $username,
            'pwd' => $pwd,
            'email' => $email
        )
    ));
} else {
    echo json_encode(array(
        'error' => "No user with username: '".$input->username."'."
    ));
}