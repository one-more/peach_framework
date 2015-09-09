<?php

class RecordSet {

    public function __construct($fields) {

        foreach($fields as $name=>$value) {
            $this->{$name} = $value;
        }
    }
}