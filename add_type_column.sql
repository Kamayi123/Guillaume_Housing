-- Add type column and update status enum for properties table

USE guillaume_housing;

-- Add type column
ALTER TABLE properties 
ADD COLUMN type ENUM('Residential', 'Commercial') DEFAULT 'Residential' 
AFTER image;

-- Modify status enum to include for-rent and for-sale
ALTER TABLE properties 
MODIFY COLUMN status ENUM('available', 'rented', 'sold', 'for-rent', 'for-sale') DEFAULT 'available';

-- Verify changes
DESCRIBE properties;
