-- Migration: Add profile fields to usuarios table
-- Date: 2025-01-02
-- Description: Add profile image, phone, and address fields to support enhanced user profiles

-- Add new columns to usuarios table
ALTER TABLE `usuarios` 
ADD COLUMN `telefono` varchar(20) NULL AFTER `email`,
ADD COLUMN `direccion` text NULL AFTER `telefono`,
ADD COLUMN `profile_image` varchar(255) NULL AFTER `direccion`;

-- Add index for better performance
ALTER TABLE `usuarios` 
ADD INDEX `idx_telefono` (`telefono`);

-- Update existing users with default values (optional)
-- UPDATE `usuarios` SET `telefono` = NULL, `direccion` = NULL, `profile_image` = NULL WHERE `telefono` IS NULL;

-- Comments for documentation
-- telefono: Phone number (optional, varchar 20 chars)
-- direccion: Full address (optional, text field)  
-- profile_image: Path to uploaded profile image (optional, varchar 255 chars)