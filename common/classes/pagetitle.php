<?php

namespace common\classes;

class PageTitle {

    /**
     * @var [] titles
     */
    private $titles = [];

    /**
     * @var string current
     */
    private $current = '';

    public function __construct() {
        $this->load_data();
        $this->parse_uri();
    }

    private function load_data() {
        $file = ROOT_PATH.DS.'resource'.DS.CURRENT_LANG.'_titles.json';
        $this->titles = json_decode(file_get_contents($file), true);
    }

    private function parse_uri() {
        $uri = str_replace('/rest/templates', '', Request::uri());
        $uri === '' && $uri = '/';
        foreach($this->titles as $pattern=>$title) {
            if(preg_match($pattern, $uri, $matches)) {
                if(is_string($title)) {
                    $this->current = $title;
                } elseif(is_array($title)) {
                    list($class, $method, $prefix) = array_pad($title, 3, '');
                    try {
                        $reflection_method = new \ReflectionMethod($class, $method);
                        $params = array_slice($matches, 1);
                        $params[] = $prefix;
                        if($reflection_method->isStatic()) {
                            $this->current = call_user_func_array([$class, $method], $params);
                        } else {
                            $this->current = call_user_func_array([
                                Application::get_class($class),
                                $method
                            ], $params);
                        }
                    } catch(\Exception $e) {
                        \Error::log($e->getMessage());
                    }
                }
            }
        }
    }

    public static function attic_stair($id, $prefix) {
        /**
         * @var $articles \Articles
         */
        $articles = Application::get_class(\Articles::class);
        $mapper = $articles->get_mapper();
        $stair = $mapper->find_by_id($id);
        return $prefix.' '.$stair->name;
    }

    public function __toString() {
        return (string)$this->current;
    }
}