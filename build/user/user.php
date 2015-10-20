<?php

use \classes\Application;
use \User\Mappers\UserMapper;
use User\auth\UserAuth;

/**
 * Class User
 *
 * @decorate AnnotationsDecorator
 */
class User implements \interfaces\Extension {
    use \traits\TraitExtension;

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
         * @var $mapper \User\Mappers\UserMapper
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
     * @return \User\Mappers\UserMapper
     * @throws InvalidArgumentException
     */
    public function get_mapper() {
        return Application::get_class(UserMapper::class);
    }
}
