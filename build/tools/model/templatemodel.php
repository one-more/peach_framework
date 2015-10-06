<?php

namespace Tools\model;

/**
 * Class TemplateModel
 * @package Tools\model
 *
 * @property string name
 * @property bool can_delete
 */
class TemplateModel extends \BaseModel {
    protected $fields = [
        'name' => null,
        'can_delelete' => false
    ];
}
