<?php

namespace Starter\router;

/**
 * Class SiteRouter
 * @package Starter\router
 * @decorate AnnotationsDecorator
 */
class SiteRouter extends \Router {
	use TraitStarterRouter {
        TraitStarterRouter::action_edit_user as trait_action_edit_user;
    }

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/action/login' => [$this, 'action_login'],
			'/action/logout' => [$this, 'action_logout'],
			'/action/edit_user' => [$this, 'action_edit_user'],
			'/action/add_user' => [$this, 'action_add_user']
		];
        $this->response = new \JsonResponse();
	}

	public function index() {}

    public function action_edit_user() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $model = $user->get_identity();
        if(\Request::get_var('id', 'int', 0) == $model->id) {
            $this->trait_action_edit_user();
        } else {
            /**
             * @var $template \Template
             */
            $template = \Application::get_class('Starter');
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new \LanguageFile('router'.DS.'router.json', $template->get_lang_path());
            $this->response->status = 'error';
            $this->response->errors = [$lang_vars['errors']['edit_user_error']];
        }
    }
}
