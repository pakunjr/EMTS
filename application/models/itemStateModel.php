<?php

class itemStateModel {


private $list;


public function __construct () {

} //__construct




public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data

}
