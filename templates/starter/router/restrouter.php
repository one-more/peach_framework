<?php

namespace Starter\router;

use Starter\view\AdminPanel\UsersTableView;

class RestRouter extends \Router {

    public function __construct() {
        $this->routes = [
            '/rest/templates/admin_panel' => [$this, 'admin_panel_index_templates'],
            '/rest/templates/admin_panel/page:number' => [$this, 'admin_panel_index_templates']
        ];
    }

    public function admin_panel_index_templates($page = 1) {
        echo json_encode([
            (new UsersTableView($page))->get_template_model()->to_array()
        ]);
    }
}