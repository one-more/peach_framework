<?php

namespace Starter\router;

class RestRouter extends \Router {

    public function __construct() {
        $this->routes = [
            '/rest/users' => [$this, 'users'],
            '/rest/templates' => [$this, 'templates']
        ];
    }

    public function users() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $mapper = $user->get_mapper();
        $collection = $mapper->get_page();
        echo json_encode([
            'users' => $collection->to_array()
        ]);
    }

    public function templates() {

    }
}