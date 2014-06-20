<?php

class departmentModel {


private $departmentID;
private $departmentShort;
private $departmentName;
private $departmentHeadID;
private $departmentDescription;

private $searchList;


public function __construct () {

} //__construct


public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data

}
