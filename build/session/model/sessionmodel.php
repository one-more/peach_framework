<?php

namespace Session\model;

class SessionModel extends \FileModel {

    public function get_file() {
        return 'pfmextension://session'.DS.'resource'.DS.'session_model.json';
    }

    public function __construct() {
        parent::__construct();

        $this->clear_old_records();
    }

    private function clear_old_records() {
        $this->data = $this->select()->where(function($record) {
            return $record['date'] == date('Y-m-d');
        })->toArray();
    }
}