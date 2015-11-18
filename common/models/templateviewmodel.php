<?php

namespace common\models;

/**
 * Class TemplateViewModel
 * @package common\models
 *
 * @property string name
 * @property array data
 * @property string html
 */
class TemplateViewModel extends BaseModel {

    protected $fields = [
        'name' => null,
        'data' => null,
        'html' => null
    ];
}