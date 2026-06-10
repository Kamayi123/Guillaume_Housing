-- Populate all properties from the website views into the database

USE guillaume_housing;

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Clear existing properties
TRUNCATE TABLE properties;

-- Reset auto increment
ALTER TABLE properties AUTO_INCREMENT = 1;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert all 9 properties displayed on the website

-- APARTMENTS (3 properties)
INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, image, type, status) VALUES
('MODERN APARTMENT', 'Beautiful residential apartment with modern amenities', 200000.00, 'Buea', 3, 2, 1100, '/GuillaumeHousing/images/p3.jpg', 'Residential', 'for-rent'),
('FAMILY HOME', 'Spacious family home perfect for families', 10000000.00, 'Buea', 3, 3, 2700, '/GuillaumeHousing/images/ppp.jpg', 'Residential', 'for-sale'),
('MODERN APARTMENTS', 'Commercial apartment complex with great amenities', 150000.00, 'Buea', 2, 2, 1450, '/GuillaumeHousing/images/p55.jpg', 'Commercial', 'for-rent');

-- HOUSES (3 properties)
INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, image, type, status) VALUES
('AWESOME HOUSE', 'Spacious commercial property with multiple offices', 150000.00, 'Bomaka, Mile 18, Buea', 20, 6, 10450, '/GuillaumeHousing/images/wpp2.jpg', 'Commercial', 'for-rent'),
('MODERN HOME', 'Contemporary residential home in prime location', 18000000.00, 'Mayor Street, Buea', 3, 2, 1450, '/GuillaumeHousing/images/p7.jpeg', 'Residential', 'for-sale'),
('CITY CENTER HOUSE', 'Convenient commercial house in the heart of the city', 200000.00, 'Buea Town, Buea', 2, 1, 450, '/GuillaumeHousing/images/ww.jpg', 'Commercial', 'for-rent');

-- OFFICES (3 properties)
INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, image, type, status) VALUES
('AWESOME OFFICE SPACE', 'Premium office space with excellent facilities', 450000.00, 'Buea Town, Buea', 20, 6, 10450, '/GuillaumeHousing/images/os.avif', 'Commercial', 'for-rent'),
('MODERN OFFICE', 'Contemporary office space with modern amenities', 200000.00, 'Molyko, Buea', 3, 2, 1450, '/GuillaumeHousing/images/om.jpg', 'Commercial', 'for-rent'),
('CITY CENTER OFFICE', 'Prime office location in city center', 35000000.00, 'Buea Town, Buea', 2, 1, 450, '/GuillaumeHousing/images/omm.jpg', 'Commercial', 'for-sale');

-- Verify the data
SELECT id, title, type, status, price, location FROM properties ORDER BY id;
