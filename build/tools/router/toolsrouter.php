<?php

namespace Tools\router;

class ToolsRouter extends \Router {

    private $response;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];

        $this->response = new \JsonResponse();

        $this->response['blocks']['main'] = '';
        $this->response['blocks']['left'] = '';
        $this->response['blocks']['top'] = '';
	}

	public function index() {
		/**
		 * @var $view \ExtensionView
		 */
		$view = \Application::get_class('Tools\view\TemplatesTableView');
		$this->response['blocks']['main'] = $view->render();
	}

    public function __destruct() {
        $this->show_result();
    }

	private function show_result() {
		if(\Request::is_ajax()) {
			echo $this->response;
		} else {
			$static_path = DS.'tools';
			$paths = [
				'css_path' => $static_path.DS.'css',
				'js_path' => $static_path.DS.'js',
				'images_path' => $static_path.DS.'images'
			];

            /**
             * @var $view \ExtensionView
             */
			$view = \Application::get_class('Tools\view\LeftMenuView');
            $this->response['blocks']['left'] = $view->render();

            $view = \Application::get_class('Tools\view\TopMenuView');
            $this->response['blocks']['top'] = $view->render();

            $smarty = new \Smarty();
			$smarty->assign($paths);
			$smarty->assign($this->response['blocks']);

            /**
             * @var $ext \Tools
             */
            $ext = \Application::get_class('Tools');
			$smarty->setTemplateDir($ext->get_path().DS.'templates'.DS.'index');
			$smarty->setCompileDir($ext->get_path().DS.'templates_c');
			echo $smarty->getTemplate('index.tpl.html');
		}
	}
}