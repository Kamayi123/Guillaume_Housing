<?php

require_once 'Database.php';
require_once 'models/User.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Show login form
    public function showLoginForm() {
        require_once 'helpers/auth.php';
        // If already logged in and admin, redirect to admin
        if (isset($_SESSION['user']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header('Location: /GuillaumeHousing/admin');
            exit;
        }
        require_once 'views/admin/login.php';
    }

    // Handle login
    public function login() {
        // Simple POST handling
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User($this->db);
        $user = $userModel->verifyCredentials($email, $password);
        if ($user) {
            // Set session
            $_SESSION['user'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'] ?? '';
            $_SESSION['user_role'] = $user['role'] ?? 'user';

            // Redirect to admin dashboard
            header('Location: /GuillaumeHousing/admin');
            exit;
        }

        // Invalid credentials
        $error = 'Invalid email or password';
        require_once 'views/admin/login.php';
    }

    // Logout
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /GuillaumeHousing/');
        exit;
    }
}
