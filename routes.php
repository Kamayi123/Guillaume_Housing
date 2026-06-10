<?php

// Simple routing system for Guillaume Housing

require_once 'controllers/HomeController.php';
require_once 'controllers/PropertyController.php';
require_once 'controllers/ContactController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/BookingController.php';

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
$router->addRoute('GET', '/dashboard', 'HomeController', 'faq');
$router->addRoute('GET', '/api/admin/stats', 'AdminController', 'getStatistics');

// Booking routes
$router->addRoute('POST', '/booking/create', 'BookingController', 'create');
$router->addRoute('GET', '/api/bookings', 'BookingController', 'getAll');
$router->addRoute('GET', '/api/bookings/user/{id}', 'BookingController', 'getByUser');
$router->addRoute('POST', '/api/booking/update/{id}', 'BookingController', 'update');
$router->addRoute('POST', '/api/booking/delete/{id}', 'BookingController', 'delete');

return $router;
