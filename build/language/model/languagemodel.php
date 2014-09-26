<?php

class LanguageModel extends SuperModel {

    public function initialize() {
        $lang   = Application::get_class('Language');
        $sql    = file_get_contents($lang->path.DS.'resource'.DS.'initialize.sql');
        $sql_chunks = explode("\n\n", $sql);
        foreach($sql_chunks as $el) {
            $this->execute($el);
        }
    }
}