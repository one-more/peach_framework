<?php

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
}