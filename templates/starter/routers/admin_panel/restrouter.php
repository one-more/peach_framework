<?php

namespace Starter\routers\AdminPanel;

use common\classes\Application;
use common\helpers\FileSystemHelper;
use common\routers\TemplateRouter;
use Starter\routers\traits\TraitRestRouter;

/**
 * Class RestRouter
 * @package Starter\routers\AdminPanel
 *
 * @decorate AnnotationsDecorator
 */
class RestRouter extends TemplateRouter {
    use TraitRestRouter;

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function templates() {
        /**
         * @var $starter \Starter
         */
        $starter = Application::get_class(\Starter::class);
        $path = $starter->get_path().DS.'templates'.DS.'admin_panel';
        $this->response->result = [
            'templates' => FileSystemHelper::dir_files($path)
        ];
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function lang_files() {
        /**
         * @var $starter \Starter
         */
        $starter = Application::get_class(\Starter::class);
        $path = $starter->get_lang_path();
        $this->response->result = [
            'lang' => FileSystemHelper::dir_files($path)
        ];
    }
}