<?php

class Book {
    // DB params:
    private $conn;

    // book params:
    private $label;
    private $allowAccess;
    
    public function __construct($db, $allowAccess, $label = '') {
        $this->conn = $db;
        $this->label = strip_tags($label);
        $this->allowAccess = strip_tags($allowAccess);
    }

    public function validateLabel($label) {
        return str_replace([' ', '-'], '_',$label);
    }

    public function getLabels() {
        if(isset($this->conn)) {
            try {
                $query = 'SELECT * FROM Books WHERE allowAccess = :allowAccess';
                $stmt = $this->conn->prepare($query);
                $stmt->execute(['allowAccess' => $this->allowAccess]);
                return $stmt;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error' => 'PDO error: '.$e->getMessage()
                ));
                return false;
            }
        } else {
            echo json_encode(array(
                'error' => 'Could not connect.'
            ));
            return false;
        }
        
    }

    public function getSingleLabel() {
        if(isset($this->conn)) {
            try {
                $query = 'SELECT * FROM Books WHERE label = :label';
                $stmt = $this->conn->prepare($query);
                $stmt->execute(['label' => $this->label]);
                return $stmt;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error' => 'PDO error: '.$e->getMessage()
                ));
                return false;
            }
        } else {
            echo json_encode(array(
                'error' => 'Could not connect.'
            ));
            return false;
        }
    }

    public function createBook() {
        if(isset($this->conn)) {
            try {
                $validLabel = self::validateLabel($this->label);
                // Register new table in 'Books' table and create new table 'label': 
                $newBook =  'CREATE TABLE '.$validLabel.' (
                    firstName text(30) NOT NULL,
                    lastName text(50) NOT NULL,
                    occupation text(100),
                    phone varchar(30),
                    email varchar(200),
                    address text(1000),
                    createdAt timestamp NOT NULL
                    )';
                $registerBook = 'INSERT INTO Books(label, allowAccess) VALUES(:label, :allowAccess)';
                $registerBookStmt = $this->conn->prepare($registerBook);

                // Execute all or nothing:
                $this->conn->beginTransaction();
                $registerBookStmt->execute(['label' => $this->label, 'allowAccess' => $this->allowAccess]);
                $this->conn->exec($newBook);
                $this->conn->commit();
                return true;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error' => 'PDO error: '.$e->getMessage()
                ));
                return false; 
            }
        } else {
            echo json_encode(array(
                'error' => 'Could not connect.'
            ));
            return false;
        }
        
    }

}