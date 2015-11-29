<?php

namespace common\interfaces;

/**
 * Interface View
 *
 */
interface View {

    public function get_lang_file();

    public function render();

    public function get_lang_vars_base_dir();

    public function load_lang_vars($file);

    public function get_template();

    public function set_compile_dir($path);

    public function set_template_dir($path);

    public function add_template_dir($path);

    public function get_template_dir($index);

    public function assign($name,  $val = null, $no_cache = false);
}