CREATE TABLE `user` (
  `user_id` INT(10) NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(255) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `hash` VARCHAR(255) NOT NULL,
  PRIMARY KEY `user_id` (`user_id`)
);