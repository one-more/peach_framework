<?php

class SiteRouter extends Router {
	use trait_controller, trait_starter_router;

	private $positions = [];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/login' => [$this, 'login'],
			'/logout' => [$this, 'logout'],
			'/edit_user' => ['UserController', 'edit_user'],
			'/add_user' => ['UserController', 'add_user'],
			'/language_model' => [$this, 'language_model', 'no check']
		];
	}

	public function index() {
		$this->show_result();
	}

	public function login() {
		$login = Request::get_var('login', 'string');
		$password = Request::get_var('password', 'string');
		$remember = Request::get_var('remember', 'string');
		$user_controller = Application::get_class('UserController');
		echo json_encode($user_controller->login($login, $password, (bool)$remember));
	}

	public function language_model() {
		$template = Application::get_class('Starter');
		echo file_get_contents($template->path.DS.'lang'.DS.CURRENT_LANG.DS.'client.json');
	}

	public function logout() {
		$user = Application::get_class('User');
		$user->log_out();
	}

	private function show_result() {
		if(!Request::is_ajax()) {
			$template   = Application::get_class('Starter');
			$templator = new Smarty();
			$static_path = DS.'starter';
			$static_paths = [
				'css_path' => $static_path.DS.'css',
				'images_path' => $static_path.DS.'images',
				'js_path' => $static_path.DS.'js'
			];
			$templator->assign($static_paths);
			$templator->setTemplateDir($template->path.DS.'templates'.DS.'index');
			$templator->setCompileDir($template->path.DS.'templates_c');
			$templator->assign($this->positions);
			echo $templator->getTemplate('index.tpl.html');
		} else {
			$this->positions = array_filter($this->positions, function($el) {
				return $el !== null;
			});
			echo json_encode($this->positions);
		}
	}
}