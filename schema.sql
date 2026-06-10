-- Database Schema for Guillaume Housing

CREATE DATABASE IF NOT EXISTS guillaume_housing;
USE guillaume_housing;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Properties Table
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    location VARCHAR(200) NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    area INT NOT NULL,
    image VARCHAR(255),
    type ENUM('Residential', 'Commercial') DEFAULT 'Residential',
    status ENUM('available', 'rented', 'sold', 'for-rent', 'for-sale') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    user_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    -- Property data snapshot at time of booking
    property_title VARCHAR(200) NOT NULL,
    property_description TEXT,
    property_price DECIMAL(10, 2) NOT NULL,
    property_location VARCHAR(200) NOT NULL,
    property_bedrooms INT NOT NULL,
    property_bathrooms INT NOT NULL,
    property_area INT NOT NULL,
    property_image VARCHAR(255),
    property_type ENUM('Residential', 'Commercial') DEFAULT 'Residential',
    property_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Images Table
CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Property Details Table
CREATE TABLE IF NOT EXISTS property_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    property_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    location VARCHAR(200) NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    area INT NOT NULL,
    image VARCHAR(255),

    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Insert Sample Admin User (password: admin123)
INSERT INTO users (name, email, password, phone, role) VALUES
('Admin User', 'admin@guillaumehousing.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123-456-7890', 'admin');

-- Insert Sample Properties (Only 3 properties)
INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, image, type, status) VALUES
('MODERN APARTMENT', 'Beautiful residential apartment with modern amenities', 200000.00, 'Buea', 3, 2, 1100, '/GuillaumeHousing/images/p3.jpg', 'Residential', 'for-rent'),
('FAMILY HOME', 'Spacious family home perfect for families', 10000000.00, 'Buea', 3, 3, 2700, '/GuillaumeHousing/images/ppp.jpg', 'Residential', 'for-sale'),
('MODERN APARTMENTS', 'Commercial apartment complex', 150000.00, 'Buea', 2, 2, 1450, '/GuillaumeHousing/images/p55.jpg', 'Commercial', 'for-rent');
