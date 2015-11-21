<?php

namespace common\interfaces;

interface Template {

	public function start();

	public function get_path();

    public function get_lang_path();
}