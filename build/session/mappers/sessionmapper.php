<?php

namespace Session\mappers;

use common\adapters\MysqlAdapter;
use common\mappers\BaseMapper;
use Session\models\SessionModel;

/**
 * Class SessionMapper
 * @package Session\mappers
 *
 * @property MysqlAdapter $adapter
 */
class SessionMapper extends BaseMapper {

    /**
     * @return MysqlAdapter
     */
    public function get_adapter() {
        return new MysqlAdapter('sessions');
    }

    /**
     * @param $id
     * @return SessionModel
     */
    public function find_by_id($id) {
        $fields = $this->adapter->select()
            ->where([
                'id' => ['=', (int)$id]
            ])
            ->execute()
            ->get_array();
        $fields['variables'] = json_decode($fields['variables'], true);
        return new SessionModel($fields);
    }

    /**
     * @param SessionModel $model
     */
    public function save(SessionModel $model) {
        $fields = $model->to_array();
        $fields['variables'] = json_encode($model->variables);
        if($model->id) {
            unset($fields['id']);
            $this->adapter->update($fields)
                ->where([
                    'id' => ['=', $model->id]
                ])
                ->execute();
        } else {
            $this->adapter->insert($fields)->execute();
            $model->id = $this->adapter->get_insert_id();
        }
    }
}