<?php

class AdminPanelRouter extends Router {
	use trait_starter_router;

    private $response;
    private $callback = [];

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
			'/admin_panel/edit_user/:number' => [$this, 'edit_user_page', 'no check'],
			'/admin_panel/add_user' => [$this, 'add_user_page', 'no check']
		];
        $this->response = new JsonResponse();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
        $this->response['blocks']['main'] = '';
	}

	public function index() {
        /**
         * @var $view \AdminPanel\UsersTableView
         */
        $view = Application::get_class('\AdminPanel\UsersTableView');
        $this->response['blocks']['main'] = $view->render();
	}

	public function login() {
        /**
         * @var $view \AdminPanel\LoginFormView
         */
        $view = Application::get_class('\AdminPanel\LoginFormView');
        $this->response['blocks']['main'] = $view->render();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
    }

	public function edit_user_page($id) {
        /**
         * @var $view \AdminPanel\EditUserView
         */
		$view = Application::get_class('\AdminPanel\EditUserView', [$id]);
        $this->response['blocks']['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view \AdminPanel\AddUserView
         */
		$view = Application::get_class('\AdminPanel\AddUserView');
        $this->response['blocks']['main'] = $view->render();
	}

    public function __destruct() {
		$callback_method = $this->callback[1];
		if($callback_method != 'login') {
			/**
			 * @var $view \AdminPanel\LeftMenuView
			 */
			$view = Application::get_class('\AdminPanel\LeftMenuView');
			$response['blocks']['left'] = $view->render();
			/**
			 * @var $view \AdminPanel\NavbarView
			 */
			$view = Application::get_class('\AdminPanel\NavbarView');
			$response['blocks']['header'] = $view->render();
		}
		$this->show_result($this->response);
    }
}