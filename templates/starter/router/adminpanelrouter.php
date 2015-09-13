<?php

namespace Starter\router;

use User\identity\UserIdentity;

class AdminPanelRouter extends \Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
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
         * @var $user UserIdentity
         */
        $user = \Application::get_class('User')->get_current();
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