<?php

class employee_model {

    private $employee_id;
    private $employee_no;
    private $occupation;

    private $office_id;
    private $office_name;

    private $department_id;
    private $department_name;
    private $department_name_short;

    private $person_id;
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

} //End class employee_model
