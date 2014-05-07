<?php

class office_model {

    private $office_id;
    private $office_name;
    private $office_head_id;
    private $office_description;
    private $department_id;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class office_model
