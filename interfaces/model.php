<?php

namespace interfaces;

interface Model {

    public function get_id();

    public function load(array $fields);

    public function to_array();
}