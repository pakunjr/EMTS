<?php

class person_model {

    private $person_id;
    private $firstname;
    private $middlename;
    private $lastname;
    private $suffix;
    private $gender;
    private $birthdate;
    private $home_address;
    private $current_address;
    private $contact_address;
    private $email_address;
    private $contact_no;
    private $mobile_no;
    private $tel_no;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class person_model
