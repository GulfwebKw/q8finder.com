CREATE TABLE `service_category` (
                              `id` INT(11) NOT NULL AUTO_INCREMENT,
                              `title_en` VARCHAR(25)  NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                              `title_ar` VARCHAR(250)  NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                              `image` VARCHAR(250)  NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                              `is_enable` TINYINT(1) NOT NULL DEFAULT '1',
                              `created_at` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                              `updated_at` VARCHAR(30) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                              `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                              PRIMARY KEY (`id`) USING BTREE
) COLLATE='latin1_swedish_ci' ENGINE=MyISAM AUTO_INCREMENT=1;
