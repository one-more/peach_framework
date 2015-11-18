<?php

namespace Starter\routers;

use common\classes\AjaxResponse;
use common\classes\Error;
use common\classes\PageTitle;
use common\classes\Request;
use common\classes\Router;
use Starter\views\AdminPanel\AddUserView;
use Starter\views\AdminPanel\EditUserView;
use Starter\views\AdminPanel\LeftMenuView;
use Starter\views\AdminPanel\LoginFormView;
use Starter\views\AdminPanel\NavbarView;
use Starter\views\AdminPanel\UsersTableView;

class RestRouter extends Router {

    /**
     * @var AjaxResponse
     */
    private $response;

    public function __construct() {
        $this->routes = [
            '/rest/templates/admin_panel' => [$this, 'admin_panel_index_templates'],
            '/rest/templates/admin_panel/users' => [$this, 'admin_panel_users_templates'],
            '/rest/templates/admin_panel/users/page:number' => [$this, 'admin_panel_users_templates'],
            '/rest/templates/admin_panel/login' => [$this, 'admin_panel_login'],
            '/rest/templates/admin_panel/add_user' => [$this, 'admin_panel_add_user'],
            '/rest/templates/admin_panel/edit_user/:number' => [$this, 'admin_panel_edit_user'],
        ];

        $this->response = new AjaxResponse();
    }

    public function admin_panel_index_templates() {
        $this->response->templates = [
            (new UsersTableView())->get_template_model()->to_array(),
            (new LeftMenuView())->get_template_model()->to_array(),
            (new NavbarView())->get_template_model()->to_array()
        ];
    }

    public function admin_panel_users_templates($page = 1) {
        $this->response->templates = [
            (new UsersTableView($page))->get_template_model()->to_array()
        ];
    }

    public function admin_panel_login() {
        $this->response->templates = [
            (new LoginFormView())->get_template_model()->to_array()
        ];
    }

    public function admin_panel_add_user() {
        $this->response->templates = [
            (new AddUserView())->get_template_model()->to_array()
        ];
    }

    public function admin_panel_edit_user($id) {
        $this->response->templates = [
            (new EditUserView($id))->get_template_model()->to_array()
        ];
    }

    public function __destruct() {
        $this->response->title = (string)new PageTitle();
        echo $this->response;
    }
}