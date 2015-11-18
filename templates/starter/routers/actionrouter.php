<?php

namespace Starter\routers;

use common\classes\AjaxResponse;
use common\classes\Application;
use common\classes\Error;
use common\classes\LanguageFile;
use common\classes\Request;
use common\classes\Router;
use common\interfaces\Template;
use User\models\UserModel;

/**
 * Class ActionRouter
 * @package Starter\routers
 * @decorate AnnotationsDecorator
 */
class ActionRouter extends Router {

    public function __construct() {
        $this->routes = [
            '/action/login' => [$this, 'login'],
            '/action/logout' => [$this, 'logout'],
            '/action/user/add' => [$this, 'site_add_user'],
            '/action/user/edit' => [$this, 'site_edit_user'],
            '/action/user/delete' => [$this, 'site_delete_user'],
            '/action/admin_panel/login' => [$this, 'admin_panel_login'],
            '/action/admin_panel/user/add' => [$this, 'admin_panel_add_user'],
            '/action/admin_panel/user/edit' => [$this, 'admin_panel_edit_user'],
            '/action/admin_panel/user/delete' => [$this, 'admin_panel_delete_user']
        ];
        $this->response = new AjaxResponse();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    public function admin_panel_login() {
        $this->login($is_admin_panel = true);
    }

    /**
     * @param bool $is_admin_panel
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    public function login($is_admin_panel = false) {
        /**
         * @var $ext \User
         */
        $ext = Application::get_class(\User::class);
        $auth = $ext->get_auth();

        /**
         * @var $template \Starter
         */
        $template = Application::get_class(\Starter::class);

        /**
         * @var $lang_vars \ArrayAccess
         */
        $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());

        $login = Request::get_var('login', 'string');
        $password = Request::get_var('password', 'string');
        $remember = Request::get_var('remember');
        if($auth->login($login, $password, $remember)) {
            if($is_admin_panel) {
                $user = $ext->get_identity();
                if($user->is_admin()) {
                    $this->response->status = 'success';
                } else {
                    $this->response->errors = $lang_vars['errors']['credentials_error'];
                    $this->response->status = 'error';
                }
            } else {
                $this->response->status = 'success';
            }
        } else {
            $this->response->errors = [$lang_vars['errors']['login_error']];
            $this->response->status = 'error';
        }
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function logout() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $auth = $user->get_auth();
        $auth->log_out();
        $this->response->status = 'success';
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function site_add_user() {
        $this->save_user();
    }

    /**
     * @requestMethod Ajax
     * @credentials super_admin
     * @throws \InvalidArgumentException
     */
    public function admin_panel_add_user() {
        $this->save_user();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function site_edit_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $model = $user->get_identity();
        if(Request::get_var('id', 'int', 0) == $model->id) {
            $this->save_user();
        } else {
            /**
             * @var $template Template
             */
            $template = Application::get_class(\Starter::class);
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
            $this->response->status = 'error';
            $this->response->errors = [$lang_vars['errors']['edit_user_error']];
        }
    }

    /**
     * @credentials super_admin
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function admin_panel_edit_user() {
        $this->save_user();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function site_delete_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $model = $user->get_identity();
        if(Request::get_var('id', 'int', 0) == $model->id) {
            $this->delete_user();
        } else {
            /**
             * @var $template Template
             */
            $template = Application::get_class(\Starter::class);
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
            $this->response->status = 'error';
            $this->response->errors = [$lang_vars['errors']['delete_user_error']];
        }
    }

    /**
     * @credentials super_admin
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function admin_panel_delete_user() {
        $this->delete_user();
    }

    private function delete_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $mapper->delete($mapper->find_by_id(Request::get_var('id', 'int', 0)));
        $this->response->status = 'success';
        /**
         * @var $template \Starter
         */
        $template = Application::get_class(\Starter::class);
        /**
         * @var $lang_vars \ArrayAccess
         */
        $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
        $this->response->message = $lang_vars['messages']['user_deleted'];
    }

    private function save_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $model = new UserModel();
        $model->id = Request::get_var('id', 'int');
        $model->login = Request::get_var('login', 'string');
        $model->password = Request::get_var('password', 'string', '');
        $model->credentials = Request::get_var('credentials', 'string', \User::credentials_user);
        if($mapper->save($model)) {
            $this->response->status = 'success';
            /**
             * @var $template \Starter
             */
            $template = Application::get_class(\Starter::class);
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
            if(Request::get_var('id', 'int')) {
                $this->response->message = $lang_vars['messages']['user_edited'];
            } else {
                $this->response->message = $lang_vars['messages']['user_added'];
            }
        } else {
            $this->response->status = 'error';
            $this->response->errors = (array)$mapper->get_validation_errors();
        }
    }

    public function __destruct() {
        echo $this->response;
    }
}