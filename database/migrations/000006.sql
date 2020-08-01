CREATE TABLE `vacation_application_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(20) NOT NULL,
  `vacation_application_id` bigint(20) unsigned NOT NULL,
  `type` enum('approve','decline') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vacation_application_links_vacation_applicaiton_id_idx` (`vacation_application_id`),
  KEY `idx_vacation_application_links_hash` (`hash`),
  CONSTRAINT `fk_vacation_application_links_vacation_applicaiton_id` 
  FOREIGN KEY (`vacation_application_id`) REFERENCES `vacation_applications` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
