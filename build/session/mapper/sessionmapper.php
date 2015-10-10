<?php

namespace Session\mapper;

use Session\model\SessionModel;

class SessionMapper extends \BaseMapper {

    public function get_adapter() {
        return new \FileAdapter('pfmextension://session'.DS.'resource'.DS.'session_model.json');
    }

    /**
     * @param $id
     * @return SessionModel
     */
    public function find_by_id($id) {
        return new SessionModel($this->adapter->select()->where(function($record) use($id) {
            return $record['id'] == $id;
        })->firstOrDefault([]));
    }

    /**
     * @param SessionModel $model
     */
    public function save(SessionModel $model) {
        if($model->id) {
            $this->adapter->update($model->id, $model->to_array());
        } else {
            $model->id = $this->adapter->insert($model->to_array());
        }
    }
}