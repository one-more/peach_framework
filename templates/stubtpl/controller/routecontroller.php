<?php

class RouteController extends Router {
	use trait_controller;

	private $positions = [
		'main_content' => ''
	];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];
	}

	public function index() {
		$view = Application::get_class('UsersTableView');
		$this->positions['main_content'] = $view->render();
		$this->show_result();
	}

	private function show_result() {
		if(!Request::is_ajax()) {
			parent::__construct();
        	$template   = Application::get_class('StubTpl');
			$templator = new Smarty();
			$static_path = DS.'stubtpl';
			$static_paths = [
				'css_path' => $static_path.DS.'css'
			];
			$templator->assign($static_paths);
			$templator->setTemplateDir($template->path.DS.'templates'.DS.'index');
			$templator->setCompileDir($template->path.DS.'templates_c');
			$templator->assign($this->positions);
			echo $templator->getTemplate('index.tpl.html');
		}
	}
}