CREATE TABLE `vacation_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `employee_id` bigint(20) unsigned NOT NULL,
  `approved_by_supervisor_id` bigint(20) unsigned,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vacation_applications_employee_id_idx` (`employee_id`),
  KEY `fk_vacation_applications_approved_by_supervisor_id_idx` (`approved_by_supervisor_id`),
  CONSTRAINT `fk_vacation_applications_approved_by_supervisor_id` FOREIGN KEY (`approved_by_supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_vacation_applications_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
