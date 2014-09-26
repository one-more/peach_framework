<?php

class IndexController {
    use trait_controller;
    protected $css_files;

    public function __construct() {
        $template   = Application::get_class($this->template);
        $css_path   = $template->path.DS.'css';
        $this->css_files    = [
            'style.css' => $css_path.DS.'style.css'
        ];
    }

    public function display() {
        $template   = Application::get_class('StubTpl');
        $templator  = new Templator($template->path.DS.'templates'.DS.'index.html');
        $system = Application::get_class('System');
        $user   = Application::get_class('User');
        $get_users  = function() use($user) {
            try {
                return $user->get_users();
            } catch (Exception $e) {
                return [];
            }
        };
        $params = [
            'use_db'    => $system->use_db(),
            'true'  => [
                'use_db'    => 'use db param is true',
                'users_trs' => [
                    'data'  => $get_users(),
                    'include'   => $template->path.DS.'templates'.DS.'users_tr.html'
                ]
            ],
            'false' => [
                'use_db'    => 'use db param is false',
                'users_trs' => ''
            ]
        ];
        $templator->replace_if($params, 'use_db');
        $lang   = Application::get_class('Language');
        return $templator->get_template();
    }
}