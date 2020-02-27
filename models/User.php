<?php

class User {
    // Users DB params
    private $username;
    private $pwd;
    private $email;
    private $conn; // from PDO

    function __construct($pdo, $username = null, $pwd = null, $email = null) {
        $this->conn = $pdo;
        $this->username = strip_tags($username);
        $this->pwd = strip_tags($pwd);
        $this->email = strip_tags($email);
    }

    // Validate a username (no whitespace or dashes):
    public function validUsername() {
        $whitespace = strpos($this->username, ' ');
        $dashes = strpos($this->username, '-');
        if($whitespace || $dashes) {
            return false;
        }
        return true;
    }

    // Encapsulate user details:
    function getDetails() {
        return array(
            'username' => $this->username,
            'pwd' => $this->pwd,
            'email' => $this->email
        );
    }

    // Read all users:
    function getUsers() {
        $query = 'SELECT * FROM Users';
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo 'Could not read users: '.$e->getMessage();
            return false;
        }
    }

    // Read single user with username:
    function getSingleUser() {
        $query = 'SELECT * FROM Users WHERE username = :username';
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['username' => $this->username]);
            return $stmt;
        } catch(PDOException $e) {
            echo 'Could not read user '.$this->username.' : '.$e->getMessage();
            return false;
        }
    }

    // Get all books for user:
    function getBooks() { // NOT SAFE FOR INSERTION OF TABLE NAME.
        $query = 'SELECT * FROM '.$this->username;
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo json_encode(array(
                'error' => 'PDO error: '.$e->getMessage()
            ));
            return false;
        }
    }

    // New user:
    function createUser() { // NOT SAFE FOR INSERTION OF TABLE NAME.
        $newUser = 'INSERT INTO Users(username, pwd, email)
                        VALUES(:username, :pwd, :email)';
        $newTable = 'CREATE TABLE IF NOT EXISTS '.$this->username.' (
            id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
            belongsTo text(100) NOT NULL,
            firstName text(30) NOT NULL,
            lastName text(50) NOT NULL,
            occupation text(100),
            phone varchar(30),
            email varchar(200)
            )';
        try {
            $stmt = $this->conn->prepare($newUser);
            $this->conn->beginTransaction();
            $stmt->execute([
                'username' => $this->username,
                'pwd' => $this->pwd,
                'email' => $this->email]);
            $this->conn->exec($newTable);
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            echo 'User could not be created: '.$e->getMessage();
            return false;
        }
    }


}