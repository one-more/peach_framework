<?php

namespace Starter\router;

trait TraitStarterRouter {

    private $response_type = 'AjaxResponse';
    private $response;

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
        if(is_array($callback) && $this instanceof AdminPanelRouter && !$user->is_admin()) {
            $method = $callback[1];
            if($method != 'login') {
                \Response::redirect('/admin_panel/login');
                $callback = false;
            }
		}
		if($callback !== false) {
			$check = true;
			if(count($callback) == 3) {
				$check = (array_pop($callback) === 'check');
			}
			if($check && !\Request::is_token_valid()) {
			    throw new \InvalidTokenException('invalid token');
			}

            parent::route();
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
            
            if($this instanceof AdminPanelRouter) {
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