<?php

class User {
    // Users DB params
    private $username;
    private $pwd;
    private $email;
    private $conn; // from PDO

    function __construct($pdo, $username, $pwd, $email) {
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
    function execAON($stmt, $params) {
        $this->conn->beginTransaction();
        $stmt->execute($params);
        $this->conn->commit();
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
    function deleteUser($username, $pwd) {
        $query = 'DELETE FROM Users WHERE username = :username AND pwd = :pwd';
        $stmt = $this->conn->prepare($query);

        try {
            self::execAON($stmt, [
                'username' => $this->username,
                'pwd' => $this->pwd
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}