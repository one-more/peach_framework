<?php

abstract class Mapper {

    protected $model_class;

    protected $collection_class;

    abstract protected function get_adapter();
}