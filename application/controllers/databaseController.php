<?php

class databaseController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} // __construct

public function connect () {
    $connection = new mysqli(
            $this->model->getData('host')
            ,$this->model->getData('username')
            ,$this->model->getData('password')
            ,$this->model->getData('database')
        );

    if ( $connection->connect_errno ) {
        echo '<div style="color: #f00;">'
            ,'Error: Cannot establish connection to the database.<br />'
            ,'Please do contact our system administrator.<br />'
            ,'Thank you.<br />'
            ,$connection->connect_error
            ,'</div>';
    } else {
        $this->model->setData('connection', $connection);
    }
} // connect

public function disconnect () {
    $this->model->getData('connection')->close();
} // disconnect

public function query ($sqlQuery) {
    $connection = $this->model->getData('connection');
    $sql = $connection->query($sqlQuery);
    return $sql;
} // query

public function escapeString ($stringValue) {
    $connection = $this->model->getData('connection');
    return $connection->real_escape_string($stringValue);
} // escapeString

public function getLastID () {
    $connection = $this->model->getData('connection');
    return $connection->insert_id;
} // getLastID

}
