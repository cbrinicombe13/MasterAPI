<?php

class Database {

    //DB params:
    private $host = 'localhost';
    protected $dbName = 'MasterAPI';
    private $username = 'root';
    private $pwd = '';
    private $conn;

    //Connection:
    public function connect() {
        $this->conn = null;
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbName;
        try {
            $this->conn = new PDO($dsn, $this->username, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->conn;
        } catch (PDOException $e) {
            echo json_encode(array(
                'error' => 'PDO error: '.$e->getMessage()
            ));
        }
    }

    public function createUserTable() {
        if(isset($this->conn)) {
            try {
                $query = 'CREATE TABLE IF NOT EXISTS Users (
                    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    username varchar(100) NOT NULL,
                    pwd varchar(100) NOT NULL,
                    email varchar(100) NOT NULL
                )';
                $this->conn->exec($query);
                return true;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error' => 'PDO error: '.$e->getMessage()
                ));
                return false;
            }
        } else {
            echo json_encode(array(
                'error' => 'No connection'
            ));
            return false;
        }

    }

    public function createBooksTable() {
        if(isset($this->conn)) {
            try {
                $query = 'CREATE TABLE IF NOT EXISTS Books (
                    id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    label text(20) NOT NULL,
                    allowAccess varchar(100) NOT NULL
                )';
                $this->conn->exec($query);
                return true;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error' => 'PDO error: '.$e->getMessage()
                ));
                return false;
            }
        } else {
            echo json_encode(array(
                'error' => 'No connection'
            ));
            return false;
        }
    }
}