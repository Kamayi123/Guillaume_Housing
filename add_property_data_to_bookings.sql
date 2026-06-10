-- Migration: Add property data columns to bookings table
-- This preserves property information at the time of booking

USE guillaume_housing;

ALTER TABLE bookings
ADD COLUMN property_title VARCHAR(200) NOT NULL AFTER status,
ADD COLUMN property_description TEXT AFTER property_title,
ADD COLUMN property_price DECIMAL(10, 2) NOT NULL AFTER property_description,
ADD COLUMN property_location VARCHAR(200) NOT NULL AFTER property_price,
ADD COLUMN property_bedrooms INT NOT NULL AFTER property_location,
ADD COLUMN property_bathrooms INT NOT NULL AFTER property_bedrooms,
ADD COLUMN property_area INT NOT NULL AFTER property_bathrooms,
ADD COLUMN property_image VARCHAR(255) AFTER property_area,
ADD COLUMN property_type ENUM('Residential', 'Commercial') DEFAULT 'Residential' AFTER property_image,
ADD COLUMN property_status VARCHAR(50) AFTER property_type;
