<?php

class Message {
    private $conn;
    private $table = 'messages';

    public $id;
    public $name;
    public $email;
    public $message;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all messages
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single message
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    // Create message
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, email, message, subject) 
                  VALUES (:name, :email, :message, 'Contact Form Submission')";
        
        $stmt = $this->conn->prepare($query);
        
        // Set default values if not provided
        $name = $this->name ?? '';
        $email = $this->email ?? '';
        $message = $this->message ?? '';
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete message
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
