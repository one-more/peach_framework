<?php

class Error {
    public static function initialize() {
        $log_file_path  = ROOT_PATH.DS.'www'.DS.'error.log';
        if(file_exists($log_file_path)) {
            if(filesize($log_file_path) > 4096*1024) {
                unlink($log_file_path);
            }
        } else {
            file_put_contents($log_file_path, '');
            @chmod($log_file_path, 0777);
        }
    }

    public static function log($msg) {
        file_put_contents(ROOT_PATH.DS.'www'.DS.'error.log',date('j.m.Y H:i:s').' - '.$msg."\r\n", FILE_APPEND);
    }
}