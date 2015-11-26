<?php

namespace Starter\routers\AdminPanel;

use common\classes\Application;
use common\helpers\FileSystemHelper;
use common\routers\TemplateRouter;
use Starter\routers\traits\TraitRestRouter;
use Starter\views\AdminPanel\AddUserView;
use Starter\views\AdminPanel\EditUserView;
use Starter\views\AdminPanel\LeftMenuView;
use Starter\views\AdminPanel\LoginFormView;
use Starter\views\AdminPanel\NavbarView;
use Starter\views\AdminPanel\UsersTableView;
use common\classes\TemplatesSource;

class RestRouter extends TemplateRouter {
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

    public function templates() {
        /**
         * @var $starter \Starter
         */
        $starter = Application::get_class(\Starter::class);
        $path = $starter->get_path().DS.'templates'.DS.'admin_panel';
        $this->response->result = [
            'templates'.DS.'admin_panel' => FileSystemHelper::dir_files($path)
        ];
    }

    public function lang_files($dir) {
        /**
         * @var $starter \Starter
         */
        $starter = Application::get_class(\Starter::class);
        $path = FileSystemHelper::dir_files($starter->get_lang_path().DS.$dir.DS.'admin_panel');
        $this->response->result = [
            'lang'.DS.'views'.DS.'admin_panel' => $path
        ];
    }
}