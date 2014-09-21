<?php

class IndexController {
    use trait_controller;

    public function display() {
        $template   = Application::get_class('StubTpl');
        $templator  = new Templator($template->path);
        echo $templator->get_template('index', []);
    }
}