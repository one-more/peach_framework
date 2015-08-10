<?php

class SiteRouter extends Router {
	use trait_controller, trait_starter_router;

	private $response;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/login' => [$this, 'login'],
			'/logout' => [$this, 'logout'],
			'/edit_user' => ['UserController', 'edit_user'],
			'/add_user' => ['UserController', 'add_user'],
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
		$template = Application::get_class('Starter');
		$path = $template->path.DS.'lang'.DS.CURRENT_LANG.DS.'model'.DS.'languagemodel.json';
		echo file_get_contents($path);
	}

	public function logout() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
		$user->log_out();
	}

	public function __destruct() {
        $this->show_result($this->response);
    }
}