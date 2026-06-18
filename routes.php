<?php

// Simple routing system for Guillaume Housing

require_once 'controllers/HomeController.php';
require_once 'controllers/PropertyController.php';
require_once 'controllers/ContactController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/BookingController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ImageController.php';

class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($method, $uri) {
        // Remove query string and trailing slashes
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/');
        $uri = $uri ?: '/';

        foreach ($this->routes as $route) {
            // Convert route path to regex pattern
            $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                $controller = new $route['controller']();
                $action = $route['action'];
                
                return call_user_func_array([$controller, $action], $matches);
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}

// Initialize router
$router = new Router();

// Define routes
// Home routes
$router->addRoute('GET', '/', 'HomeController', 'index');
$router->addRoute('GET', '/about', 'HomeController', 'about');
$router->addRoute('GET', '/faq', 'HomeController', 'faq');

// Property routes
$router->addRoute('GET', '/properties', 'PropertyController', 'index');
$router->addRoute('GET', '/property/{id}', 'PropertyController', 'details');
$router->addRoute('GET', '/api/properties', 'PropertyController', 'getAll');
$router->addRoute('GET', '/api/properties/featured', 'PropertyController', 'getAll');
$router->addRoute('GET', '/api/property/{id}', 'PropertyController', 'getById');
$router->addRoute('POST', '/api/property/create', 'PropertyController', 'create');
$router->addRoute('POST', '/api/property/update/{id}', 'PropertyController', 'update');
$router->addRoute('POST', '/api/property/delete/{id}', 'PropertyController', 'delete');

// Contact routes
$router->addRoute('GET', '/contact', 'ContactController', 'index');
$router->addRoute('POST', '/contact', 'ContactController', 'submit');
$router->addRoute('GET', '/api/messages', 'ContactController', 'getAll');
$router->addRoute('POST', '/api/message/delete/{id}', 'ContactController', 'delete');

// Admin routes
$router->addRoute('GET', '/admin', 'AdminController', 'dashboard');
$router->addRoute('GET', '/admin/login', 'AuthController', 'showLoginForm');
$router->addRoute('POST', '/admin/login', 'AuthController', 'login');
$router->addRoute('GET', '/admin/logout', 'AuthController', 'logout');

// Admin pages
$router->addRoute('GET', '/admin/properties', 'AdminController', 'properties');
$router->addRoute('GET', '/admin/properties/create', 'AdminController', 'createProperty');
$router->addRoute('GET', '/admin/properties/edit/{id}', 'AdminController', 'editProperty');
$router->addRoute('GET', '/admin/users', 'AdminController', 'users');
$router->addRoute('GET', '/admin/bookings', 'AdminController', 'bookings');
$router->addRoute('GET', '/admin/messages', 'AdminController', 'messages');
$router->addRoute('GET', '/admin/analytics', 'AdminController', 'analytics');
$router->addRoute('GET', '/admin/images', 'ImageController', 'page');

// Admin API endpoints
$router->addRoute('GET', '/api/admin/stats', 'AdminController', 'getStatistics');
$router->addRoute('GET', '/api/admin/users', 'AdminController', 'getUsers');
$router->addRoute('POST', '/api/admin/user/role/{id}', 'AdminController', 'updateUserRole');
$router->addRoute('POST', '/api/admin/user/delete/{id}', 'AdminController', 'deleteUser');
$router->addRoute('GET', '/api/admin/analytics', 'AdminController', 'getAnalytics');
$router->addRoute('GET', '/api/admin/export/bookings', 'AdminController', 'exportBookings');
$router->addRoute('GET', '/api/admin/export/properties', 'AdminController', 'exportProperties');
$router->addRoute('GET', '/api/admin/export/messages', 'AdminController', 'exportMessages');

// Image routes
$router->addRoute('GET', '/api/admin/images', 'ImageController', 'getAll');
$router->addRoute('POST', '/api/image/upload', 'ImageController', 'upload');
$router->addRoute('POST', '/api/image/delete/{id}', 'ImageController', 'delete');

// Booking routes
$router->addRoute('POST', '/booking/create', 'BookingController', 'create');
$router->addRoute('GET', '/api/bookings', 'BookingController', 'getAll');
$router->addRoute('GET', '/api/bookings/user/{id}', 'BookingController', 'getByUser');
$router->addRoute('POST', '/api/booking/update/{id}', 'BookingController', 'update');
$router->addRoute('POST', '/api/booking/delete/{id}', 'BookingController', 'delete');

return $router;
