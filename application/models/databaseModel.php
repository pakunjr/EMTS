<?php

class databaseModel {

private $connection;

private $host;
private $username;
private $password;
private $database;
private $port;
private $socket;

public function __construct () {

} // __construct

public function getData ($data) {
    return $this->$data;
} // getData

public function setData ($data, $value) {
    $this->$data = $value;
} // setData

}
