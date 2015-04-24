<?php

class AdminPanelRouter extends Router {
	use trait_starter_router;

	private $positions = [
		'left_menu' => null,
		'main_content' => null
	];

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check']
		];
	}

	public function index() {
		$user = Application::get_class('User');
		if($user->is_logined()) {

		} else {
			$view = Application::get_class('AdminPanelLogin');
			$this->positions['main_content'] = $view->render();
		}
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
			$templator->setTemplateDir($template->path.DS.'templates'.DS.'admin_panel');
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