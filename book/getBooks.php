<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/Book.php';

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));
$book = new Book($pdo, $input->username);
$userBooks = $book->getBooks();
$num = $userBooks->rowCount();

if($num > 0) {
    $respData = array(
        'data' => array()
    );
    while($row = $userBooks->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        array_push($respData['data'], array(
            'id' => $id,
            'label' => $label
        ));
    }
    echo json_encode($respData);
} else {
    echo json_encode(array(
        'error' => "No books exist for user: '".$input->username."'."
    ));
}