<?php

class loginModel {

private $isAuthorized;

private $username;
private $accessLevelID;
private $accessLevel;

private $tmp_password;
private $tmp_passwordSalt;

private $firstname;
private $middlename;
private $lastname;
private $suffix;
private $email;

public function __construct () {
    $this->isAuthorized = isset($_SESSION['user']) ? true : false;
    $this->username = isset($_SESSION['user']['username'])
        ? $_SESSION['user']['username'] : null;
    $this->accessLevel = isset($_SESSION['user']['accessLevel'])
        ? $_SESSION['user']['accessLevel'] : null;
    $this->firstname = isset($_SESSION['user']['firstname'])
        ? $_SESSION['user']['firstname'] : null;
    $this->middlename = isset($_SESSION['user']['middlename'])
        ? $_SESSION['user']['middlename'] : null;
    $this->lastname = isset($_SESSION['user']['lastname'])
        ? $_SESSION['user']['lastname'] : null;
    $this->suffix = isset($_SESSION['user']['suffix'])
        ? $_SESSION['user']['suffix'] : null;
    $this->email = isset($_SESSION['user']['email'])
        ? $_SESSION['user']['email'] : null;
} //__construct

public function __destruct () {
    $this->tmp_password = null;
    $this->tmp_passwordSalt = null;
} //__destruct

public function get ($data) {
    return $this->$data;
} //get

public function set ($data, $value) {
    $this->$data = $value;
} //set

}
