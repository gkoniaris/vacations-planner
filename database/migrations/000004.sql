CREATE TABLE `supervisor_employee` (
  `employee_id` bigint(20) unsigned NOT NULL,
  `supervisor_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`employee_id`,`supervisor_id`),
  KEY `fk_supervisor_employee_supervisor_id_idx` (`supervisor_id`),
  CONSTRAINT `fk_supervisor_employee_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_supervisor_employee_supervisor_id` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;