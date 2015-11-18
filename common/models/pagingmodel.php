<?php

namespace common\models;

/**
 * Class PagingModel
 * @package common\models
 *
 * @property int current
 * @property int pages
 */
class PagingModel extends BaseModel {

    protected $fields = [
        'current' => null,
        'pages' => null
    ];
}