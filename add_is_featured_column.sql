-- Migration: Add is_featured column to properties table if not exists
-- This supports the featured properties feature in the admin dashboard

ALTER TABLE properties ADD COLUMN is_featured BOOLEAN DEFAULT FALSE AFTER status;

-- Create index for faster filtering of featured properties
CREATE INDEX idx_featured ON properties(is_featured);

-- Ensure images table exists with proper structure
ALTER TABLE images MODIFY file_path VARCHAR(500) NOT NULL;
ALTER TABLE images ADD INDEX idx_property (property_id);
ALTER TABLE images ADD INDEX idx_primary (is_primary);

-- Commit message: Add is_featured column to properties and ensure images table is indexed
