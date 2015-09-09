<?php

class SiteRouter extends Router {
	use trait_starter_router;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check'],
			'/login' => [$this, 'login'],
			'/logout' => [$this, 'logout'],
			'/edit_user' => [$this, 'edit_user'],
			'/add_user' => [$this, 'add_user'],
			'/language_model' => [$this, 'language_model', 'no check']
		];
        $this->response = new JsonResponse();
	}

	public function index() {}

    /**
     * @requestMethod Ajax
     */
	public function login() {
		$login = Request::get_var('login', 'string');
		$password = Request::get_var('password', 'string');
		$remember = Request::get_var('remember', 'string');

        /**
         * @var $ext User
         */
		$ext = Application::get_class('User');
        if($ext->get_auth()->login($login, $password, (bool)$remember)) {
            $user = $ext->get_identity_by_field('login', $login);
            $this->response->set_attribute('user', $user);
            $this->response->set_attribute('status', 'success');
        } else {
            $this->response->set_attribute('status', 'error');
        }
	}

	public function language_model() {
        $this->response_type = 'Json';
		$this->response = new LanguageFile('model'.DS.'languagemodel.json');
	}

	public function logout() {
        Application::get_class('User')->get_auth()->log_out();
        $this->response->set_attribute('status', 'success');
	}

	public function add_user() {
        $this->response = Application::get_class('User')->add_by_ajax();
    }

    public function edit_user() {
        $this->response = Application::get_class('User')->edit_by_ajax();
    }

	public function __destruct() {
        if($this->response_type === 'AjaxResponse') {
            $this->show_result($this->response);
        } else {
            echo $this->response;
        }
    }
}