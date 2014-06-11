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
    $this->host = DATABASE_HOST;
    $this->username = DATABASE_USERNAME;
    $this->password = DATABASE_PASSWORD;
    $this->database = DATABASE_NAME;
    $this->port = DATABASE_PORT;
    $this->socket = DATABASE_SOCKET;
} //__construct




public function data ($name, $value=null) {
    if ( $value == null ) return $this->$name;
    $this->$name = $value;
} //data

}
