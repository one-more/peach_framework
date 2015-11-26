<?php

namespace Starter\routers\AdminPanel;

use common\routers\TemplateRouter;
use Starter\routers\traits\TraitActionRouter;

/**
 * Class AdminPanelActionRouter
 * @package Starter\routers
 *
 * @decorate AnnotationsDecorator
 */
class ActionRouter extends TemplateRouter {
    use TraitActionRouter;

    public function login() {
        $this->action_login($is_admin_panel = true);
    }

    /**
     * @requestMethod Ajax
     * @credentials super_admin
     * @throws \InvalidArgumentException
     */
    public function add_user() {
        $this->save_user();
    }

    /**
     * @credentials super_admin
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function edit_user() {
        $this->save_user();
    }

    /**
     * @credentials super_admin
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function delete_user() {
        $this->action_delete_user();
    }
}