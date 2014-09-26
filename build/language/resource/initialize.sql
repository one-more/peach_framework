CREATE TABLE IF NOT EXISTS `language_vars` {
  `id` SERIAL PRIMARY KEY,
  `page`  VARCHAR NOT NULL DEFAULT '',
  `var_key`  VARCHAR NOT NULL,
  `var_value` TEXT NOT NULL,
  `lang`  VARCHAR,
  KEY `key_lang` (`var_key`, `lang`),
  KEY `page`  (`page`)
}