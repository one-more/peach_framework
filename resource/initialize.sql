CREATE TABLE IF NOT EXISTS `users` (
  `id`        SERIAL PRIMARY KEY ,
  `login`     VARCHAR (255) NOT NUll,
  `password`  VARCHAR (255) NOT NULL,
  `credentials` ENUM('user', 'administrator', 'super_administrator') DEFAULT 'user',
  `remember_hash` VARCHAR(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) not null default 0
);

CREATE TABLE IF NOT EXISTS `sessions` (
  `id`   SERIAL PRIMARY KEY,
  `uid`  BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `variables`  VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `templates` (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) UNIQUE NOT NULL DEFAULT '',
  is_active TINYINT NOT NULL DEFAULT 0,
  can_delete TINYINT NOT NULL DEFAULT 0,
  deleted TINYINT NOT NULL DEFAULT 0
);

INSERT INTO templates SET name = 'Starter', is_active = 1;

INSERT INTO `users` SET
  `login`       = 'root',
  `password`    = '',
  `credentials` = 'super_administrator';

INSERT INTO `sessions` SET `uid` = 1, `variables` = '[]';