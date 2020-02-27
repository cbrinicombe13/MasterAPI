<?php

class Contact
{
    // DB Params:
    private $conn;
    private $username;

    // Contact params:
    public $belongsTo;
    public $firstName;
    public $lastName;
    public $occupation;
    public $phone;
    public $email;

    public function __construct(
        $db,
        $username,
        $tName,
        $firstName = '',
        $lastName = '',
        $occupation = '',
        $phone = '',
        $email = ''
    ) {
        $this->conn = $db;
        $this->username = $username;
        $this->belongsTo = $tName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->occupation = $occupation;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function createContact()
    { // NOT SAFE FOR INSERTION OF TABLE NAME
        $query = 'INSERT INTO ' . $this->username . '(
            belongsTo, firstName, lastName, occupation, phone, email
        ) VALUES(
            :belongsTo, :firstName, :lastName, :occupation, :phone, :email 
        )';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'belongsTo' => $this->belongsTo,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'occupation' => $this->occupation,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Contact could not be created: ' . $e->getMessage();
            return false;
        }
    }

    // Get single contact:
    public function getSingleContact()
    { // NOT SAFE FOR INSERTION OF TABLE NAME
        $query = 'SELECT * FROM ' . $this->username . ' WHERE 
        belongsTo = :belongsTo AND firstName = :firstName AND lastName = :lastName';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'belongsTo' => $this->belongsTo,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName
            ]);
            return $stmt;
        } catch (PDOException $e) {
            echo 'Could not read contact: ' . $e->getMessage();
            return false;
        }
    }

    // Get single book:
    function getSingleBook()
    { // NOT SAFE FOR INSERTION OF TABLE NAME.
        $query = 'SELECT belongsTo FROM ' . $this->username . ' WHERE belongsTo = :belongsTo';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['belongsTo' => $this->belongsTo]);
            return $stmt;
        } catch (PDOException $e) {
            echo "Could not read book '" . $this->belongsTo . "' : " . $e->getMessage();
            return false;
        }
    }

    // New book:
    public function createBook()
    { // NOT SAFE FOR INSERTION OF TABLE NAME.
        $query = 'INSERT INTO ' . $this->username . '(
                        belongsTo, firstName, lastName, occupation, phone, email) 
                        VALUES(:belongsTo, :firstName, :lastName, :occupation, :phone, :email)';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'belongsTo' => $this->belongsTo,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'occupation' => $this->occupation,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Book could not be created: ' . $e->getMessage();
            return false;
        }
    }

    // Delete Book:
    public function deleteBook()
    { // NOT SAFE FOR INSERTION OF TABLE NAME.
        $query = 'DELETE FROM '.$this->username.' WHERE belongsTo = :belongsTo';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['belongsTo' => $this->belongsTo]);
            return true;
        } catch (PDOException $e) {
            echo 'Book could not be deleted: ' . $e->getMessage();
            return false;
        }
    }
}
