<?php

namespace Tools\models;
use common\models\BaseModel;

/**
 * Class TemplateModel
 * @package Tools\model
 *
 * @property string name
 * @property bool is_active
 * @property bool can_delete
 * @property bool deleted
 */
class TemplateModel extends BaseModel {

    protected $fields = [
        'id' => null,
        'name' => null,
        'is_active' => null,
        'can_delete' => null,
        'deleted' => null
    ];
}
