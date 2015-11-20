<?php

namespace Starter\routers;

use common\routers\TemplateRouter;
use Starter\views\AdminPanel\AddUserView;
use Starter\views\AdminPanel\EditUserView;
use Starter\views\AdminPanel\LeftMenuView;
use Starter\views\AdminPanel\LoginFormView;
use Starter\views\AdminPanel\NavbarView;
use Starter\views\AdminPanel\UsersTableView;

class AdminPanelRestRouter extends TemplateRouter {
    use TraitRestRouter;

    public function index() {
        $this->response->templates = [
            (new UsersTableView())->get_template_model()->to_array(),
            (new LeftMenuView())->get_template_model()->to_array(),
            (new NavbarView())->get_template_model()->to_array()
        ];
    }

    public function users($page = 1) {
        $this->response->templates = [
            (new UsersTableView($page))->get_template_model()->to_array()
        ];
    }

    public function login() {
        $this->response->templates = [
            (new LoginFormView())->get_template_model()->to_array()
        ];
    }

    public function add_user() {
        $this->response->templates = [
            (new AddUserView())->get_template_model()->to_array()
        ];
    }

    public function edit_user($id) {
        $this->response->templates = [
            (new EditUserView($id))->get_template_model()->to_array()
        ];
    }
}