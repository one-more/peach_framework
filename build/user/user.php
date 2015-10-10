<?php

/**
 * Class User
 *
 * @decorate AnnotationsDecorator
 */
class User implements Extension {
    use TraitExtension;

    const credentials_user = 'user';

    const credentials_admin = 'administrator';

    const credentials_super_admin = 'super_administrator';

    public function __construct() {
        $this->register_autoload();
    }

    /**
     * @return \User\model\UserModel
     * @throws InvalidArgumentException
     */
    public function get_identity() {
        $remember_hash = null;
        if(!empty($_COOKIE['user'])) {
            $remember_hash = $_COOKIE['user'];
        } else {
            /**
             * @var $session Session
             */
            $session = Application::get_class('Session');
            $remember_hash = $session->get_var('user');
        }

        /**
         * @var $mapper \User\Mapper\UserMapper
         */
        $mapper = Application::get_class('\User\Mapper\UserMapper');
        $collection = $mapper->find_where([
            'remember_hash' => ['=', $remember_hash]
        ]);
        if($collection->count()) {
            return $collection->one();
        } else {
            return new \User\model\UserModel();
        }
    }

    /**
     * @return \User\auth\UserAuth
     * @throws InvalidArgumentException
     */
    public function get_auth() {
        return Application::get_class('\User\auth\UserAuth');
    }

    /**
     * @return \User\Mapper\UserMapper
     * @throws InvalidArgumentException
     */
    public function get_mapper() {
        return Application::get_class('\User\Mapper\UserMapper');
    }
}
