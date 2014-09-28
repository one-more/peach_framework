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
        if($system->use_db()) {
            $lang   = Application::get_class('Language');
            $lang_vars  = $lang->get_page('StubTpl::index');
            $templator->replace_vars($lang_vars);
        } else {
            $lang   = new STDClass();
        }
        $get_users  = function() use($user) {
            try {
                return $user->get_users();
            } catch (Exception $e) {
                return [];
            }
        };
        $get_use_db_var = function() use($lang) {
            try {
                return (is_callable([$lang,'get_var'])) ? $lang->get_var('use_db') : '';
            } catch(Exception $e) {
                return '';
            }
        };
        $params = [
            'use_db'    => $system->use_db(),
            'true'  => [
                'use_db'    => $get_use_db_var(),
                'users_trs' => [
                    'data'  => $get_users(),
                    'include'   => $template->path.DS.'templates'.DS.'users_tr.html'
                ],
                'content_class' => 'display-block'
            ],
            'false' => [
                'use_db'    => 'use db param is false',
                'users_trs' => '',
                'content_class' => 'hide'
            ]
        ];
        $templator->replace_if($params, 'use_db');
        return $templator->get_template();
    }
}