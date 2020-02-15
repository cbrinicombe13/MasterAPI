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

$details = json_decode(file_get_contents('php://input'));
$user = new User($pdo, $details->username);
$checkUser = $user->getSingleUser()->fetch(PDO::FETCH_ASSOC);

if($checkUser) {
    echo json_encode(array(
        'deleted' => $user->deleteUser()
    ));
} elseif(!$checkUser) {
    echo json_encode(array(
        'error' => "Could not find user '".$details->username."'."
    ));
}
