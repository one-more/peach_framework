<?php

namespace Starter\routers;

use common\classes\Application;
use common\classes\GetResponse;
use common\classes\PageTitle;
use common\classes\Request;
use common\classes\Response;
use common\interfaces\Template;

trait TraitStarterRouter {

    /**
     * @var $response GetResponse
     */
    private $response;

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

        if(strpos(Request::uri(), 'admin_panel') !== false) {
            $smarty->setTemplateDir($template->get_path().DS.'templates'.DS.'admin_panel');
        } else {
            $smarty->setTemplateDir($template->get_path().DS.'templates'.DS.'site');
        }
        $smarty->setCompileDir($template->get_path().DS.'templates_c');
        $smarty->assign($response->blocks);
        $smarty->assign('title', new PageTitle());
        echo $smarty->getTemplate('index'.DS.'index.tpl.html');
	}
}