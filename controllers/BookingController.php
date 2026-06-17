<?php

require_once 'Database.php';
require_once 'models/Booking.php';
require_once 'models/Property.php';
require_once 'helpers/auth.php';

class BookingController {
    private $db;
    private $booking;
    private $property;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->booking = new Booking($this->db);
        $this->property = new Property($this->db);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get property data first
                $propertyId = $_POST['property_id'] ?? 0;
                $propertyStmt = $this->property->getById($propertyId);
                // getById returns a PDOStatement; fetch the row
                $property = $propertyStmt ? $propertyStmt->fetch(PDO::FETCH_ASSOC) : false;
                
                if (!$property) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Property not found']);
                } else {
                    header('Location: /GuillaumeHousing/properties?booking=error');
                    exit;
                }
                return;
            }
            
            // Set booking data
            $this->booking->property_id = $propertyId;
            $this->booking->user_id = $_POST['user_id'] ?? 1; // Default user ID for now
            $this->booking->check_in = $_POST['check_in'] ?? date('Y-m-d');
            $this->booking->check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
            $this->booking->guests = $_POST['guests'] ?? 1;
            $this->booking->total_price = $_POST['total_price'] ?? 0;
            $this->booking->status = 'pending';
            
            // Set property data snapshot
            $this->booking->property_title = $property['title'];
            $this->booking->property_description = $property['description'];
            $this->booking->property_price = $property['price'];
            $this->booking->property_location = $property['location'];
            $this->booking->property_bedrooms = $property['bedrooms'];
            $this->booking->property_bathrooms = $property['bathrooms'];
            $this->booking->property_area = $property['area'];
            $this->booking->property_image = $property['image'];
            $this->booking->property_type = $property['type'];
            $this->booking->property_status = $property['status'];

            if ($this->booking->create()) {
                // Check if it's an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Booking request submitted successfully']);
                } else {
                    header('Location: /GuillaumeHousing/properties?booking=success');
                    exit;
                }
            } else {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to submit booking']);
                } else {
                    header('Location: /GuillaumeHousing/properties?booking=error');
                    exit;
                }
            }
            } catch (\Throwable $e) {
                // Return a clean JSON error for AJAX or redirect for regular requests
                error_log('Booking create exception: ' . $e->getMessage());
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    // Include exception message to aid debugging in development
                    echo json_encode(['success' => false, 'message' => 'Server error creating booking', 'error' => $e->getMessage()]);
                } else {
                    header('Location: /GuillaumeHousing/properties?booking=error');
                }
                return;
            }
        }
    }

    public function getAll() {
        $stmt = $this->booking->getAll();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($bookings);
    }

    public function getByUser($userId) {
        $stmt = $this->booking->getByUser($userId);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($bookings);
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
            
            $data = json_decode(file_get_contents('php://input'), true);
            $status = $data['status'] ?? 'pending';
            
            $this->booking->id = $id;
            $this->booking->status = $status;

            if ($this->booking->update()) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Booking updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update booking']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        try {
            requireAdmin();
            
            $this->booking->id = $id;
            
            if ($this->booking->delete()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to delete booking']);
            }
        } catch (\Throwable $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
