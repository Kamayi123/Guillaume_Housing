<?php

require_once 'Database.php';
require_once 'models/Property.php';
require_once 'models/Booking.php';
require_once 'models/Message.php';
require_once 'models/User.php';
require_once 'helpers/auth.php';

class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getStatistics() {
        requireAdmin();

        $stats = [
            'total_properties' => $this->getTotalProperties(),
            'total_bookings' => $this->getTotalBookings(),
            'total_messages' => $this->getTotalMessages(),
            'total_users' => $this->getTotalUsers()
        ];

        header('Content-Type: application/json');
        echo json_encode($stats);
    }

    // Render admin dashboard view
    public function dashboard() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/dashboard.php';
        require_once 'views/admin/footer.php';
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

    // Properties management
    public function properties() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/properties.php';
        require_once 'views/admin/footer.php';
    }

    public function createProperty() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/property_form.php';
        require_once 'views/admin/footer.php';
    }

    public function editProperty($id) {
        requireAdmin();
        $propertyModel = new Property($this->db);
        $stmt = $propertyModel->getById($id);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/property_form.php';
        require_once 'views/admin/footer.php';
    }

    // Users management
    public function users() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/users.php';
        require_once 'views/admin/footer.php';
    }

    public function getUsers() {
        requireAdmin();
        $user = new User($this->db);
        $stmt = $user->getAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function updateUserRole($id) {
        requireAdmin();
        $data = json_decode(file_get_contents('php://input'), true);
        $role = $data['role'] ?? 'user';
        
        $query = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $stmt->execute()]);
    }

    public function deleteUser($id) {
        requireAdmin();
        $user = new User($this->db);
        $user->id = $id;
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $user->delete()]);
    }

    // Bookings management
    public function bookings() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/bookings.php';
        require_once 'views/admin/footer.php';
    }

    // Messages management
    public function messages() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/messages.php';
        require_once 'views/admin/footer.php';
    }

    // Analytics
    public function analytics() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/analytics.php';
        require_once 'views/admin/footer.php';
    }

    public function getAnalytics() {
        requireAdmin();
        
        // Calculate analytics
        $booking = new Booking($this->db);
        $stmt = $booking->getAll();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalRevenue = array_sum(array_column($bookings, 'total_price'));
        $avgBookingValue = count($bookings) > 0 ? $totalRevenue / count($bookings) : 0;
        
        // Bookings timeline (last 30 days)
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count 
                  FROM bookings 
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                  GROUP BY DATE(created_at)
                  ORDER BY date";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $timeline = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Occupancy rate (simplified)
        $confirmedCount = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
        $occupancyRate = count($bookings) > 0 ? round(($confirmedCount / count($bookings)) * 100) : 0;
        
        $analytics = [
            'total_revenue' => $totalRevenue,
            'avg_booking_value' => round($avgBookingValue, 2),
            'occupancy_rate' => $occupancyRate,
            'bookings_timeline' => $timeline
        ];
        
        header('Content-Type: application/json');
        echo json_encode($analytics);
    }

    // Export functionality
    public function exportBookings() {
        requireAdmin();
        $booking = new Booking($this->db);
        $stmt = $booking->getAll();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="bookings_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Property', 'Check-in', 'Check-out', 'Guests', 'Total Price', 'Status', 'Created']);
        
        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking['id'],
                $booking['property_title'],
                $booking['check_in'],
                $booking['check_out'],
                $booking['guests'],
                $booking['total_price'],
                $booking['status'],
                $booking['created_at']
            ]);
        }
        
        fclose($output);
    }

    public function exportProperties() {
        requireAdmin();
        $property = new Property($this->db);
        $stmt = $property->getAll();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="properties_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Title', 'Location', 'Price', 'Bedrooms', 'Bathrooms', 'Area', 'Type', 'Status', 'Created']);
        
        foreach ($properties as $property) {
            fputcsv($output, [
                $property['id'],
                $property['title'],
                $property['location'],
                $property['price'],
                $property['bedrooms'],
                $property['bathrooms'],
                $property['area'],
                $property['type'] ?? 'Residential',
                $property['status'],
                $property['created_at']
            ]);
        }
        
        fclose($output);
    }

    // Settings
    public function settings() {
        requireAdmin();
        require_once 'views/admin/header.php';
        require_once 'views/admin/sidebar.php';
        require_once 'views/admin/settings.php';
        require_once 'views/admin/footer.php';
    }
}
