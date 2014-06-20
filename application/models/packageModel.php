<?php

class packageModel {


private $packageID;
private $packageName;
private $packageSerialNo;
private $packageModelNo;
private $packageDateOfPurchase;

private $searchList;



public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data


}
