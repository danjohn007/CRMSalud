-- Migración para agregar campos faltantes al módulo de oportunidades
-- Fecha: 2024-01-15

-- Agregar campo prioridad a la tabla oportunidades
ALTER TABLE `oportunidades` 
ADD COLUMN `prioridad` enum('baja','media','alta') NOT NULL DEFAULT 'media' AFTER `probabilidad`;

-- Actualizar datos existentes con valores por defecto
UPDATE `oportunidades` SET `prioridad` = 'media' WHERE `prioridad` IS NULL;