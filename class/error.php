<?php

class Error {

    public static function initialize() {
        $log_file_path = WEB_ROOT.DS.'error.log';
        if(file_exists($log_file_path)) {
            if(filesize($log_file_path) > (1024*1024*3)) {
                unlink($log_file_path);
            }
        } else {
            file_put_contents($log_file_path, '');
            @chmod($log_file_path, 0777);
        }
    }

    public static function log() {
        $msg = implode(' ', func_get_args());
        file_put_contents(WEB_ROOT.DS.'error.log', date('j.m.Y H:i:s').' - '.$msg.PHP_EOL, FILE_APPEND);
    }
}