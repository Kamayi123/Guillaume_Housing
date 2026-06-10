-- Add property snapshot columns to bookings table
-- These columns store property data at the time of booking

USE guillaume_housing;

ALTER TABLE bookings
ADD COLUMN IF NOT EXISTS property_title VARCHAR(200) NOT NULL AFTER status,
ADD COLUMN IF NOT EXISTS property_description TEXT AFTER property_title,
ADD COLUMN IF NOT EXISTS property_price DECIMAL(10, 2) NOT NULL AFTER property_description,
ADD COLUMN IF NOT EXISTS property_location VARCHAR(200) NOT NULL AFTER property_price,
ADD COLUMN IF NOT EXISTS property_bedrooms INT NOT NULL AFTER property_location,
ADD COLUMN IF NOT EXISTS property_bathrooms INT NOT NULL AFTER property_bedrooms,
ADD COLUMN IF NOT EXISTS property_area INT NOT NULL AFTER property_bathrooms,
ADD COLUMN IF NOT EXISTS property_image VARCHAR(255) AFTER property_area,
ADD COLUMN IF NOT EXISTS property_type ENUM('Residential', 'Commercial') DEFAULT 'Residential' AFTER property_image,
ADD COLUMN IF NOT EXISTS property_status VARCHAR(50) AFTER property_type;

-- Verify the new columns
DESCRIBE bookings;
