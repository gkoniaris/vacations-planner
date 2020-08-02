ALTER TABLE `companies` 
ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `total_employees`,
ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`;
