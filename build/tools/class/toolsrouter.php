<?php

class ToolsRouter extends Router {

    private $response;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index'],
			'/node_processes' => [$this, 'node_processes'],
			'/node_processes/enable' => ['NodeProcessesController', 'enable_process'],
			'/node_processes/disable' => ['NodeProcessesController', 'disable_process']
		];

        $this->response = new JsonResponse();
	}

	public function index() {
		/**
		 * $var $view TemplatesTableView
		 */
		$view = Application::get_class('TemplatesTableView');
		$this->response['blocks']['main'] = $view->render();
	}

	public function node_processes() {
        /**
         * @var $view NodeProcessesTableView
         */
		$view = Application::get_class('NodeProcessesTableView');
        $this->response['blocks']['main'] = $view->render();
	}

    public function __destruct() {
        $this->show_result();
    }

	private function show_result() {
		if(Request::is_ajax()) {
			echo $this->response;
		} else {
			$static_path = DS.'tools';
			$paths = [
				'css_path' => $static_path.DS.'css',
				'js_path' => $static_path.DS.'js',
				'images_path' => $static_path.DS.'images'
			];

            /**
             * @var $view View
             */
			$view = Application::get_class('LeftMenuView');
            $this->response['blocks']['left'] = $view->render();

            $view = Application::get_class('TopMenuView');
            $this->response['blocks']['top'] = $view->render();

            $smarty = new Smarty();
			$smarty->assign($paths);
			$smarty->assign($this->response['blocks']);

            /**
             * @var $ext Tools
             */
            $ext = Application::get_class('Tools');
			$smarty->setTemplateDir($ext->get_path().DS.'templates'.DS.'index');
			$smarty->setCompileDir($ext->get_path().DS.'templates_c');
			echo $smarty->getTemplate('index.tpl.html');
		}
	}
}