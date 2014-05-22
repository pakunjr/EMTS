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

public function getData ($data) {
    return $this->$data;
} //getData

public function setData ($data, $value) {
    $this->$data = $value;
} //setData

}
