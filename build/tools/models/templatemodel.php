<?php

namespace Tools\models;
use common\models\BaseModel;

/**
 * Class TemplateModel
 * @package Tools\model
 *
 * @property string name
 * @property bool can_delete
 */
class TemplateModel extends BaseModel {

    protected $fields = [
        'id' => null,
        'name' => null,
        'can_delete' => false
    ];
}
