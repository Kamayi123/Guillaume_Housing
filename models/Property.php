<?php

class Property {
    private $conn;
    private $table = 'properties';

    public $id;
    public $title;
    public $description;
    public $price;
    public $location;
    public $bedrooms;
    public $bathrooms;
    public $area;
    public $image;
    public $type;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all properties
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single property
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    // Create property
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, price, location, bedrooms, bathrooms, area, image, type, status) 
                  VALUES (:title, :description, :price, :location, :bedrooms, :bathrooms, :area, :image, :type, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':bedrooms', $this->bedrooms);
        $stmt->bindParam(':bathrooms', $this->bathrooms);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update property
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :description, price = :price, 
                      location = :location, bedrooms = :bedrooms, bathrooms = :bathrooms, 
                      area = :area, type = :type, status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':bedrooms', $this->bedrooms);
        $stmt->bindParam(':bathrooms', $this->bathrooms);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete property
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
