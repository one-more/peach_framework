CREATE TABLE IF NOT EXISTS `users` (
  `id`        serial PRIMARY KEY ,
  `login`     VARCHAR (255) UNIQUE NOT NUll,
  `password`  VARCHAR (255) NOT NULL,
  `credentials` ENUM('user', 'administrator', 'super_administrator') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS `session` (
  `id`    serial PRIMARY KEY,
  `uid`  BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `datetime` DATETIME DEFAULT NULL,
  `vars`  VARCHAR(255) NOT NULL
);

INSERT INTO `users` SET
`login`       = 'root',
`password`    = 'root',
`credentials` = 'super_administrator';