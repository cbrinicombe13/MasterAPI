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

    // Encapsulate user details:
    function getDetails() {
        return array(
            'username' => $this->username,
            'pwd' => $this->pwd,
            'email' => $this->email
        );
    }

    // Commit all or nothing: 
    function execAON($stmt, $params = []) {
        $this->conn->beginTransaction();
        $stmt->execute($params);
        $this->conn->commit();
    }

    // Read all users:
    function getUsers() {
        $query = 'SELECT * FROM Users';
        $stmt = $this->conn->prepare($query);
        try {
            self::execAON($stmt);
            return $stmt;
        } catch(PDOException $e) {
            echo 'Could not read users: '.$e;
            return false;
        }
    }

    // Read single user with username:
    function getSingleUser() {
        $query = 'SELECT * FROM Users WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        try {
            self::execAON($stmt, ['username' => $this->username]);
            return $stmt;
        } catch(PDOException $e) {
            echo 'Could not read user '.$this->username.' : '.$e;
            return false;
        }
    }

    // New user:
    function createUser() {
        $query = 'INSERT INTO Users(username, pwd, email)
                        VALUES(:username, :pwd, :email)';
        $stmt = $this->conn->prepare($query);
        try {
            self::execAON($stmt, [
                'username' => $this->username,
                'pwd' => $this->pwd,
                'email' => $this->email]);
                return true;
        } catch (PDOException $e) {
            echo 'User could not be created: '.$e;
            return false;
        }
    }

    // Delete user:
    function deleteUser() {
        $query = 'DELETE FROM Users WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        try {
            self::execAON($stmt, [
                'username' => $this->username
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'User could not be deleted: '.$e;
            return false;
        }
    }

}