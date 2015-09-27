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
            if($fields = $this->validate($model->to_array())) {
                $this->insert((array)$fields);
                return true;
            } else {
                return false;
            }
        } else {
            if($fields = $this->validate($model->to_array(), $record)) {
                $this->update((array)$fields, $record['id']);
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
                        ])->excute()->get_result())) {
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
                    ])->excute()->get_result();
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
                    $output_arr = $this->crypt_password($fields['login'], $value);
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

    public function crypt_password($login, $password) {
        return crypt(trim($password), sha1($password).sha1($login).uniqid('password', true));
    }

    private function insert(array $fields) {
        $fields['remember_hash'] = password_hash($fields['password'].$fields['login'], PASSWORD_DEFAULT);
        if(!empty($fields['id'])) {
            unset($fields['id']);
        }
        $this->adapter->insert($fields)->execute();
    }

    private function update(array $fields, $id) {
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

    public function delete(UserModel $model) {
        $this->adapter->update([
            'deleted' => 1
        ])->where([
            'id' => ['=', $model->id]
        ])->execute();
    }

    public function get_page($number, $per_page) {
        if($number < 0) {
            $number = 0;
        }
        yield $this->adapter->select()->limit($per_page)
            ->offset(($number-1)*$per_page)->execute()->get_arrays();
    }
}