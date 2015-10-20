<?php

namespace common\mappers;

use common\interfaces\Collection;
use common\interfaces\Mapper;
use common\interfaces\Model;

abstract class BaseMapper implements Mapper {

    /**
     * @var $model_class Model
     */
    protected $model_class = 'BaseModel';

    /**
     * @var $collection_class Collection
     */
    protected $collection_class = 'BaseCollection';

    protected $adapter;

    protected $validation_errors;

    abstract protected function get_adapter();

    public function __construct() {
        $this->adapter = $this->get_adapter();
    }

    public function set_adapter($adapter) {
        $this->adapter = $adapter;
    }

    public function get_validation_errors() {
        return $this->validation_errors;
    }
}