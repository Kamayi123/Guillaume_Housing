<?php

require_once 'Database.php';
require_once 'models/Property.php';
require_once 'helpers/security.php';
require_once 'helpers/auth.php';

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
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->property->title = sanitizeInput($_POST['title']);
            $this->property->description = sanitizeInput($_POST['description']);
            $this->property->price = floatval($_POST['price']);
            $this->property->location = sanitizeInput($_POST['location']);
            $this->property->bedrooms = intval($_POST['bedrooms']);
            $this->property->bathrooms = intval($_POST['bathrooms']);
            $this->property->area = intval($_POST['area']);
            $this->property->type = sanitizeInput($_POST['type'] ?? 'Residential');
            $this->property->status = sanitizeInput($_POST['status'] ?? 'available');
            $this->property->is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            // Handle image uploads
            $imagePath = '';
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../images/properties/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageCount = count($_FILES['images']['name']);
                for ($i = 0; $i < $imageCount; $i++) {
                    $file = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    if (validateImageFile($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '_' . time() . '.' . $ext;
                        $filepath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $filepath)) {
                            $webPath = '/GuillaumeHousing/images/properties/' . $filename;
                            if ($i === 0) {
                                $imagePath = $webPath; // First image as primary
                            }
                        }
                    }
                }
            }
            
            $this->property->image = $imagePath;

            if ($this->property->create()) {
                // Save images to images table
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $imageCount = count($_FILES['images']['name']);
                    for ($i = 0; $i < $imageCount; $i++) {
                        $file = $_FILES['images'];
                        if ($file['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
                            $filename = uniqid() . '_' . time() . '.' . $ext;
                            $webPath = '/GuillaumeHousing/images/properties/' . $filename;
                            $this->property->saveImage($this->property->id, $filename, $webPath, $i === 0);
                        }
                    }
                }
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Property created successfully']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to create property']);
            }
        }
    }

    public function update($id) {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->property->id = $id;
            $this->property->title = sanitizeInput($_POST['title']);
            $this->property->description = sanitizeInput($_POST['description']);
            $this->property->price = floatval($_POST['price']);
            $this->property->location = sanitizeInput($_POST['location']);
            $this->property->bedrooms = intval($_POST['bedrooms']);
            $this->property->bathrooms = intval($_POST['bathrooms']);
            $this->property->area = intval($_POST['area']);
            $this->property->type = sanitizeInput($_POST['type'] ?? 'Residential');
            $this->property->status = sanitizeInput($_POST['status']);
            $this->property->is_featured = isset($_POST['is_featured']) ? 1 : 0;

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
        requireAdmin();
        
        $this->property->id = $id;
        
        if ($this->property->delete()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Property deleted successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to delete property']);
        }
    }

    // Toggle featured status
    public function toggleFeatured($id) {
        requireAdmin();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $isFeatured = isset($data['is_featured']) ? intval($data['is_featured']) : 0;
        
        if ($this->property->updateFeatured($id, $isFeatured)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
        }
    }
}
