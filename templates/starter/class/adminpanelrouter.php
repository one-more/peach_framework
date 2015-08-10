<?php

class AdminPanelRouter extends Router {
	use trait_starter_router;

    private $response;

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
			'/admin_panel/edit_user/:number' => [$this, 'edit_user_page', 'no check'],
			'/admin_panel/add_user' => [$this, 'add_user_page', 'no check']
		];
        $this->response = new AjaxResponse();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
        $this->response['blocks']['main'] = '';
	}

	public function index() {
        /**
         * @var $view \AdminPanel\UsersTableView
         */
        $view = Application::get_class('\AdminPanel\UsersTableView');
        $this->response['blocks']['main'] = $view->render();
	}

	public function login() {
        /**
         * @var $view \AdminPanel\LoginFormView
         */
        $view = Application::get_class('\AdminPanel\LoginFormView');
        $this->response['blocks']['main'] = $view->render();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
    }

	public function edit_user_page($id) {
        /**
         * @var $view \AdminPanel\EditUserView
         */
		$view = Application::get_class('\AdminPanel\EditUserView', [$id]);
        $this->response['blocks']['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view \AdminPanel\AddUserView
         */
		$view = Application::get_class('\AdminPanel\AddUserView');
        $this->response['blocks']['main'] = $view->render();
	}

    public function __destruct() {
        $this->show_result();
    }

	private function show_result() {
        $callback_method = $this->callback[1];
        if($callback_method != 'login') {
            /**
             * @var $view \AdminPanel\LeftMenuView
             */
            $view = Application::get_class('\AdminPanel\LeftMenuView');
            $this->response['blocks']['left'] = $view->render();
            /**
             * @var $view \AdminPanel\NavbarView
             */
            $view = Application::get_class('\AdminPanel\NavbarView');
            $this->response['blocks']['header'] = $view->render();
        }
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
			$templator->assign($this->response['blocks']);
			echo $templator->getTemplate('index.tpl.html');
		} else {
			echo $this->response;
		}
	}
}