<?php

trait trait_controller {
    public function execute($task, $params) {
        return call_user_func_array([$this, $task], $params);
    }
}