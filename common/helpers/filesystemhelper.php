<?php

namespace common\helpers;

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
        $it = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it,
            \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }

    public static function copy_dir($source, $dest) {
        mkdir($dest, 0755);
        foreach (
         $iterator = new \RecursiveIteratorIterator(
          new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
          \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                /**
                 * @var $iterator \RecursiveDirectoryIterator
                 */
                mkdir($dest.DS.$iterator->getSubPathName());
            } else {
                copy($item, $dest.DS.$iterator->getSubPathName());
            }
        }
    }
}