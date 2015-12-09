<?php

namespace common\models;

/**
 * Class PageModel
 * @package common\models
 *
 * @property string name
 * @property array params
 */
class PageModel extends BaseModel {

    public $fields = [
        'name' => null,
        'params' => null
    ];
}