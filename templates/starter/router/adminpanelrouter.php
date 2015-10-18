<?php

namespace Starter\router;
use Starter\view\AdminPanel\UsersTableView;

/**
 * Class AdminPanelRouter
 * @package Starter\router
 * @decorate AnnotationsDecorator
 */
class AdminPanelRouter extends \Router {
	use TraitStarterRouter {
        TraitStarterRouter::action_add_user as trait_add_user;
        TraitStarterRouter::action_edit_user as trait_edit_user;
    }

	public function __construct() {
		$this->routes = [
			'/admin_panel' => [$this, 'index', 'no check'],
			'/admin_panel/page:number' => [$this, 'index', 'no check'],
			'/admin_panel/login' => [$this, 'login', 'no check'],
			'/admin_panel/edit_user/:number' => [$this, 'edit_user_page', 'no check'],
			'/admin_panel/add_user' => [$this, 'add_user_page', 'no check'],
            '/admin_panel/action/login' => [$this, 'action_login'],
            '/admin_panel/action/edit_user/:number' => [$this, 'action_edit_user'],
			'/admin_panel/action/add_user' => [$this, 'action_add_user']
		];

        $this->response = new \JsonResponse();
        $this->response['blocks']['header'] = '';
        $this->response['blocks']['left'] = '';
        $this->response['blocks']['main'] = '';
	}

	public function index($page = 1) {
        /**
         * @var $view \TemplateView
         */
        $view = new UsersTableView($page);
        $this->response['blocks']['main'] = $view->render();
	}

	public function login() {
        /**
         * @var $view \TemplateView
         */
        $view = \Application::get_class('Starter\view\AdminPanel\LoginFormView');
        $this->response['blocks']['main'] = $view->render();
    }

	public function edit_user_page($id) {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\EditUserView', [$id]);
        $this->response['blocks']['main'] = $view->render();
	}

	public function add_user_page() {
        /**
         * @var $view \TemplateView
         */
		$view = \Application::get_class('Starter\view\AdminPanel\AddUserView');
        $this->response['blocks']['main'] = $view->render();
	}

    /**
     * @credentials super_admin
     * @throws \InvalidArgumentException
     */
    public function action_add_user() {
        $this->trait_add_user();
    }

    /**
     * @credentials super_admin
     * @throws \InvalidArgumentException
     */
    public function action_edit_user() {
        $this->trait_edit_user();
    }
}
