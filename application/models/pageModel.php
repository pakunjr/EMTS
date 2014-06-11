<?php

class pageModel {

private $uri;

private $module;
private $controller;
private $action;
private $extra;

public function __construct ($uri) {
    $this->uri = $uri;
} //__construct



public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data

}
