<?php

namespace Tools\views;
use common\classes\Application;
use Tools\mappers\TemplatesMapper;
use common\views\ExtensionView;

/**
 * Class TemplatesTableView
 * @package Tools\views
 */
class TemplatesTableView extends ExtensionView {

	private $template_name = 'templates_table.tpl.html';

    public function get_extension() {
        return Application::get_class(\Tools::class);
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'templates');
	}

	public function render() {
		/**
		 * @var $mapper \Tools\mappers\TemplatesMapper
		 */
        $mapper = Application::get_class(TemplatesMapper::class);
		$templates = $mapper->get_page();
		$this->assign('templates_list', $templates);
		return $this->get_template($this->template_name);
	}

	public function get_data() {
		return [];
	}

	public function get_template_name() {
		return $this->template_name;
	}
}
