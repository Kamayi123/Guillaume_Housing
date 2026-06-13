<?php

// Simple auth helper functions
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        // Redirect to admin login
        header('Location: /GuillaumeHousing/admin/login');
        exit;
    }
}

?>
