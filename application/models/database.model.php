<?php

class database_model {

    private $connection;

    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    private $socket;

    public function __construct ($o=array()) {
        $this->host = DATABASE_HOST;
        $this->username = DATABASE_USERNAME;
        $this->password = DATABASE_PASSWORD;
        $this->database = DATABASE_NAME;
        $this->port = DATABASE_PORT;
        $this->socket = DATABASE_SOCKET;

        if ( is_array($o) ) {
            foreach ( $o as $n => $v ) {
                if ( isset($this->$n) ) $this->$n = $v;
            }
        }
    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class database_model
