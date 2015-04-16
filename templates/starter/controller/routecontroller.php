<?php

class RouteController extends Router {
	use trait_controller;

	private $positions = [];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];
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