CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `username` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `display_name` TEXT NOT NULL
);
INSERT INTO `users` (`username`, `password`, `display_name`) VALUES ('system', '-', 'System');
INSERT INTO `users` (`username`, `password`, `display_name`) VALUES ('user', '5f4dcc3b5aa765d61d8327deb882cf99', 'Default User');