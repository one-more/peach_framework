<?php

namespace models;

/**
 * Class PagingModel
 * @package models
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