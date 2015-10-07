<?php

namespace Tools\view;

/**
 * Class TemplatesTableView
 * @package Tools\view
 */
class TemplatesTableView extends \ExtensionView {

	public function get_extension() {
        return \Application::get_class('Tools');
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'templates');
	}

	public function render() {
		/**
		 * @var $mapper \Tools\mapper\TemplatesMapper
		 */
        $mapper = \Application::get_class('Tools\mapper\TemplatesMapper');
		$templates = $mapper->get_page();
		$this->assign('templates_list', $templates);
		return $this->get_template('templates_table.tpl.html');
	}
}
