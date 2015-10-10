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
        $record = null;
        if($model->id) {
            $record = $this->find_by_id($model->id);
        }
        if(is_null($record)) {
            if($fields = $this->validate($model->to_array())) {
                $this->insert((array)$fields);
                $model->id = $this->adapter->get_insert_id();
                return true;
            } else {
                return false;
            }
        } else {
            if($fields = $this->validate($model->to_array(), $record->to_array())) {
                $this->update((array)$fields, $record->id);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param array $fields
     * @param null|array $old_fields
     * @throws \InvalidArgumentException
     * @return bool
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
                        ])->execute()->get_result())) {
                        return 'LOGIN_EXISTS';
                    }
                    return null;
                };
            }]);
        } else {
            $validator->registerRules(['unique_login' => function() {
                return function ($value) {
                    $user = $this->adapter->select()->where([
                        'login' => ['=', $value]
                    ])->execute()->get_result();
                    if($user) {
                        return 'LOGIN_EXISTS';
                    }
                    return null;
                };
            }]);
        }
        $validator->registerRules(['hash_password' => function() use($fields) {
            return function($value, $undef, &$output_arr) use($fields) {
                if(trim($value)) {
                    $output_arr = password_hash($value, PASSWORD_BCRYPT);
                    return;
                }
                return null;
            };
        }]);

        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        $lang_vars = new \LanguageFile('mapper'.DS.'usermapper.json', $ext->get_lang_path());
        $this->validation_errors = $validator->getErrors($lang_vars['errors']);
        return $validator->validate($fields);
    }

    /**
     * @param array $fields
     */
    private function insert(array $fields) {
        $fields['remember_hash'] = password_hash($fields['password'].$fields['login'], PASSWORD_DEFAULT);
        if(!empty($fields['id'])) {
            unset($fields['id']);
        }
        $this->adapter->insert($fields)->execute();
    }

    /**
     * @param array $fields
     * @param $id
     */
    private function update(array $fields, $id) {
        if(!empty($fields['id'])) {
            unset($fields['id']);
        }
        $this->adapter->update($fields)
            ->where([
                'id' => ['=', $id]
            ])->execute();
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
        $records = array_filter($this->adapter->execute($sql)->get_arrays(), function($record) {
            return $record['deleted'] == 0;
        });
        $collection->load($records);
        return $collection;
    }

    /**
     * @param array $where_statement
     * @return \BaseCollection
     */
    public function find_where(array $where_statement) {
        $collection = new \BaseCollection(UserModel::class);
        $records = array_filter($this->adapter->select()->where($where_statement)->execute()->get_arrays(), function($record) {
            return $record['deleted'] == 0;
        });
        $collection->load($records);
        return $collection;
    }

    /**
     * @param UserModel $model
     */
    public function delete(UserModel $model) {
        $this->adapter->update([
            'deleted' => 1
        ])->where([
            'id' => ['=', $model->id]
        ])->execute();
    }

    /**
     * @param $number
     * @param $per_page
     * @return mixed
     */
    public function get_page($number = 1, $per_page = 30) {
        $number < 0 && $number = 0;
        $records = $this->adapter->select()->limit($per_page)
            ->offset(($number-1)*$per_page)->execute()->get_arrays();
        $collection = new \BaseCollection(UserModel::class);
        $collection->load($records);
        return $collection;
    }
}
