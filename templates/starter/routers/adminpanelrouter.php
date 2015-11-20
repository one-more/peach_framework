<?php

namespace Starter\routers;
use common\classes\Application;
use common\classes\GetResponse;
use common\classes\Response;
use common\models\PageModel;
use common\routers\TemplateRouter;
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
class AdminPanelRouter extends TemplateRouter {
	use TraitStarterRouter;

	public function __construct() {

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

    public function navigate(PageModel $page, $params) {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $identity = $user->get_identity();
        if($identity->is_guest() && $page->name != 'login') {
            Response::redirect('/admin_panel/login');
        } else {
            parent::navigate($page, $params);
        }
    }

	public function index($page = 1) {
        $this->users($page);
    }

	public function users($page = 1) {
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

	public function edit_user($id) {
        /**
         * @var $view TemplateView
         */
		$view = Application::get_class(EditUserView::class, [$id]);
        $this->response->blocks['main'] = $view->render();
	}

	public function add_user() {
        /**
         * @var $view TemplateView
         */
		$view = Application::get_class(AddUserView::class);
        $this->response->blocks['main'] = $view->render();
	}
}
