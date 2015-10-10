<?php

namespace Session\model;

/**
 * Class SessionModel
 * @package Session\model
 *
 * @property int id
 * @property string date
 * @property int uid
 * @property array variables
 *
 */
class SessionModel extends \BaseModel {

    protected $fields = [
        'id' => null,
        'date' => null,
        'uid' => null,
        'variables' => []
    ];
}