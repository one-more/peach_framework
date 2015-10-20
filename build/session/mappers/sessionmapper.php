<?php

namespace Session\mappers;

use common\adapters\MysqlAdapter;
use common\mappers\BaseMapper;
use Session\models\SessionModel;

class SessionMapper extends BaseMapper {

    public function get_adapter() {
        return new MysqlAdapter('session');
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
        $fields['vars'] = json_decode($fields['vars'], true);
        return new SessionModel($fields);
    }

    /**
     * @param SessionModel $model
     */
    public function save(SessionModel $model) {
        $fields = $model->to_array();
        $fields['vars'] = json_encode($model->vars);
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