<?php

class Booking {
    private $conn;
    private $table = 'bookings';

    public $id;
    public $property_id;
    public $user_id;
    public $check_in;
    public $check_out;
    public $guests;
    public $total_price;
    public $status;
    public $created_at;
    
    // Property data snapshot
    public $property_title;
    public $property_description;
    public $property_price;
    public $property_location;
    public $property_bedrooms;
    public $property_bathrooms;
    public $property_area;
    public $property_image;
    public $property_type;
    public $property_status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all bookings
    public function getAll() {
        // Return booking rows including the stored property snapshot fields (don't join current properties)
        $query = "SELECT b.*, u.name as user_name 
                  FROM " . $this->table . " b
                  LEFT JOIN users u ON b.user_id = u.id
                  ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get bookings by user
    public function getByUser($user_id) {
        // Return bookings for a given user using the stored snapshot fields on the bookings table
        $query = "SELECT b.* 
                  FROM " . $this->table . " b
                  WHERE b.user_id = :user_id
                  ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Get bookings by property
    public function getByProperty($property_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE property_id = :property_id 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':property_id', $property_id);
        $stmt->execute();
        return $stmt;
    }

    // Create booking
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (property_id, user_id, check_in, check_out, guests, total_price, status,
                   property_title, property_description, property_price, property_location,
                   property_bedrooms, property_bathrooms, property_area, property_image,
                   property_type, property_status) 
                  VALUES (:property_id, :user_id, :check_in, :check_out, :guests, :total_price, :status,
                          :property_title, :property_description, :property_price, :property_location,
                          :property_bedrooms, :property_bathrooms, :property_area, :property_image,
                          :property_type, :property_status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':property_id', $this->property_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':check_in', $this->check_in);
        $stmt->bindParam(':check_out', $this->check_out);
        $stmt->bindParam(':guests', $this->guests);
        $stmt->bindParam(':total_price', $this->total_price);
        $stmt->bindParam(':status', $this->status);
        
        // Bind property data
        $stmt->bindParam(':property_title', $this->property_title);
        $stmt->bindParam(':property_description', $this->property_description);
        $stmt->bindParam(':property_price', $this->property_price);
        $stmt->bindParam(':property_location', $this->property_location);
        $stmt->bindParam(':property_bedrooms', $this->property_bedrooms);
        $stmt->bindParam(':property_bathrooms', $this->property_bathrooms);
        $stmt->bindParam(':property_area', $this->property_area);
        $stmt->bindParam(':property_image', $this->property_image);
        $stmt->bindParam(':property_type', $this->property_type);
        $stmt->bindParam(':property_status', $this->property_status);
        
        try {
            if($stmt->execute()) {
                return true;
            }
            $errorInfo = $stmt->errorInfo();
            error_log('Booking create failed: ' . print_r($errorInfo, true));
            return false;
        } catch (\Throwable $e) {
            error_log('Booking create exception: ' . $e->getMessage());
            throw $e;
        }
    }

    // Update booking
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete booking
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
