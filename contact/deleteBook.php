<?php

include_once '../config/Database.php';
include_once '../models/Contact.php';

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

$db = new Database();
$pdo = $db->connect();

// Recieve book name:
$input = json_decode(file_get_contents('php://input'));
$contact = new Contact($pdo, $input->username, $input->belongsTo);

// No need to check if book exists as can only delete if exists on front-end.

echo json_encode(array(
    'deleted' => $contact->deleteBook()
));