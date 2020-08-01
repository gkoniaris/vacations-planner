ALTER TABLE `company` 
ADD COLUMN `companycol` VARCHAR(45) NULL AFTER `total_employees`,
ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `companycol`,
ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`;
