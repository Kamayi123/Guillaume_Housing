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
        try {
            requireAdmin();
            
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                return;
            }
            
            // Validate required fields
            if (empty($_POST['title'] ?? '')) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Title is required']);
                return;
            }
            if (empty($_POST['price'] ?? '')) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Price is required']);
                return;
            }
            
            $this->property->title = sanitizeInput($_POST['title']);
            $this->property->description = sanitizeInput($_POST['description'] ?? '');
            $this->property->price = floatval($_POST['price']);
            $this->property->location = sanitizeInput($_POST['location'] ?? '');
            $this->property->bedrooms = intval($_POST['bedrooms'] ?? 0);
            $this->property->bathrooms = intval($_POST['bathrooms'] ?? 0);
            $this->property->area = intval($_POST['area'] ?? 0);
            $this->property->type = sanitizeInput($_POST['type'] ?? 'Residential');
            $this->property->status = sanitizeInput($_POST['status'] ?? 'available');
            
            // Handle image uploads
            $imagePath = '';
            $uploadedFiles = [];
            
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../images/properties/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                
                $imageCount = count($_FILES['images']['name']);
                for ($i = 0; $i < $imageCount; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['images']['name'][$i],
                            'type' => $_FILES['images']['type'][$i],
                            'tmp_name' => $_FILES['images']['tmp_name'][$i],
                            'error' => $_FILES['images']['error'][$i],
                            'size' => $_FILES['images']['size'][$i]
                        ];
                        
                        if (validateImageFile($file)) {
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $filename = time() . '_' . uniqid() . '.' . $ext;
                            $filepath = $uploadDir . $filename;
                            
                            if (@move_uploaded_file($file['tmp_name'], $filepath)) {
                                $webPath = '/GuillaumeHousing/images/properties/' . $filename;
                                $uploadedFiles[] = [
                                    'filename' => $filename,
                                    'webPath' => $webPath,
                                    'isPrimary' => ($i === 0)
                                ];
                                if ($i === 0) {
                                    $imagePath = $webPath;
                                }
                            }
                        }
                    }
                }
            }
            
            $this->property->image = $imagePath;

            if ($this->property->create()) {
                // Save uploaded files to images table
                foreach ($uploadedFiles as $file) {
                    $this->property->saveImage($this->property->id, $file['filename'], $file['webPath'], $file['isPrimary']);
                }
                
                // Assign selected unassigned images to this property
                if (!empty($_POST['selected_image_ids'] ?? '')) {
                    $imageIds = array_filter(array_map('intval', explode(',', $_POST['selected_image_ids'])));
                    foreach ($imageIds as $imageId) {
                        $stmt = $this->db->prepare("UPDATE images SET property_id = ? WHERE id = ?");
                        $stmt->execute([$this->property->id, $imageId]);
                    }
                    
                    // If no primary image was uploaded, set the first selected image as primary
                    if (empty($imagePath) && !empty($imageIds)) {
                        $firstImageId = reset($imageIds);
                        $stmt = $this->db->prepare("SELECT file_path FROM images WHERE id = ? LIMIT 1");
                        $stmt->execute([$firstImageId]);
                        $firstImage = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($firstImage) {
                            $stmt = $this->db->prepare("UPDATE properties SET image = ? WHERE id = ?");
                            $stmt->execute([$firstImage['file_path'], $this->property->id]);
                        }
                    }
                }
                
                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Property created successfully', 'id' => $this->property->id]);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Failed to create property in database']);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update($id) {
        try {
            requireAdmin();
            
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                return;
            }
            
            // Validate required fields
            if (empty($_POST['title'] ?? '')) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Title is required']);
                return;
            }
            if (empty($_POST['price'] ?? '')) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Price is required']);
                return;
            }
            
            $this->property->id = $id;
            $this->property->title = sanitizeInput($_POST['title']);
            $this->property->description = sanitizeInput($_POST['description'] ?? '');
            $this->property->price = floatval($_POST['price']);
            $this->property->location = sanitizeInput($_POST['location'] ?? '');
            $this->property->bedrooms = intval($_POST['bedrooms'] ?? 0);
            $this->property->bathrooms = intval($_POST['bathrooms'] ?? 0);
            $this->property->area = intval($_POST['area'] ?? 0);
            $this->property->type = sanitizeInput($_POST['type'] ?? 'Residential');
            $this->property->status = sanitizeInput($_POST['status'] ?? 'available');
            
            // Handle image uploads for update
            $uploadedFiles = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../images/properties/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                
                $imageCount = count($_FILES['images']['name']);
                for ($i = 0; $i < $imageCount; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['images']['name'][$i],
                            'type' => $_FILES['images']['type'][$i],
                            'tmp_name' => $_FILES['images']['tmp_name'][$i],
                            'error' => $_FILES['images']['error'][$i],
                            'size' => $_FILES['images']['size'][$i]
                        ];
                        
                        if (validateImageFile($file)) {
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $filename = time() . '_' . uniqid() . '.' . $ext;
                            $filepath = $uploadDir . $filename;
                            
                            if (@move_uploaded_file($file['tmp_name'], $filepath)) {
                                $webPath = '/GuillaumeHousing/images/properties/' . $filename;
                                $uploadedFiles[] = [
                                    'filename' => $filename,
                                    'webPath' => $webPath,
                                    'isPrimary' => ($i === 0)
                                ];
                                if ($i === 0) {
                                    $this->property->image = $webPath;
                                }
                            }
                        }
                    }
                }
            }

            if ($this->property->update()) {
                // Save new uploaded files to images table
                foreach ($uploadedFiles as $file) {
                    $this->property->saveImage($this->property->id, $file['filename'], $file['webPath'], $file['isPrimary']);
                }
                
                // Assign selected unassigned images to this property
                if (!empty($_POST['selected_image_ids'] ?? '')) {
                    $imageIds = array_filter(array_map('intval', explode(',', $_POST['selected_image_ids'])));
                    foreach ($imageIds as $imageId) {
                        $stmt = $this->db->prepare("UPDATE images SET property_id = ? WHERE id = ?");
                        $stmt->execute([$this->property->id, $imageId]);
                    }
                    
                    // If no primary image was uploaded, set the first selected image as primary
                    if (empty($uploadedFiles) && !empty($imageIds)) {
                        $firstImageId = reset($imageIds);
                        $stmt = $this->db->prepare("SELECT file_path FROM images WHERE id = ? LIMIT 1");
                        $stmt->execute([$firstImageId]);
                        $firstImage = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($firstImage) {
                            $stmt = $this->db->prepare("UPDATE properties SET image = ? WHERE id = ?");
                            $stmt->execute([$firstImage['file_path'], $this->property->id]);
                        }
                    }
                }
                
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Property updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Failed to update property in database']);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
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

}
