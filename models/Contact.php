<?php

include_once './User.php';

class Contact {
    // DB Params:
    private $conn;

    // Contact params:
    public $bookID;
    public $contactID;
    public $firstName;
    public $lastName;
    public $occupation;
    public $phone;
    public $email;
    public $address;
    

    public function __construct($db,
                $bookID, $contactID, $firstName, $lastName,
                $occupation = '', $phone = '', $email = '', $address = []) {
        $this->conn = $db; // Vital
        $this->bookID = $bookID; // Required
        $this->contactID = $contactID; // Required
        $this->firstName = $firstName; // Required
        $this->lastName = $lastName; // Required
        $this->occupation = $occupation; // Optional
        $this->phone = $phone; // Optional
        $this->email = $email; // Optional
        $this->address = $address; // Optional
    }
    

}