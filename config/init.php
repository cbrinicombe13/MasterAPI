<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once './Database.php';

$db = new Database();
$pdo = $db->connect();

if(isset($pdo)) {
    echo json_encode(array(
        'usersTableCreated' => $db->createUserTable()
    ));
} else {
    echo json_encode(array(
        'error' => 'Could not connect.'
    ));
}
