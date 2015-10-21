<?php

use \common\classes\Application;
use \User\mappers\UserMapper;
use User\auth\UserAuth;

/**
 * Class User
 *
 * @decorate \common\decorators\AnnotationsDecorator
 */
class User implements \common\interfaces\Extension {
    use \common\traits\TraitExtension;

    const credentials_user = 'user';

    const credentials_admin = 'administrator';

    const credentials_super_admin = 'super_administrator';

    public function __construct() {
        $this->register_autoload();
    }

    /**
     * @return \User\models\UserModel
     * @throws InvalidArgumentException
     * @throws ErrorException
     */
    public function get_identity() {
        $remember_hash = null;
        if(!empty($_COOKIE['user'])) {
            $remember_hash = $_COOKIE['user'];
        } else {
            /**
             * @var $session Session
             */
            $session = Application::get_class(Session::class);
            $remember_hash = $session->get_var('user');
        }

        /**
         * @var $mapper \User\mappers\UserMapper
         */
        $mapper = Application::get_class(UserMapper::class);
        $collection = $mapper->find_where([
            'remember_hash' => ['=', $remember_hash]
        ]);
        if($collection->count()) {
            return $collection->one();
        } else {
            return new \User\models\UserModel();
        }
    }

    /**
     * @return \User\auth\UserAuth
     * @throws InvalidArgumentException
     */
    public function get_auth() {
        return Application::get_class(UserAuth::class);
    }

    /**
     * @return \User\mappers\UserMapper
     * @throws InvalidArgumentException
     */
    public function get_mapper() {
        return Application::get_class(UserMapper::class);
    }
}
