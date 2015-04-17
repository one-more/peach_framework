<?php

class ToolsRouter extends Router {
	protected $routes;
	private $positions = [
		'main_content' => null
	];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index'],
			'/node_processes' => [$this, 'node_processes']
		];
	}

	public function index() {
		$view = Application::get_class('TemplatesTable');
		$this->positions['main_content'] = $view->render();
		$this->show_result();
	}

	public function node_processes() {
		$view = Application::get_class('NodeProcessesTable');
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
				'js_path' => $static_path.DS.'js',
				'images_path' => $static_path.DS.'images'
			];
			$smarty = new Smarty();
			$smarty->assign($paths);
			$smarty->assign($this->positions);
			$smarty->assign('uri', Request::uri());
			$smarty->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'index');
			$smarty->setCompileDir('pfmextension://tools'.DS.'templates_c');
			echo $smarty->getTemplate('index.tpl.html');
		}
	}
}