<?php

require_once 'Database.php';
require_once 'models/Property.php';
require_once 'helpers/auth.php';
require_once 'helpers/security.php';

class ImageController {
    private $db;
    private $property;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->property = new Property($this->db);
    }

    public function getAll() {
        try {
            requireAdmin();
            
            // First, scan the properties folder and add any missing images to database
            $this->syncImagesFromFolder();
            
            $stmt = $this->db->prepare("
                SELECT i.*, p.title as property_title 
                FROM images i 
                JOIN properties p ON i.property_id = p.id 
                ORDER BY i.created_at DESC
            ");
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode($images);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function syncImagesFromFolder() {
        $this->syncFolder(__DIR__ . '/../images/properties/');
        $this->syncFolder(__DIR__ . '/../images/', false);
    }
    
    private function syncFolder($folderPath, $isPropertiesFolder = true) {
        if (!is_dir($folderPath)) {
            if ($isPropertiesFolder) {
                @mkdir($folderPath, 0777, true);
            }
            return;
        }
        
        $files = @scandir($folderPath);
        if (!$files) return;
        
        $files = array_diff($files, ['.', '..']);
        
        foreach ($files as $file) {
            $filePath = $folderPath . $file;
            
            // Skip subdirectories
            if (is_dir($filePath)) {
                continue;
            }
            
            // Only process image files
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'bmp'];
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                continue;
            }
            
            // Check if already in database
            $stmt = $this->db->prepare("SELECT id FROM images WHERE filename = ? LIMIT 1");
            $stmt->execute([$file]);
            
            if ($stmt->rowCount() > 0) {
                // Already exists, skip
                continue;
            }
            
            // File not in database - need to add it
            $webPath = $isPropertiesFolder 
                ? '/GuillaumeHousing/images/properties/' . $file
                : '/GuillaumeHousing/images/' . $file;
            
            // Try to find property by image path
            $stmt = $this->db->prepare("SELECT id FROM properties WHERE image = ? LIMIT 1");
            $stmt->execute([$webPath]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $propertyId = null;
            
            if ($property) {
                $propertyId = $property['id'];
            } else {
                // Get the first property as default
                $stmt = $this->db->prepare("SELECT id FROM properties ORDER BY id ASC LIMIT 1");
                $stmt->execute();
                $firstProperty = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($firstProperty) {
                    $propertyId = $firstProperty['id'];
                }
            }
            
            // Add the image to database
            if ($propertyId) {
                try {
                    $stmt = $this->db->prepare("
                        INSERT INTO images (property_id, filename, file_path, is_primary, created_at)
                        VALUES (?, ?, ?, 0, NOW())
                        ON DUPLICATE KEY UPDATE property_id = VALUES(property_id)
                    ");
                    $stmt->execute([$propertyId, $file, $webPath]);
                } catch (Exception $e) {
                    // Silently continue if insert fails
                }
            }
        }
    }

    public function upload() {
        try {
            requireAdmin();
            
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                return;
            }
            
            if (empty($_POST['property_id'] ?? '')) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Property ID is required']);
                return;
            }
            
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
                return;
            }
            
            $propertyId = intval($_POST['property_id']);
            $file = $_FILES['image'];
            
            if (!validateImageFile($file)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid image file']);
                return;
            }
            
            $uploadDir = __DIR__ . '/../images/properties/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $filepath = $uploadDir . $filename;
            
            if (!@move_uploaded_file($file['tmp_name'], $filepath)) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to save image']);
                return;
            }
            
            $webPath = '/GuillaumeHousing/images/properties/' . $filename;
            $this->property->saveImage($propertyId, $filename, $webPath, false);
            
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Image uploaded successfully', 'filename' => $filename, 'webPath' => $webPath]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        try {
            requireAdmin();
            
            header('Content-Type: application/json');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
                return;
            }
            
            $stmt = $this->db->prepare("SELECT file_path FROM images WHERE id = ?");
            $stmt->execute([$id]);
            $image = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$image) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Image not found']);
                return;
            }
            
            // Delete file from server
            $filename = basename($image['file_path']);
            
            // Try properties folder first
            $filePath = __DIR__ . '/../images/properties/' . $filename;
            if (!file_exists($filePath)) {
                // Try root images folder
                $filePath = __DIR__ . '/../images/' . $filename;
            }
            
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Delete from database
            $stmt = $this->db->prepare("DELETE FROM images WHERE id = ?");
            $stmt->execute([$id]);
            
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function page() {
        include 'views/admin/images.php';
    }
}
?>
