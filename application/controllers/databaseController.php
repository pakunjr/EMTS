<?php

class databaseController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct

public function connect () {
    $connection = new mysqli(
            $this->model->get('host')
            ,$this->model->get('username')
            ,$this->model->get('password')
            ,$this->model->get('database')
        );

    if ( $connection->connect_errno ) {
        echo '<div style="color: #f00;">'
            ,'Error: Cannot establish connection to the database.<br />'
            ,'Please do contact our system administrator.<br />'
            ,'Thank you.<br />'
            ,$connection->connect_error
            ,'</div>';
    } else {
        $this->model->set('connection', $connection);
    }
} //connect

public function disconnect () {
    $this->model->get('connection')->close();
} //disconnect

public function query ($sqlQuery) {
    $this->connect();
    $connection = $this->model->get('connection');
    $sql = $connection->query($sqlQuery);
    return $sql;
} //query

public function escapeString ($stringValue) {
    $connection = $this->model->get('connection');
    return $connection->real_escape_string($stringValue);
} //escapeString

public function getLastID () {
    $connection = $this->model->get('connection');
    return $connection->insert_id;
} //getLastID

}
