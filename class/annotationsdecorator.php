<?php

class AnnotationsDecorator {
    private $object;

    public function __construct($object) {
        $this->object = $object;
    }

    public function __call($method, $arguments) {
        $annotations = ReflectionHelper::get_method_annotations($this->object, $method);
        if(count($annotations)) {
            $this->handle_annotations($annotations);
        }
        if(is_callable([$this->object, $method])) {
            return call_user_func_array([$this->object, $method], $arguments);
        } else {
            $msg = get_class($this->object)." has no method {$method}";
            throw new InvalidArgumentException($msg);
        }
    }

    private function handle_annotations($annotations) {
        foreach($annotations as $annotation) {
            switch($annotation['name']) {
                case 'credentials':
                    if(!$this->check_credentials($annotation['value'])) {
                        $error = current(array_filter($annotations, function($el) {
                            return $el['name'] == 'credentialsError';
                        }));
                        if(!$error) {
                            $error['value'] = "you must be {$annotation['name']}";
                        }
                        throw new WrongRightsException($error['value']);
                    }
                    break;
                case 'requestMethod':
                    if(!$this->check_request_method($annotation['value'])) {
                        throw new WrongRequestMethodException('wrong request method');
                    }
                break;
            }
        }
    }

    private function check_credentials($value) {
        /**
         * @var $controller UserController
         */
        $controller = Application::get_class('UserController');
        switch($value) {
            case 'super_admin':
                return $controller->is_super_admin();
            case 'admin':
                return $controller->is_admin();
        }
    }

    private function check_request_method($value) {
        switch(strtolower($value)) {
            case 'ajax':
                return Request::is_ajax();
            case 'post':
                return Request::is_post();
            case 'get':
                return Request::is_get();
        }
    }
}