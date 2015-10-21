<?php

use Tools\routers\ToolsRouter;

class Tools implements \common\interfaces\Extension {
	use \common\traits\TraitExtension;

	public function __construct() {
		$this->register_autoload();
	}

	public function route() {
        /**
         * @var $router \Tools\routers\ToolsRouter
         */
		$router = \common\classes\Application::get_class(ToolsRouter::class);
        $router->route();
	}

    /**
     * @return \Tools\mappers\TemplatesMapper
     */
    public function get_templates_mapper() {
        return new \Tools\mappers\TemplatesMapper();
    }
}