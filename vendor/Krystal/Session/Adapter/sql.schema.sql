
CREATE TABLE `sessions` (
	
	`id` CHAR(64) PRIMARY KEY, 
	`data` TEXT DEFAULT NULL, 
	`touched` INT(10)
	
) DEFAULT CHARSET=UTF8;
