<?php

namespace Starter\routers;
use common\classes\Application;
use common\classes\GetResponse;
use common\classes\Router;
use Starter\views\AdminPanel\AddUserView;
use Starter\views\AdminPanel\EditUserView;
use Starter\views\AdminPanel\LeftMenuView;
use Starter\views\AdminPanel\LoginFormView;
use Starter\views\AdminPanel\NavbarView;
use Starter\views\AdminPanel\UsersTableView;
use common\views\TemplateView;

/**
 * Class AdminPanelRouter
 * @package Starter\routers
 * @decorate \common\decorators\AnnotationsDecorator
 */
class AdminPanelRouter extends Router {
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

        $this->response = new GetResponse();
		/**
		 * @var $view TemplateView
		 */
		$view = Application::get_class(LeftMenuView::class);
		$this->response->blocks['left'] = $view->render();

		$view = Application::get_class(NavbarView::class);
		$this->response->blocks['header'] = $view->render();
        $this->response->blocks['main'] = '';
	}

	public function index($page = 1) {
        /**
         * @var $view TemplateView
         */
        $view = new UsersTableView($page);
        $this->response->blocks['main'] = $view->render();
    }

	public function login() {
		$this->response->blocks['left'] = '';
        $this->response->blocks['header'] = '';
        /**
         * @var $view TemplateView
         */
        $view = Application::get_class(LoginFormView::class);
        $this->response->blocks['main'] = $view->render();
    }

	public function edit_user_page($id) {
        /**
         * @var $view TemplateView
         */
		$view = Application::get_class(EditUserView::class, [$id]);
        $this->response->blocks['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view TemplateView
         */
		$view = Application::get_class(AddUserView::class);
        $this->response->blocks['main'] = $view->render();
	}
}
