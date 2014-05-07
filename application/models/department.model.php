<?php

class department_model {

    private $department_id;
    private $department_name;
    private $department_name_short;
    private $department_description;

    private $department_head_id;
    private $firstname;
    private $middlename;
    private $lastname;
    private $suffix;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class department_model
