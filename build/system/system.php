<?php

class System {
    use trait_extension;

    public function initialize() {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

        ini_set('display_errors', 'on');

        spl_autoload_register(['System', 'load_extension_class']);
        spl_autoload_register(['System', 'load_model']);
        spl_autoload_register(['System', 'load_controller']);

        error::initialize();
        ExceptionHandler::initialize();

        $this->init_db();
        $this->update_db_from_dump();

        $session    = Application::get_class('Session');
        $session->start();
    }

    public function get_configuration() {
        return $this->get_params('configuration');
    }

    public function get_template() {
        return $this->get_configuration()['template'];
    }

    public function use_db() {
        return $this->get_configuration()['use_db'];
    }

    public function dump_db() {
        if($this->use_db()) {
            if($this->get_configuration()['dump_db']) {
                if($this->is_db_dump_actual()) {
                    $model  = $this->get_model('SystemModel');
                    $dump_hash  = $model->dump_db();
                    $this->set_params('system', ['db_dump_hash' => $dump_hash]);
                }
            }
        }
    }

    protected function init_db() {
        $params = $this->get_configuration();
        if($this->use_db()) {
            if(empty($this->get_params('system')['db_initialized'])) {
                $db_params  = $params['db_params'];
                $model  = Application::get_class('SystemInitModel', $db_params);
                $model->initialize();
                $this->set_params('system', ['db_initialized'=>true]);
            }
        }
    }

    protected function update_db_from_dump() {
        if($this->use_db()) {
            if($this->get_configuration()['dump_db']) {
                if(!$this->is_db_dump_actual()) {
                    $params = $this->get_params('system');
                    $auto_update    = !empty($params['auto_update_db_dump']) ? $params['auto_update_db_dump'] : false;
                    if($auto_update) {
                        $model  = $this->get_model('SystemModel');
                        $dump_hash  = $model->update_db_from_dump();
                        $this->set_params('system', ['db_dump_hash' => $dump_hash]);
                    } else {
                        if(empty($_SERVER['QUERY_STRING'])) {
                            $templator    = new Templator($this->path.DS.'templates'.DS.'message.html');
                            $params = [
                                'message'   => 'your database is outdated. please check database dump file',
                                'class' => $this->get_configuration()['info_block_class']
                            ];
                            $templator->replace_vars($params);
                            echo $templator->get_template();
                        }
                    }
                }
            }
        }
    }

    protected function is_db_dump_actual() {
        $params = $this->get_params('system');
        $params_hash    = !empty($params['db_dump_hash']) ? $params['db_dump_hash'] : '';
        $file_hash  = md5(file_get_contents(ROOT_PATH.DS.'resource'.DS.'dump_db.sql'));
        return $params_hash == $file_hash;
    }
}