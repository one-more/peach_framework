<?php

class Starter implements Template {
    use trait_template;

    public function __construct() {
        $this->register_autoload();

        $system = Application::get_class('System');
        if($system->use_db()) {
            $lang_obj   = Application::get_class('Language');
            if(empty($lang_obj->get_page('StubTpl::index'))) {
                $this->import_language_variables();
            }
        }
    }

    public function import_language_variables() {
        $language_variables = [
            ["use_db", "use db param is true", "EN"],
            ['users', 'users in system', 'EN'],
            ['login', 'login', 'EN'],
            ['password', 'password', 'EN'],
            ['credentials', 'credentials','EN'],
            ['remember_hash', 'remember hash', 'EN'],
            ["use_db", "база данных подключена", "RU"],
            ['users', 'пользователи в системе', 'RU'],
            ['login', 'логин', 'RU'],
            ['password', 'пароль', 'RU'],
            ['credentials', 'привилегии','RU'],
            ['remember_hash', 'хэш идентификации', 'RU']
        ];
        $lang_obj   = Application::get_class('Language');
        $lang_obj->set_page('StubTpl::index', $language_variables);
    }

	public function route() {
		$controller = Application::get_class('RouteController');
		$controller->route();
	}
}