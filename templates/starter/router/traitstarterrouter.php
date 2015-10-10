<?php

namespace Starter\router;

use User\auth\UserAuth;
use User\model\UserModel;

trait TraitStarterRouter {

    /**
     * @var $response \JsonResponse
     */
    private $response;

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
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
        $lang_vars = new \LanguageFile('router'.DS.'router.json', $template->get_lang_path());

        $login = \Request::get_var('login', 'string');
        $password = \Request::get_var('password', 'string');
        $remember = \Request::get_var('remember');
        if($auth->login($login, $password, $remember)) {
            $user = $ext->get_identity();
            if(\Starter::$current_router === AdminPanelRouter::class) {
                if($user->is_admin()) {
                    $this->response->status = 'success';
                } else {
                    $this->response->errors = $lang_vars['errors']['credentials_error'];
                    $this->response->status = 'error';
                }
            } else {
                $this->response->status = 'success';
            }
        } else {
            $this->response->errors = $lang_vars['errors']['login_error'];
            $this->response->status = 'error';
        }
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function action_logout() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $auth = $user->get_auth();
        $auth->log_out();
        $this->response->status = 'success';
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function action_add_user() {
        $this->save_user();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function action_edit_user() {
        $this->save_user();
    }

    private function save_user() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $mapper = $user->get_mapper();
        $model = new UserModel();
        $model->id = \Request::get_var('id', 'int');
        $model->login = \Request::get_var('login', 'string');
        $model->password = \Request::get_var('password', 'string');
        $model->credentials = \Request::get_var('credentials', 'string', \User::credentials_user);
        if($mapper->save($model)) {
            $this->response->status = 'success';
        } else {
            $this->response->status = 'error';
            $this->response->errors = $mapper->get_validation_errors();
        }
    }

    /**
     * @throws \InvalidTokenException
     * @throws \InvalidArgumentException
     */
	public function route() {
		/**
		 * @var $ext \User
		 */
		$ext = \Application::get_class('User');
		$user = $ext->get_identity();
        $callback = $this->get_callback();
        $is_admin_panel_router = \Starter::$current_router === AdminPanelRouter::class;
        if(is_array($callback) && $is_admin_panel_router && !$user->is_admin()) {
            $method = $callback[1];
            if($method != 'login') {
                \Response::redirect('/admin_panel/login');
                $callback = false;
            }
		}
		if($callback !== false) {
			$check = true;
			if(count($callback) === 3) {
				$check = (array_pop($callback) === 'check');
			}
			if($check && !\Request::is_token_valid()) {
			    throw new \InvalidTokenException('invalid token');
			}

            parent::route();
		}
	}

    private function add_blocks_for_authenticated() {
        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        $user = $ext->get_identity();
        /*
         * User can be logged in only if he is administrator
         */
        if($user->is_admin()) {
            /**
             * @var $view \TemplateView
             */
            $view = \Application::get_class('Starter\view\AdminPanel\LeftMenuView');
            $this->response['blocks']['left'] = $view->render();

            $view = \Application::get_class('Starter\view\AdminPanel\NavbarView');
            $this->response['blocks']['header'] = $view->render();
        }
    }

    public function __destruct() {
        if(\Starter::$current_router === AdminPanelRouter::class) {
            $this->add_blocks_for_authenticated();
        }
        if($this->response instanceof \AjaxResponse) {
            $this->show_result($this->response);
        } else {
            echo $this->response;
        }
    }

	protected function show_result(\AjaxResponse $response) {
		if(!\Request::is_ajax()) {
            /**
             * @var $template \Template
             */
			$template = \Application::get_class('Starter');
			$smarty = new \Smarty();

            $bundle_file = ROOT_PATH.DS.'static_builder'.DS.'bundle.result.json';
            $bundle_result = json_decode(file_get_contents($bundle_file), true);
			$smarty->assign('bundle_result', $bundle_result);
            
            if(\Starter::$current_router === AdminPanelRouter::class) {
                $smarty->setTemplateDir($template->get_path().DS.'templates'.DS.'admin_panel');
            } else {
                $smarty->setTemplateDir($template->get_path().DS.'templates'.DS.'site');
            }
			$smarty->setCompileDir($template->get_path().DS.'templates_c');
			$smarty->assign($response['blocks']);
			echo $smarty->getTemplate('index'.DS.'index.tpl.html');
		} else {
			echo $response;
		}
	}
}