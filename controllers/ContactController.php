<?php

require_once 'Database.php';
require_once 'models/Message.php';

class ContactController {
    private $db;
    private $message;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->message = new Message($this->db);
    }

    public function index() {
        include 'views/contact.php';
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->message->name = $_POST['name'] ?? '';
            $this->message->email = $_POST['email'] ?? '';
            $this->message->message = $_POST['message'] ?? '';

            if ($this->message->create()) {
                // Check if it's an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
                } else {
                    // Regular form submission - redirect back with success
                    header('Location: /GuillaumeHousing/contact?success=1');
                    exit;
                }
            } else {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
                } else {
                    header('Location: /GuillaumeHousing/contact?error=1');
                    exit;
                }
            }
        }
    }

    public function getAll() {
        $stmt = $this->message->getAll();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function delete($id) {
        $this->message->id = $id;
        
        if ($this->message->delete()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to delete message']);
        }
    }
}
