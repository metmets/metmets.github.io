CREATE TABLE `phpvms_downloads` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `pid` INT,
   `name` VARCHAR(50),
   `link` TEXT ASCII,
   `image` TEXT ASCII,
   `hits` INT,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM;

CREATE TABLE `phpvms_expenses` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`cost` FLOAT NOT NULL ,
	`fixed` INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS `phpvms_financedata` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`month` int(11) NOT NULL,
	`year` int(11) NOT NULL,
	`data` text NOT NULL,
	`total` float NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM;

ALTER TABLE `phpvms_downloads` ADD `description` TEXT ASCII AFTER `name`;

ALTER TABLE `phpvms_aircraft` ADD `maxpax` FLOAT NOT NULL AFTER `cruise`;
ALTER TABLE `phpvms_aircraft` ADD `maxcargo` FLOAT NOT NULL AFTER `maxpax`;
ALTER TABLE `phpvms_aircraft` ADD `enabled` INT NOT NULL DEFAULT 1;

ALTER TABLE `phpvms_airports` ADD `fuelprice` FLOAT NOT NULL ;

ALTER TABLE `phpvms_pilots` ADD `bgimage` VARCHAR( 30 ) NOT NULL DEFAULT 'background.png' AFTER `salt`;
ALTER TABLE `phpvms_pilots` ADD `joindate` datetime NOT NULL default '0000-00-00 00:00:00';
ALTER TABLE `phpvms_pilots` ADD `lastpirep` datetime NOT NULL default '0000-00-00 00:00:00';

ALTER TABLE `phpvms_expenses` ADD `type` VARCHAR ( 1 ) NOT NULL DEFAULT 'M';

ALTER TABLE `phpvms_schedules` CHANGE `flighttime` `flighttime` FLOAT NOT NULL;
ALTER TABLE `phpvms_schedules` ADD `maxload` INT NOT NULL AFTER `flighttime`;
ALTER TABLE `phpvms_schedules` ADD `price` FLOAT NOT NULL AFTER `maxload`;
ALTER TABLE `phpvms_schedules` ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P' AFTER `price`;

ALTER TABLE `phpvms_pireps` ADD `load` INT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `fuelused` FLOAT NOT NULL DEFAULT 5.10 AFTER `load`;
ALTER TABLE `phpvms_pireps` ADD `fuelprice` FLOAT NOT NULL DEFAULT 5.10 AFTER `fuelused`;
ALTER TABLE `phpvms_pireps` ADD `price` FLOAT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `flighttype` VARCHAR( 1 ) NOT NULL DEFAULT 'P';
ALTER TABLE `phpvms_pireps` ADD `pilotpay` FLOAT NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `expenses` FLOAT NOT NULL DEFAULT 0;
ALTER TABLE `phpvms_pireps` ADD `expenselist` BLOB NOT NULL;
ALTER TABLE `phpvms_pireps` ADD `source` VARCHAR ( 25 ) NOT NULL;
ALTER TABLE `phpvms_pireps` CHANGE `fuelused` `fuelused` FLOAT NOT NULL;