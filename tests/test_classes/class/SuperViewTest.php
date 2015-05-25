<?php

class FakeView extends SuperView {

	public function render() {}

	public function get_lang_file() {
		return ROOT_PATH.DS.'tests'.DS.'resource'.DS.'fake_router.json';
	}
}

class SuperViewTest extends PHPUnit_Framework_TestCase {
	private $view;

	public function setUp() {
		if(is_null($this->view)) {
			$this->view = new FakeView;
			$this->view->setTemplateDir(ROOT_PATH.DS.'tests'.DS.'resource');
			$this->view->setCompileDir(ROOT_PATH.DS.'tests'.DS.'resource');
		}
	}

	/**
	 * @covers SuperView::load_lang_vars
	 */
	public function test_load_lang_vars() {
		$method = new ReflectionMethod($this->view, 'load_lang_vars');
		$method->setAccessible(true);
		$method->invoke($this->view, $this->view->get_lang_file());

		$template_vars = $this->view->getTemplateVars();
		$lang_vars = json_decode(file_get_contents($this->view->get_lang_file()), true);
		foreach($lang_vars as $k=>$var) {
			$this->assertEquals($var, $template_vars[$k]);
		}
	}

	/**
	 * @covers SuperView::getTemplate
	 */
	public function test_get_template() {
		$lang_vars = json_decode(file_get_contents($this->view->get_lang_file()), true);
		$template_name = 'fake_router_template.tpl.html';
		$template_path = ROOT_PATH.DS.'tests'.DS.'resource'.DS.$template_name;
		$template_str = file_get_contents($template_path);
		foreach($lang_vars as $k=>$var) {
			$template_str = str_replace('{$'.$k.'}', $var, $template_str);
		}
		$this->assertEquals($template_str, $this->view->getTemplate($template_name));
	}
}
 