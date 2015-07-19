<?php

class SystemInitModel extends MysqlModel {

    protected function get_table() {
        return '';
    }

    public function initialize() {
        $sql    = file_get_contents(ROOT_PATH.DS.'resource'.DS.'initialize.sql');
        $sql_chunks = explode("\n\n", $sql);
        foreach($sql_chunks as $el) {
            $this->execute($el);
        }
    }
}