<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../models/Book.php';

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));
$user = new User($pdo, $input->username);
$requestedUser = $user->getSingleUser();

$details = $requestedUser->fetch(PDO::FETCH_ASSOC);
if($details) {
    extract($details);

    $book = new Book($pdo, $details['username']);
    $requestBooks = $book->getBooks();
    $numBooks = $requestBooks->rowCount();
    $books = array();

    while($row = $requestBooks->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($books, array(
            'id' => $id,
            'label' => $label
        ));
    }
    echo json_encode(array(
        'valid' => $details['pwd'] == $input->pwd,
        'user' => array(
            'username' => $details['username'],
            'email' => $details['email'],
            'books' => $books
        )
    ));
} else {
    echo json_encode(array(
        'error' => "Username '".$input->username."' not found."
    ));
}


