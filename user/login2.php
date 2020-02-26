<?php

// Headers:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods');

include_once '../config/Database.php';
include_once '../models/User.php';

$db = new Database();
$pdo = $db->connect();

$input = json_decode(file_get_contents('php://input'));
$user = new User($pdo, $input->username, $input->pwd);
$details = $user->getDetails();
$userExists = $user->getSingleUser()->fetch(PDO::FETCH_ASSOC);

if ($userExists) {

    // Access user details:
    extract($userExists);
    $userEmail = $email;

    if ($pwd == $input->pwd) {

        // Get all from users unique table storing all info in nested arrays,
        // also store 'belongsTo' (book names) in another array:
        $books = $user->getBooks();
        $booksArr = array();
        $belongsToArr = array();
        while ($row = $books->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($booksArr, array(
                'id' => $id,
                'belongsTo' => $belongsTo,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'occupation' => $occupation,
                'phone' => $phone,
                'email' => $email
            ));
            array_push($belongsToArr, $belongsTo);
        }

        // Get copy of 'belongsTo' column which contains unique 'labels' and
        // group contacts by book name:
        $labels = array_unique($belongsToArr);
        $newBooksArr = array();
        foreach ($labels as $label) {
            $currentLabelBooks = array();
            foreach ($booksArr as $book) {
                if ($book['belongsTo'] == $label) {
                    array_push($currentLabelBooks, array(
                        'id' => $book['id'],
                        'firstName' => $book['firstName'],
                        'lastName' => $book['lastName'],
                        'occupation' => $book['occupation'],
                        'phone' => $book['phone'],
                        'email' => $book['email']
                    ));
                }
            }
            array_push($newBooksArr, array(
                $label => $currentLabelBooks
            ));
        }
        
        // Set response:
        echo json_encode(array(
            'username' => $username,
            'email' => $userEmail,
            'labels' => $labels,
            'books' => json_encode($newBooksArr)
        ));

    } else {
        echo json_encode(array(
            'error' => 'Details do not match.'
        ));
    }
} else {
    echo json_encode(array(
        'error' => 'No user found.'
    ));
}
