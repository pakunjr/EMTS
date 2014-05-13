<?php

class department_model {

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class department_model
