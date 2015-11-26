<?php

namespace Starter\routers\site;

use common\classes\Application;
use common\classes\LanguageFile;
use common\classes\Request;
use common\interfaces\Template;
use common\routers\TemplateRouter;
use Starter\routers\traits\TraitActionRouter;

/**
 * Class SiteActionRouter
 * @package Starter\routers
 *
 * @decorate AnnotationsDecorator
 */
class ActionRouter extends TemplateRouter {
    use TraitActionRouter;

    public function login() {
        $this->action_login();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function add_user() {
        $this->save_user();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function edit_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $model = $user->get_identity();
        if(Request::get_var('id', 'int', 0) == $model->id) {
            $this->save_user();
        } else {
            /**
             * @var $template Template
             */
            $template = Application::get_class(\Starter::class);
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
            $this->response->status = 'error';
            $this->response->errors = [$lang_vars['errors']['edit_user_error']];
        }
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function delete_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $model = $user->get_identity();
        if(Request::get_var('id', 'int', 0) == $model->id) {
            $this->action_delete_user();
        } else {
            /**
             * @var $template Template
             */
            $template = Application::get_class(\Starter::class);
            /**
             * @var $lang_vars \ArrayAccess
             */
            $lang_vars = new LanguageFile('routers'.DS.'router.json', $template->get_lang_path());
            $this->response->status = 'error';
            $this->response->errors = [$lang_vars['errors']['delete_user_error']];
        }
    }
}