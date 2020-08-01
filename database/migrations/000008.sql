CREATE TABLE `company` (
  `id` BIGINT(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `vat` varchar(45) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `industry_id` mediumint(4) unsigned NOT NULL,
  `total_employees` enum('1 - 9','10 - 50','51 - 200','201 - 1000','1000+') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_industry_id_idx` (`industry_id`),
  CONSTRAINT `fk_company_industry_id` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
