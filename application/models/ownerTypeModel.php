<?php

class ownerTypeModel {

private $ownerTypeID;
private $ownerTypeLabel;
private $ownerTypeDescription;

private $list;

private $selectOptions; //used for select form elements

public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data



}
