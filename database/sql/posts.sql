CREATE TABLE IF NOT EXISTS `posts` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `create_ts` INTEGER NOT NULL,
  `edit_ts` INTEGER NULL,
  `author_id` INTEGER NOT NULL,
  `editor_id` INTEGER NULL,
  `title` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `summary` TEXT NULL,
  `slug` TEXT NULL,
  `graph_img` TEXT NULL,
  `published` INTEGER NOT NULL,
);