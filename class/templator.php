<?php

class Templator {
    public $path;

    public function __construct($path) {
        $this->path = $path;
    }

    protected  function prepare($buffer,$params) {
		$tmp = '';
        $buffer = preg_replace('/\s+/', ' ', $buffer);

        foreach($params as $key=>$value)
		{
			if(is_array($value)) {
                foreach($value as $k1=>$v1) {
                    $tmp .= $v1;
                }

                $buffer = preg_replace("/:$key/", $tmp, $buffer, 1);
                $buffer = preg_replace("/&:$key/", $tmp, $buffer);
            }
            else {
                if(preg_match("/%$key(.*)$key%/m", $buffer)) {
                    if(empty($value)) {
                        $buffer = preg_replace(["/%$key/", "/$key%/"], ['', ''], $buffer);
                    }
                    else {
                        $buffer = preg_replace("/%$key(.*)$key%/m", $value, $buffer);
                    }
                }
                else {
                    $buffer = preg_replace("/:$key/", $value, $buffer, 1);
                    $buffer = preg_replace("/&:$key/", $value, $buffer);
                }
            }
		}

		return $buffer;
	}

    public function get_template($name, $params) {
        ob_start();
        include($this->path.DS.'templates'.DS."{$name}.html");
        $result = $this->prepare(ob_get_contents(), $params);
        ob_end_clean();
        return $result;
    }
}