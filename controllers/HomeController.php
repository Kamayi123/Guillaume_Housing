<?php

require_once 'Database.php';
require_once 'models/Property.php';

class HomeController {
    private $db;
    private $property;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->property = new Property($this->db);
    }

    public function index() {
        include 'views/home.php';
    }

    public function about() {
        include 'views/about.php';
    }

    public function faq() {
        include 'views/faq.php';
    }

    public function getFeaturedProperties() {
        $stmt = $this->property->getAll();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return only first 6 properties for featured section
        return array_slice($properties, 0, 6);
    }
}
