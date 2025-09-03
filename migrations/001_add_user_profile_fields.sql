-- Migration: Add profile fields to usuarios table
-- Date: 2024-01-01
-- Description: Add imagen_perfil, telefono, and direccion fields to usuarios table

-- Add new columns to usuarios table
ALTER TABLE `usuarios` 
ADD COLUMN `imagen_perfil` varchar(255) NULL AFTER `rol`,
ADD COLUMN `telefono` varchar(20) NULL AFTER `imagen_perfil`,
ADD COLUMN `direccion` text NULL AFTER `telefono`;

-- Create uploads directory structure if needed
-- Note: This should be done manually on the server
-- mkdir -p uploads/profiles

-- Set default values for existing users (optional)
-- UPDATE `usuarios` SET `imagen_perfil` = NULL, `telefono` = NULL, `direccion` = NULL WHERE `imagen_perfil` IS NULL;