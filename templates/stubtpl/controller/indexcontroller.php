<?php

class IndexController extends Smarty {
    use trait_controller;
    protected $css_files;

    public function __construct() {
         parent::__construct();
        $template   = Application::get_class('StubTpl');
        $css_path   = $template->path.DS.'css';
        $this->css_files    = [
            'style.css' => $css_path.DS.'style.css'
        ];
        $this->setTemplateDir($template->path.DS.'templates');
        $compile_dir = $template->path.DS.'templates_c';
        $this->setCompileDir($compile_dir);
        if(!is_dir($compile_dir)) {
            mkdir($compile_dir);
            chmod($compile_dir, 0777);
        }
    }

    public function _display() {
        $system = Application::get_class('System');
        $user   = Application::get_class('User');
        $this->assign('use_db_param', $system->use_db());
        if($system->use_db()) {
            $lang   = Application::get_class('Language');
            $lang_vars  = $lang->get_page('StubTpl::index');
            $this->assign($lang_vars);
            $this->assign('users_array', $user->get_users());
        }
        $this->display('index.tpl.html');
    }
}