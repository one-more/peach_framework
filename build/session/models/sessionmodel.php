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
 * @property array vars
 *
 */
class SessionModel extends BaseModel {

    protected $fields = [
        'id' => null,
        'datetime' => null,
        'uid' => null,
        'vars' => []
    ];
}