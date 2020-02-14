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
            throw $e;
        }
    }

    public function createUserTable() {
        if(!isset($this->conn)) {
            echo 'No connection';
            return;
        }

        $query = 'CREATE TABLE Users (
            id varchar(20) NOT NULL PRIMARY KEY,
            username varchar(100) NOT NULL,
            pwd varchar(100) NOT NULL,
            email varchar(100) NOT NULL
        )';

        if($this->conn->exec($query)) {
            return true;
        }
        return false;
    }

}