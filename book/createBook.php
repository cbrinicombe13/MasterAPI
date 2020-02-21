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
$requestedBook = $book->getSingleLabel()->fetch(PDO::FETCH_ASSOC);
if (isset($book)) {
    if ($requestedBook) { // Should be able to have two users with the same book labels
        echo json_encode(array(
            'error' => 'Book already exists.'
        ));
    } else {
        if ($book->createBook()) {
            $newBook = $book->getSingleLabel()->fetch(PDO::FETCH_ASSOC);
            extract($newBook);
            echo json_encode(array(
                'created' => true,
                'label' => $label,
                'id' => $id
            ));
        } else {
            echo json_encode(array(
                'created' => false
            ));
        }
    }
} else {
    echo json_encode(array(
        'error' => 'Could not create new book.'
    ));
}
