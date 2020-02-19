<?php

include_once '../config/Database.php';
include_once '../models/Book.php';

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));

$book = new Book($pdo, $input->allowAccess, $input->label);
if(isset($book)) {
    echo json_encode(array(
        'created' => $book->createBook()
    ));
} else {
    echo json_encode(array(
        'error' => 'Could not create new book.'
    ));
}