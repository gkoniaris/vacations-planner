CREATE TABLE `sessions` (
  `user_id` bigint(20) unsigned NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`session_id`),
  UNIQUE KEY `session_id_UNIQUE` (`session_id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
