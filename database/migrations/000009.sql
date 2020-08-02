ALTER TABLE `users` 
ADD COLUMN `company_id` BIGINT(20) UNSIGNED NOT NULL AFTER `role`;
ALTER TABLE `users` 
ADD CONSTRAINT `fk_users_company_id`
  FOREIGN KEY (`company_id`)
  REFERENCES `companiessss` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
