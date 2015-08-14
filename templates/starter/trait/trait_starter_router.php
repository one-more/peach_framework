<?php

trait trait_starter_router {

    private $response_type = 'AjaxResponse';

	public function route() {
        /**
         * @var $user_controller UserController
         */
		$user_controller = Application::get_class('UserController');
		if(strtolower(__CLASS__) == 'adminpanelrouter' && !$user_controller->is_admin()) {
			$callback = $this->get_callback();
            $method = $callback[1];
            if($method !== 'login') {
                header('Refresh: 0; url=/admin_panel/login');
                $callback = false;
            }
		} else {
			$callback = $this->get_callback();
		}
		if($callback !== false) {
			$check = true;
			if(count($callback) == 3) {
				$check = (array_pop($callback) == 'check');
			}
			if($check) {
				$user_controller = Application::get_class('UserController');
				if(!$user_controller->is_token_valid()) {
					throw new Exception('invalid token');
				}
			}
			$class = $callback[0];
			if(is_string($class)) {
				$reflection = new ReflectionClass($class);
				$method = $reflection->getMethod($callback[1]);
				if(!$method->isStatic()) {
					$callback[0] = Application::get_class($class);
				}
			}
            $this->callback = $callback;
			call_user_func_array($callback, $this->route_params);
		}
	}

	protected function show_result(AjaxResponse $response) {
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
            if(strtolower(__CLASS__) == 'adminpanelrouter') {
                $templator->setTemplateDir($template->path.DS.'templates'.DS.'admin_panel');
            } else {
                $templator->setTemplateDir($template->path.DS.'templates'.DS.'site');
            }
			$templator->setCompileDir($template->path.DS.'templates_c');
			$templator->assign($response['blocks']);
			echo $templator->getTemplate('index'.DS.'index.tpl.html');
		} else {
			echo $response;
		}
	}
}