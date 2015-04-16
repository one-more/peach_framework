<?php

class ToolsRouter extends Router {
	protected $routes;
	private $positions = [
		'main_content' => null
	];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];
	}

	public function index() {
		$view = Application::get_class('TemplatesTableView');
		$this->positions['main_content'] = $view->render();
		$this->show_result();
	}

	private function show_result() {
		if(Request::is_ajax()) {
			$this->positions = array_filter($this->positions, function($el) {
				return $el !== null;
			});
			echo json_encode($this->positions);
		} else {
			$static_path = DS.'tools';
			$paths = [
				'css_path' => $static_path.DS.'css',
				'js_path' => $static_path.DS.'js'
			];
			$smarty = new Smarty();
			$smarty->assign($paths);
			$smarty->assign($this->positions);
			$smarty->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'index');
			$smarty->setCompileDir('pfmextension://tools'.DS.'templates_c');
			echo $smarty->getTemplate('index.tpl.html');
		}
	}
}