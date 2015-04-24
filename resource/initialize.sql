CREATE TABLE IF NOT EXISTS `users` (
  `id`        serial PRIMARY KEY ,
  `login`     VARCHAR (255) UNIQUE NOT NUll,
  `password`  VARCHAR (255) NOT NULL,
  `credentials` ENUM('user', 'administrator', 'super_administrator') DEFAULT 'user',
  `remember_hash` VARCHAR(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) not null default 0
);

CREATE TABLE IF NOT EXISTS `session` (
  `id`    serial PRIMARY KEY,
  `uid`  BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `datetime` DATETIME DEFAULT NULL,
  `vars`  VARCHAR(255) NOT NULL
);

INSERT INTO `users` SET
`login`       = 'root',
`password`    = '',
`credentials` = 'super_administrator';