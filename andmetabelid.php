kasutajate tabel

CREATE TABLE `if18_lisam_va_1`.`vpusers` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `firstname` VARCHAR(30) NOT NULL , `lastname` VARCHAR(30) NOT NULL , `birthdate` DATE NOT NULL , `gender` INT(1) NOT NULL , `email` VARCHAR(100) NOT NULL , `password` VARCHAR(60) NOT NULL , `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

üleslaetavad fotod
CREATE TABLE `if18_lisam_va_1`.`vpphotos` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `userid` INT(11) NOT NULL , `filename` VARCHAR(40) NOT NULL , `created` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP , `alttext` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_estonian_ci NULL , `privacy` INT(1) NOT NULL , `deleted` DATE NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `if18_lisam_va_1`.`vp_user_pictures` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `userid` INT(11) NOT NULL , `filename` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL , `created` DATE NOT NULL , `deleted` DATE NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;