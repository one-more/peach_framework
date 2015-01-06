CREATE TABLE IF NOT EXISTS `language_vars` (
  `id` SERIAL PRIMARY KEY,
  `page`  VARCHAR(255) NOT NULL DEFAULT '',
  `var_key`  VARCHAR(255) NOT NULL,
  `var_value` TEXT NOT NULL,
  `lang`  VARCHAR(255) NOT NULL,
  KEY `key_lang` (`var_key`, `lang`),
  KEY `page`  (`page`)
)