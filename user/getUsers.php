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

$user = new User($pdo);
$users = $user->getUsers();
$num = $users->rowCount();

if($num > 0) {
    $respData = array(
        'data' => array()
    );
    while($row = $users->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($respData['data'], array(
            'id' => $id,
            'username' => $username,
            'pwd' => $pwd,
            'email' => $email
        ));
    }
    echo json_encode($respData);
} else {
    echo json_encode(array(
        'error' => 'No users exist.'
    ));
}