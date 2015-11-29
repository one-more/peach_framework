<?php

namespace Starter\routers\site;

use common\classes\Application;
use common\classes\Error;
use common\classes\PageTitle;
use common\classes\Request;
use common\routers\TemplateRouter;
use Starter\routers\traits\TraitRestRouter;

/**
 * Class RestRouter
 * @package Starter\routers\site
 *
 * @decorate AnnotationsDecorator
 */
class RestRouter extends TemplateRouter {
    use TraitRestRouter;

    private static $excluding_user_fields = [
        'remember_hash', 'password', 'credentials', 'deleted', 'is_admin', 'is_super_admin'
    ];

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function users() {
        $page = Request::get_var('page', 'int', 1);
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $this->response->result = $mapper->get_page($page)->to_array();
        $this->response->paging_model = $mapper->get_paging($page)->to_array();

        $this->exclude_user_fields();
    }

    /**
     * @param int $id
     *
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     */
    public function user($id) {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $this->response->result = $mapper->find_by_id($id)->to_array();

        $this->exclude_user_fields();
    }

    /**
     * @requestMethod Ajax
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    public function current_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $this->response->result = $user->get_identity()->to_array();

        $this->exclude_user_fields();
    }

    private function exclude_user_fields() {
        if(isset($this->response->result[0]) && $this->should_exclude(['id' => -1])) {
            $this->response->result = array_map([$this, 'filter_user_fields'], $this->response->result);
        } elseif($this->should_exclude($this->response->result)) {
            $this->response->result = $this->filter_user_fields($this->response->result);
        }
    }

    private function should_exclude(array $fields) {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $identity = $user->get_identity();
        return !($identity->is_super_admin() || ($identity->id == $fields['id'] && !$identity->is_guest()));
    }

    private function filter_user_fields(array $user) {
        return array_diff_key($user, array_flip(self::$excluding_user_fields));
    }

    public function meta() {
        $this->response->result = [
            'title' => new PageTitle()
        ];
    }
}