<?php

include_once '../config/Database.php';
include_once '../models/Contact.php';

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

$db = new Database();
$pdo = $db->connect();

// Recieve new book name and first contact info:
$input = json_decode(file_get_contents('php://input'));
$contact = new Contact($pdo, $input->username, $input->belongsTo ,$input->firstName,
            $input->lastName, $input->occupation, $input->phone
            ,$input->email);
$checkBook = $contact->getSingleBook()->fetch(PDO::FETCH_ASSOC);

if(!$checkBook) {
    echo json_encode(array(
        'created' => $contact->createBook()
    ));
} else {
    echo json_encode(array(
        'error' => 'Book already exists'
    ));
}
