CREATE TABLE IF NOT EXISTS `sessions` (
  `id` TEXT NOT NULL PRIMARY KEY,
  `user_id` TEXT NOT NULL,
  `start_time` INTEGER NOT NULL,
  `user_agent` TEXT NOT NULL,
  `ip` TEXT NOT NULL
);