<?php

class RouteController extends Router {
	protected $routes;
	private $positions = [
		''
	];

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];
	}

	public function index() {
		$this->show_result();
	}

	private function show_result() {
		if(Request::is_ajax()) {
			echo json_encode($this->positions);
		} else {
			$static_path = DS.'tools';
			$paths = [
				'css_path' => $static_path.DS.'css'
			];
			$smarty = new Smarty();
			$smarty->assign($paths);
			$smarty->setTemplateDir('pfmextension://tools/templates/index');
			$smarty->setCompileDir('pfmextension://tools/templates_c');
			echo $smarty->getTemplate('index.tpl.html');
		}
	}
}