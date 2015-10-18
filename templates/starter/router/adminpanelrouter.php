<?php

namespace Starter\router;
use Starter\view\AdminPanel\UsersTableView;

/**
 * Class AdminPanelRouter
 * @package Starter\router
 * @decorate AnnotationsDecorator
 */
class AdminPanelRouter extends \Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/users' => [$this, 'index', 'no check'],
			'/admin_panel/users/page:number' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
			'/admin_panel/edit_user/:number' => [$this, 'edit_user_page', 'no check'],
			'/admin_panel/add_user' => [$this, 'add_user_page', 'no check']
		];

        $this->response = new \GetResponse();
		/**
		 * @var $view \TemplateView
		 */
		$view = \Application::get_class('Starter\view\AdminPanel\LeftMenuView');
		$this->response->blocks['left'] = $view->render();

		$view = \Application::get_class('Starter\view\AdminPanel\NavbarView');
		$this->response->blocks['header'] = $view->render();
        $this->response->blocks['main'] = '';
	}

	public function index($page = 1) {
        /**
         * @var $view \TemplateView
         */
        $view = new UsersTableView($page);
        $this->response->blocks['main'] = $view->render();
    }

	public function login() {
		$this->response->blocks['left'] = '';
        $this->response->blocks['header'] = '';
        /**
         * @var $view \TemplateView
         */
        $view = \Application::get_class('Starter\view\AdminPanel\LoginFormView');
        $this->response->blocks['main'] = $view->render();
    }

	public function edit_user_page($id) {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\EditUserView', [$id]);
        $this->response->blocks['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\AddUserView');
        $this->response->blocks['main'] = $view->render();
	}
}
