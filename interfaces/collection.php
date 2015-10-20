<?php

namespace interfaces;

interface Collection extends \Iterator, \ArrayAccess {

    public function load(array $models);

    public function get($id);

    public function add(Model $model);

    public function count();

    public function one();
}