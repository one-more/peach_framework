<?php

class FileSystemHelper {

    public static function init_dirs() {
        $system_dirs    = [
            ROOT_PATH.DS.'extensions'
        ];
        foreach($system_dirs as $el) {
            if(!file_exists($el)) {
                mkdir($el);
                chmod($el, 0777);
            }
        }
    }

    public static function remove_dir($path) {
        $it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }
}