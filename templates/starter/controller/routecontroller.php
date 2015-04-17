<?php

class RouteController extends Router {
	use trait_controller;

	private $positions = [];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check']
		];
	}

	public function route() {
		$callback = $this->get_callback();
		if($callback !== false) {
			$check = true;
			if(count($callback) == 3) {
				$check = (array_pop($callback) == 'check');
			}
			if($check) {
				$user_controller = Application::get_class('UserController');
				if(!$user_controller->is_token_valid()) {
					throw new Exception('invalid token');
				}
			}
			call_user_func_array($callback, $this->route_params);
		}
	}

	public function index() {
		$this->show_result();
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