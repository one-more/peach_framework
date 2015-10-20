<?php

use Tools\routers\ToolsRouter;

class Tools implements \common\interfaces\Extension {
	use \common\traits\TraitExtension;

	public function __construct() {
		$this->register_autoload();

        $this->initialize();
	}

	public function route() {
        /**
         * @var $router \Tools\routers\ToolsRouter
         */
		$router = \common\classes\Application::get_class(ToolsRouter::class);
        $router->route();
	}

	private function initialize() {
        if(empty($this->get_params()['initialized'])) {
            $file = $this->get_path().DS.'resource'.DS.'initialize.sql';
            $adapter = new \common\adapters\MysqlAdapter('');
            $adapter->execute(file_get_contents($file));
            $this->set_params([
                'initialized' => 1
            ]);
        }
    }
}