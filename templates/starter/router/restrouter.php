<?php

namespace Starter\router;

use Starter\view\AdminPanel\AddUserView;
use Starter\view\AdminPanel\EditUserView;
use Starter\view\AdminPanel\LeftMenuView;
use Starter\view\AdminPanel\LoginFormView;
use Starter\view\AdminPanel\NavbarView;
use Starter\view\AdminPanel\UsersTableView;

class RestRouter extends \Router {

    public function __construct() {
        $this->routes = [
            '/rest/templates/admin_panel' => [$this, 'admin_panel_index_templates'],
            '/rest/templates/admin_panel/users' => [$this, 'admin_panel_users_templates'],
            '/rest/templates/admin_panel/users/page:number' => [$this, 'admin_panel_users_templates'],
            '/rest/templates/admin_panel/login' => [$this, 'admin_panel_login'],
            '/rest/templates/admin_panel/add_user' => [$this, 'admin_panel_add_user'],
            '/rest/templates/admin_panel/edit_user/:number' => [$this, 'admin_panel_edit_user'],
        ];
    }

    public function admin_panel_index_templates() {
        echo json_encode([
            (new UsersTableView())->get_template_model()->to_array(),
            (new LeftMenuView())->get_template_model()->to_array(),
            (new NavbarView())->get_template_model()->to_array()
        ]);
    }

    public function admin_panel_users_templates($page = 1) {
        echo json_encode([
            (new UsersTableView($page))->get_template_model()->to_array()
        ]);
    }

    public function admin_panel_login() {
        echo json_encode([
            (new LoginFormView())->get_template_model()->to_array()
        ]);
    }

    public function admin_panel_add_user() {
        echo json_encode([
            (new AddUserView())->get_template_model()->to_array()
        ]);
    }

    public function admin_panel_edit_user($id) {
        echo json_encode([
            (new EditUserView($id))->get_template_model()->to_array()
        ]);
    }
}