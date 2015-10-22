<?php

namespace Starter\routers;

use common\classes\Application;
use common\classes\GetResponse;
use common\classes\Request;
use common\classes\Response;
use common\interfaces\Template;

trait TraitStarterRouter {

    /**
     * @var $response GetResponse
     */
    private $response;

    /**
     * @throws \InvalidTokenException
     * @throws \InvalidArgumentException
     */
	public function route() {
		/**
		 * @var $ext \User
		 */
		$ext = Application::get_class(\User::class);
		$user = $ext->get_identity();
        $callback = $this->get_callback();
        $is_admin_panel_router = \Starter::$current_router === AdminPanelRouter::class;
        if(is_array($callback) && $is_admin_panel_router) {
            $method = $callback[1];
            if($method !== 'login' && !$user->is_admin()) {
                Response::redirect('/admin_panel/login');
                $callback = false;
            }
            if($method === 'login' && $user->is_admin()) {
                Response::redirect('/admin_panel');
                $callback = false;
            }
		}
		if($callback !== false) {
			$check = true;
			if(count($callback) === 3) {
				$check = (array_pop($callback) == 'check');
			}
			if($check && !Request::is_token_valid()) {
			    throw new \InvalidTokenException('invalid token');
			}

            parent::route();
		}
	}

    public function __destruct() {
        $this->show_result($this->response);
    }

	protected function show_result(GetResponse $response) {
        /**
         * @var $template Template
         */
        $template = Application::get_class(\Starter::class);
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
        $smarty->assign($response->blocks);
        echo $smarty->getTemplate('index'.DS.'index.tpl.html');
	}
}