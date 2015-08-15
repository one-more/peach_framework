<?php

class SiteRouter extends Router {
	use trait_starter_router;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/login' => [$this, 'login'],
			'/logout' => [$this, 'logout'],
			'/edit_user' => [$this, 'edit_user'],
			'/add_user' => [$this, 'add_user'],
			'/language_model' => [$this, 'language_model', 'no check']
		];
        $this->response = new JsonResponse();
	}

	public function index() {}

	public function login() {
		$login = Request::get_var('login', 'string');
		$password = Request::get_var('password', 'string');
		$remember = Request::get_var('remember', 'string');
        /**
         * @var $user_controller UserController
         */
        $user_controller = Application::get_class('UserController');
		$this->response = $user_controller->login($login, $password, (bool)$remember);
	}

	public function language_model() {
        $this->response_type = 'Json';
		$this->response = new LanguageFile('model'.DS.'languagemodel.json');
	}

	public function logout() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
		$user->log_out();
        $this->response->status = 'success';
	}

	public function add_user() {
        /**
         * @var $controller UserController
         */
        $controller = Application::get_class('UserController');
        $this->response = $controller->add_user();
    }

    public function edit_user() {
        /**
         * @var $controller UserController
         */
        $controller = Application::get_class('UserController');
        $this->response = $controller->edit_user();
    }

	public function __destruct() {
        if($this->response_type == 'AjaxResponse') {
            $this->show_result($this->response);
        } else {
            echo $this->response;
        }
    }
}