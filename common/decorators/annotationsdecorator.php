<?php

namespace common\decorators;

use common\classes\Application;
use common\classes\Request;
use common\exceptions\NotExistedMethodException;
use common\exceptions\WrongRequestMethodException;
use common\exceptions\WrongRightsException;
use common\helpers\ReflectionHelper;

class AnnotationsDecorator {
    private $object;

    public function __construct($object) {
        $this->object = $object;
    }

    public function __call($method, $arguments) {
        if(is_callable([$this->object, $method])) {
            $annotations = ReflectionHelper::get_method_annotations($this->object, $method);
            if(count($annotations)) {
                $this->handle_annotations($annotations);
            }
            return call_user_func_array([$this->object, $method], $arguments);
        } else {
            $msg = get_class($this->object)." has no method {$method}";
            throw new NotExistedMethodException($msg);
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
         * @var $ext \User
         */
        $ext = Application::get_class(\User::class);
        /**
         * @var $user \User\models\UserModel
         */
        $user = $ext->get_identity();
        switch($value) {
            case 'super_admin':
                return $user->is_super_admin();
            case 'admin':
                return $user->is_admin();
            default:
                return false;
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
            default:
                return false;
        }
    }
}