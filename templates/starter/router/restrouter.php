<?php

namespace Starter\router;

use Starter\view\AdminPanel\UsersTableView;

class RestRouter extends \Router {

    public function __construct() {
        $this->routes = [
            '/rest/templates/admin_panel' => [$this, 'admin_panel_index_templates']
        ];
    }

    public function admin_panel_index_templates() {
        echo json_encode([
            (new UsersTableView())->get_template_model()->to_array()
        ]);
    }
}