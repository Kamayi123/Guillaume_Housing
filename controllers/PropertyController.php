<?php

require_once 'Database.php';
require_once 'models/Property.php';

class PropertyController {
    private $db;
    private $property;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->property = new Property($this->db);
    }

    public function index() {
        include 'views/properties.php';
    }

    public function details($id) {
        include 'views/property-details.php';
    }

    public function getAll() {
        $stmt = $this->property->getAll();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($properties);
    }

    public function getById($id) {
        $stmt = $this->property->getById($id);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($property);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->property->title = $_POST['title'];
            $this->property->description = $_POST['description'];
            $this->property->price = $_POST['price'];
            $this->property->location = $_POST['location'];
            $this->property->bedrooms = $_POST['bedrooms'];
            $this->property->bathrooms = $_POST['bathrooms'];
            $this->property->area = $_POST['area'];
            $this->property->image = $_POST['image'] ?? '';
            $this->property->status = $_POST['status'] ?? 'available';

            if ($this->property->create()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Property created successfully']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to create property']);
            }
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->property->id = $id;
            $this->property->title = $_POST['title'];
            $this->property->description = $_POST['description'];
            $this->property->price = $_POST['price'];
            $this->property->location = $_POST['location'];
            $this->property->bedrooms = $_POST['bedrooms'];
            $this->property->bathrooms = $_POST['bathrooms'];
            $this->property->area = $_POST['area'];
            $this->property->status = $_POST['status'];

            if ($this->property->update()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Property updated successfully']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to update property']);
            }
        }
    }

    public function delete($id) {
        $this->property->id = $id;
        
        if ($this->property->delete()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Property deleted successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to delete property']);
        }
    }
}
