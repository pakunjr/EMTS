<?php

class personModel {


private $personID;
private $firstname;
private $middlename;
private $lastname;
private $suffix;
private $gender;
private $birthdate;
private $homeAddress;
private $currentAddress;
private $contactAddress;
private $emailAddress;
private $mobileNoA;
private $mobileNoB;
private $telNo;


private $searchList;

public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data



}
