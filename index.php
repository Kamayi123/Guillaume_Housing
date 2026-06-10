<?php

// Main entry point for Guillaume Housing website

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the router
$router = require_once 'routes.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Remove base path if application is in subdirectory
$basePath = '/GuillaumeHousing';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Dispatch the request
$router->dispatch($method, $uri);
