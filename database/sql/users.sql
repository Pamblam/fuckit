CREATE TABLE IF NOT EXISTS `users` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`username` TEXT NOT NULL,
	`password` TEXT NOT NULL,
	`display_name` TEXT NOT NULL
);
INSERT INTO `users` (`username`, `password`, `display_name`) VALUES ('system', '-', 'System');