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
			'/login' => [$this, 'login'],
			'/logout' => [$this, 'logout'],
			'/edit_user' => [$this, 'edit_user'],
			'/add_user' => [$this, 'add_user'],
			'/language_model' => [$this, 'language_model', 'no check']
		];
        $this->response = new \JsonResponse();
	}

	public function index() {}

    /**
     * @throws \WrongRequestMethodException
     * @throws \InvalidArgumentException
     */
	public function login() {
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
        if($auth->login_by_ajax()) {
            $user = $ext->get_identity();
            if($user->is_admin()) {
                $this->response->set_attribute('status', 'success');
            } else {
                $this->response->set_attribute('errors', $lang_vars['errors']['credentials_error']);
                $this->response->set_attribute('status', 'error');
            }
        } else {
            $this->response->set_attribute('errors', $lang_vars['errors']['login_error']);
            $this->response->set_attribute('status', 'error');
        }
	}

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
	public function logout() {
        \Application::get_class('User')->get_auth()->log_out();
        $this->response->set_attribute('status', 'success');
	}

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
	public function add_user() {
        $this->response = \Application::get_class('User')->add_by_ajax();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function edit_user() {
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
