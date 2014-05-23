<?php

class loginModel {

private $isAuthorized;

private $username;

private $tmp_password;
private $tmp_passwordSalt;

public function __construct () {
    $this->isAuthorized = isset($_SESSION['user']) ? true : false;
} // __construct

public function __destruct () {
    $this->tmp_password = '';
    $this->tmp_passwordSalt = '';
} // __destruct

public function getData ($data) {
    return $this->$data;
} // getData

public function setData ($data, $value) {
    $this->$data = $value;
} // setData

}
