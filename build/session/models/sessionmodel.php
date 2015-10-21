<?php

namespace Session\models;
use common\models\BaseModel;

/**
 * Class SessionModel
 * @package Session\model
 *
 * @property int id
 * @property string datetime
 * @property int uid
 * @property array variables
 *
 */
class SessionModel extends BaseModel {

    protected $fields = [
        'id' => null,
        'datetime' => null,
        'uid' => null,
        'variables' => []
    ];
}