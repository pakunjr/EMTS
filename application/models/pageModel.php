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

public function get ($data) {
    return $this->$data;
} //get

public function set ($data, $value) {
    $this->$data = $value;
} //set

}
