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
    public $is_featured;
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
                  (title, description, price, location, bedrooms, bathrooms, area, image, type, status, is_featured) 
                  VALUES (:title, :description, :price, :location, :bedrooms, :bathrooms, :area, :image, :type, :status, :is_featured)";
        
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
        $is_featured_val = $this->is_featured ? 1 : 0;
        $stmt->bindParam(':is_featured', $is_featured_val);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Update property
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :description, price = :price, 
                      location = :location, bedrooms = :bedrooms, bathrooms = :bathrooms, 
                      area = :area, type = :type, status = :status, is_featured = :is_featured 
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
        $is_featured_val = $this->is_featured ? 1 : 0;
        $stmt->bindParam(':is_featured', $is_featured_val);
        
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

    // Update featured status
    public function updateFeatured($id, $isFeatured) {
        $query = "UPDATE " . $this->table . " SET is_featured = :is_featured WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $featured_val = $isFeatured ? 1 : 0;
        $stmt->bindParam(':is_featured', $featured_val);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Save image to images table
    public function saveImage($propertyId, $filename, $filePath, $isPrimary = false) {
        $query = "INSERT INTO images (property_id, filename, file_path, is_primary) 
                  VALUES (:property_id, :filename, :file_path, :is_primary)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':property_id', $propertyId);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':file_path', $filePath);
        $primary_val = $isPrimary ? 1 : 0;
        $stmt->bindParam(':is_primary', $primary_val);
        return $stmt->execute();
    }

    // Get property images
    public function getImages($propertyId) {
        $query = "SELECT * FROM images WHERE property_id = :property_id ORDER BY is_primary DESC, created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':property_id', $propertyId);
        $stmt->execute();
        return $stmt;
    }
}
