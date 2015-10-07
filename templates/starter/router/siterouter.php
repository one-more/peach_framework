<?php

namespace Starter\router;
use User\auth\UserAuth;

/**
 * Class SiteRouter
 *
 * @decorate AnnotationsDecorator
 */
class SiteRouter extends \Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/action/login' => [$this, 'action_login'],
			'/action/logout' => [$this, 'logout'],
			'/action/edit_user' => [$this, 'edit_user'],
			'/action/add_user' => [$this, 'add_user']
		];
        $this->response = new \JsonResponse();
	}

	public function index() {}

    /**
     * @throws \WrongRequestMethodException
     * @throws \InvalidArgumentException
     * @requestMethod Ajax
     */
	public function action_login() {
        /**
         * @var $ext \User
         */
		$ext = \Application::get_class('User');

        /**
         * @var $auth UserAuth
         */
        $auth = $ext->get_auth();

        /**
         * @var $template \Starter
         */
        $template = \Application::get_class('Starter');

        /**
         * @var $lang_vars \ArrayAccess
         */
        $lang_vars = new \LanguageFile('router'.DS.'siterouter.json', $template->get_lang_path());

        $login = \Request::get_var('login', 'string');
        $password = \Request::get_var('password', 'string');
        $remember = \Request::get_var('remember');
        if($auth->login($login, $password, $remember)) {
            $this->response->set_attribute('status', 'success');
        } else {
            $this->response->set_attribute('errors', $lang_vars['errors']['login_error']);
            $this->response->set_attribute('status', 'error');
        }
	}

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
	public function action_logout() {
        \Application::get_class('User')->get_auth()->log_out();
        $this->response->set_attribute('status', 'success');
	}

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
	public function action_add_user() {
        $this->response = \Application::get_class('User')->add_by_ajax();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function action_edit_user() {
        $this->response = \Application::get_class('User')->edit_by_ajax();
    }

	public function __destruct() {
        if($this->response_type === 'AjaxResponse') {
            $this->show_result($this->response);
        } else {
            echo $this->response;
        }
    }
}
