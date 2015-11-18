<?php

namespace Tools\routers;

use common\classes\Application;
use common\classes\GetResponse;
use common\classes\Router;
use Tools\views\LeftMenuView;
use Tools\views\TemplatesTableView;
use Tools\views\TopMenuView;
use common\views\ExtensionView;

class ToolsRouter extends Router {

    private $response;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index']
		];

        $this->response = new GetResponse();

        $this->response->blocks['main'] = '';
        $this->response->blocks['left'] = '';
        $this->response->blocks['top'] = '';
	}

	public function index() {
		/**
		 * @var $view ExtensionView
		 */
		$view = Application::get_class(TemplatesTableView::class);
		$this->response->blocks['main'] = $view->render();
	}

    public function __destruct() {
        $this->show_result();
    }

	private function show_result() {
		/**
		 * @var $view ExtensionView
		 */
		$view = Application::get_class(LeftMenuView::class);
		$this->response->blocks['left'] = $view->render();

		$view = Application::get_class(TopMenuView::class);
		$this->response->blocks['top'] = $view->render();

		$smarty = new \Smarty();

		$bundle_file = ROOT_PATH.DS.'static_builder'.DS.'bundle.result.json';
		$bundle_result = json_decode(file_get_contents($bundle_file), true);
		$smarty->assign('bundle_result', $bundle_result);
		$smarty->assign($this->response->blocks);

		/**
		 * @var $ext \Tools
		 */
		$ext = Application::get_class(\Tools::class);
		$smarty->setTemplateDir($ext->get_path().DS.'templates'.DS.'index');
		$smarty->setCompileDir($ext->get_path().DS.'templates_c');
		echo $smarty->getTemplate('index.tpl.html');
	}
}