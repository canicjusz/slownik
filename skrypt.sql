CREATE TABLE `definition` (
	`id` int NOT NULL AUTO_INCREMENT,
	`phrase` varchar(255) NOT NULL,
	`tags` varchar(500) NOT NULL,
	`description_shortened` varchar(150) NOT NULL,
	`description` TEXT(1000) NOT NULL,
	`creation_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`last_edit_date` DATETIME,
	`author_id` int NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `user` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL UNIQUE,
	`email` varchar(255) NOT NULL UNIQUE,
	`password` varchar(255) NOT NULL,
	`avatar` varchar(255) NOT NULL DEFAULT 'user_avatar.png',
	`creation_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`is_admin` BOOLEAN NOT NULL DEFAULT '0',
	`description` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
);

CREATE TABLE `ratio` (
	`user_id` int NOT NULL,
	`definition_id` int NOT NULL,
	`opinion` smallint(2) NOT NULL,
	PRIMARY KEY (`user_id`, `definition_id`)
);

ALTER TABLE `definition` ADD CONSTRAINT `definition_fk0` FOREIGN KEY (`author_id`) REFERENCES `user`(`id`);

ALTER TABLE `ratio` ADD CONSTRAINT `ratio_fk0` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE;

ALTER TABLE `ratio` ADD CONSTRAINT `ratio_fk1` FOREIGN KEY (`definition_id`) REFERENCES `definition`(`id`) ON DELETE CASCADE;

ALTER TABLE definition ADD FULLTEXT idk(phrase,description,tags);