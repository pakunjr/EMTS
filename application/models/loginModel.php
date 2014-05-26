<?php

class loginModel {

private $isAuthorized;

private $username;

private $tmp_password;
private $tmp_passwordSalt;

private $firstname;
private $middlename;
private $lastname;
private $suffix;
private $name;

public function __construct () {
    $this->isAuthorized = isset($_SESSION['user']) ? true : false;
} //__construct

public function __destruct () {
    $this->tmp_password = '';
    $this->tmp_passwordSalt = '';
} //__destruct

public function get ($data) {
    return $this->$data;
} //get

public function set ($data, $value) {
    $this->$data = $value;
} //set

}
