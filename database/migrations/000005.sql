CREATE TABLE `pto` (
  `employee_id` bigint(20) unsigned NOT NULL,
  `remaining_days` tinyint(2) NOT NULL DEFAULT '21',
  PRIMARY KEY (`employee_id`),
  CONSTRAINT `fk_vacation_days_user_id` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
