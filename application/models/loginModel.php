<?php

class loginModel {

private $isAuthorized;

private $accountID;
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
    $this->accountID = isset($_SESSION['user']['accountID'])
        ? $_SESSION['user']['accountID'] : null;
    $this->username = isset($_SESSION['user']['username'])
        ? $_SESSION['user']['username'] : null;
    $this->accessLevelID = isset($_SESSION['user']['accessLevelID'])
        ? $_SESSION['user']['accessLevelID'] : null;
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



public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data





public function getUserName ($format='fullname') {
    $fullname = $this->lastname.', '
        .$this->firstname.' '
        .$this->middlename.' '
        .$this->suffix;
    return $fullname;
}

}
