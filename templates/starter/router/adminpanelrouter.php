<?php

namespace Starter\router;

use User\auth\UserAuth;

class AdminPanelRouter extends \Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
			'/admin_panel/action/login' => [$this, 'action_login'],
			'/admin_panel/edit_user/:number' => [$this, 'edit_user_page', 'no check'],
			'/admin_panel/add_user' => [$this, 'add_user_page', 'no check']
		];
        $this->response = new \JsonResponse();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
        $this->response['blocks']['main'] = '';
	}

	public function index() {
        /**
         * @var $view \TemplateView
         */
        $view = \Application::get_class('Starter\view\AdminPanel\UsersTableView');
        $this->response['blocks']['main'] = $view->render();
	}

	public function login() {
        /**
         * @var $view \TemplateView
         */
        $view = \Application::get_class('Starter\view\AdminPanel\LoginFormView');
        $this->response['blocks']['main'] = $view->render();
    }

    /**
     * @throws \WrongRequestMethodException
     * @throws \InvalidArgumentException
     * @requestMethod Ajax
     */
    public function action_login() {
        /**
         * @var $ext \User
         */
		$ext = \Application::get_class('User');

        /**
         * @var $auth UserAuth
         */
        $auth = $ext->get_auth();

        /**
         * @var $template \Starter
         */
        $template = \Application::get_class('Starter');

        /**
         * @var $lang_vars \ArrayAccess
         */
        $lang_vars = new \LanguageFile('router'.DS.'siterouter.json', $template->get_lang_path());

        $login = \Request::get_var('login', 'string');
        $password = \Request::get_var('password', 'string');
        $remember = \Request::get_var('remember');
        if($auth->login($login, $password, $remember)) {
            $user = $ext->get_identity();
            if($user->is_admin()) {
                $this->response->set_attribute('status', 'success');
            } else {
                $this->response->set_attribute('errors', $lang_vars['errors']['credentials_error']);
                $this->response->set_attribute('status', 'error');
            }
        } else {
            $this->response->set_attribute('errors', $lang_vars['errors']['login_error']);
            $this->response->set_attribute('status', 'error');
        }
    }

	public function edit_user_page($id) {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\EditUserView', [$id]);
        $this->response['blocks']['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\AddUserView');
        $this->response['blocks']['main'] = $view->render();
	}

    private function add_blocks_for_logined() {
        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        $user = $ext->get_identity();
        /*
         * User can be logged in only if he is administrator
         */
        if($user->is_admin()) {
            /**
             * @var $view \TemplateView
             */
            $view = \Application::get_class('Starter\view\AdminPanel\LeftMenuView');
            $this->response['blocks']['left'] = $view->render();

            $view = \Application::get_class('Starter\view\AdminPanel\NavbarView');
            $this->response['blocks']['header'] = $view->render();
        }
    }

    public function __destruct() {
        $this->add_blocks_for_logined();
        if($this->response_type == 'AjaxResponse') {
            $this->show_result($this->response);
        } else {
            echo $this->response;
        }
    }
}
