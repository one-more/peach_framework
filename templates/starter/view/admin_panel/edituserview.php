<?php

namespace Starter\view\AdminPanel;

class EditUserView extends \TemplateView {

    private $template_name = 'edit_user.tpl.html';
    private $id;

	public function __construct($id) {
        $this->id = $id;
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'edit_user');
	}

	public function render() {
        $this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

    private function get_data() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $mapper = $user->get_mapper();
        $identity = $mapper->find_by_id($this->id);
        return [
            'user' => $identity->to_array()
        ];
    }

	public function get_template_model() {
		$template_dir = $this->getTemplateDir(0);
		return new \TemplateViewModel([
			'name' => basename(str_replace('\\', '/', get_class($this))),
			'data' => array_merge([
				'lang_vars' => $this->get_lang_vars_array()
			], $this->get_data()),
			'html' => file_get_contents($template_dir.DS.$this->template_name)
		]);
	}
}