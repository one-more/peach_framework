<?php

namespace User\Mapper;


use User\model\UserModel;
use Validator\LIVR;

/**
 * Class UserMapper
 * @package User\Mapper
 */
class UserMapper extends \BaseMapper {

    protected $table = 'users';

    protected function get_adapter() {
        return new \MysqlAdapter($this->table);
    }

    public function save(UserModel $model) {
        $record = $this->adapter->select()->where([
            'login' => ['=', $model->login]
        ])->execute()->get_result();
        if(empty($record)) {

        } else {

        }
    }

    /**
     * @param array $fields
     * @param null|array $old_fields
     */
    private function validate(array $fields, $old_fields = null) {
        \Application::init_validator();
        $validator = new LIVR([
            'login' => ['required', 'unique_login'],
            'password' => 'hash_password',
            'credentials' => 'required'
        ]);
        if(is_array($old_fields)) {
            $is_login_changed = $old_fields['login'] != $fields['login'];
            $validator->registerRules(['unique_login' => function() use($is_login_changed) {
                return function ($value) use($is_login_changed) {
                    if($is_login_changed && !empty($this->adapter->select()->where([
                            'login' => ['=', $value]
                        ])->excute()->get_result())) {
                        return 'LOGIN_EXISTS';
                    }
                    return null;
                };
            }]);
        }
    }

    private function insert(UserModel $model) {

    }

    private function update(UserModel $model) {

    }

    /**
     * @param $id
     * @return UserModel
     */
    public function find_by_id($id) {
        return new UserModel($this->adapter->select()->where([
            'id' => ['=', $id],
            'and' => [
                'deleted' => ['=', 0]
            ]
        ])->execute()->get_array());
    }

    /**
     * @param $sql
     * @return \BaseCollection
     */
    public function find_by_sql($sql) {
        $collection = new \BaseCollection(UserModel::class);
        $collection->load($this->adapter->execute($sql)->get_arrays());
        return $collection;
    }

    /**
     * @param array $where_statement
     * @return \BaseCollection
     */
    public function find_where(array $where_statement) {
        $collection = new \BaseCollection(UserModel::class);
        $collection->load($this->adapter->select()->where($where_statement)->execute()->get_arrays());
        return $collection;
    }
}