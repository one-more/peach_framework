<?php

class System {
    use trait_extension;

    public function initialize() {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

        spl_autoload_register(['System', 'load_extension_class']);

        error::initialize();
        ExceptionHandler::initialize();
    }
}