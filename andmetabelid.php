kasutajate tabel

CREATE TABLE `if18_lisam_va_1`.`vpusers` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `firstname` VARCHAR(30) NOT NULL , `lastname` VARCHAR(30) NOT NULL , `birthdate` DATE NOT NULL , `gender` INT(1) NOT NULL , `email` VARCHAR(100) NOT NULL , `password` VARCHAR(60) NOT NULL , `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

