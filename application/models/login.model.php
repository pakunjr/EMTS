<?php

class login_model {

    private $login_status; // true or false

    private $login_attempt_count; // not-working
    private $login_attempt_status; // not-working

    private $account_id;
    private $username;
    private $password;

    private $employee_id;
    private $occupation;
    private $designated_office_id;
    private $designated_department_id;

    private $person_id;
    private $firstname;
    private $middlename;
    private $lastname;
    private $suffix;

    public function __construct () {
        $this->login_status = !isset($_SESSION['user']) ? false : true;

        $this->person_id = isset($_SESSION['user']['person_id'])
            ? $_SESSION['user']['person_id'] : '';
        $this->firstname = isset($_SESSION['user']['firstname'])
            ? $_SESSION['user']['firstname'] : '';
        $this->middlename = isset($_SESSION['user']['middlename'])
            ? $_SESSION['user']['middlename'] : '';
        $this->lastname = isset($_SESSION['user']['lastname'])
            ? $_SESSION['user']['lastname'] : '';
        $this->suffix = isset($_SESSION['user']['suffix'])
            ? $_SESSION['user']['suffix'] : '';

        $this->employee_id = isset($_SESSION['user']['employee_id'])
            ? $_SESSION['user']['employee_id'] : '';
        $this->occupation = isset($_SESSION['user']['occupation'])
            ? $_SESSION['user']['occupation'] : '';
        $this->designated_office_id = isset($_SESSION['user']['designated_office_id'])
            ? $_SESSION['user']['designated_office_id'] : '';
        $this->designated_department_id = isset($_SESSION['user']['designated_department_id'])
            ? $_SESSION['user']['designated_department_id'] : '';
    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

    public function encryptPassword ($password_raw, $password_salt) {
        return hash('sha256', $password_raw. $password_salt);
    } //End function encryptPassword

} //End class login_model
