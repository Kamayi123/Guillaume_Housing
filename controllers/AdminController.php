<?php

require_once 'Database.php';
require_once 'models/Property.php';
require_once 'models/Booking.php';
require_once 'models/Message.php';
require_once 'models/User.php';

class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getStatistics() {
        $stats = [
            'total_properties' => $this->getTotalProperties(),
            'total_bookings' => $this->getTotalBookings(),
            'total_messages' => $this->getTotalMessages(),
            'total_users' => $this->getTotalUsers()
        ];

        header('Content-Type: application/json');
        echo json_encode($stats);
    }

    private function getTotalProperties() {
        $property = new Property($this->db);
        $stmt = $property->getAll();
        return $stmt->rowCount();
    }

    private function getTotalBookings() {
        $booking = new Booking($this->db);
        $stmt = $booking->getAll();
        return $stmt->rowCount();
    }

    private function getTotalMessages() {
        $message = new Message($this->db);
        $stmt = $message->getAll();
        return $stmt->rowCount();
    }

    private function getTotalUsers() {
        $user = new User($this->db);
        $stmt = $user->getAll();
        return $stmt->rowCount();
    }
}
