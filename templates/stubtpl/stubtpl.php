<?php

class StubTpl {
    use trait_template;

    public function __construct() {
        spl_autoload_register(['StubTpl', 'load_template_class']);
        spl_autoload_register(['StubTpl', 'load_template_model']);
        spl_autoload_register(['StubTpl', 'load_template_controller']);
    }
}