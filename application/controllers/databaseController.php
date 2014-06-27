<?php

class databaseController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct




public function connect () {
    $connection = new mysqli(
            $this->model->data('host')
            ,$this->model->data('username')
            ,$this->model->data('password')
            ,$this->model->data('database')
        );

    if ( $connection->connect_errno ) {
        echo '<div style="color: #f00;">'
            ,'Error: Cannot establish connection to the database.<br />'
            ,'Please do contact our system administrator.<br />'
            ,'Thank you.<br />'
            ,$connection->connect_error
            ,'</div>';
    } else {
        $this->model->data('connection', $connection);
    }
} //connect




public function disconnect () {
    $this->model->data('connection')->close();
} //disconnect




public function query ($sqlQuery) {
    $this->connect();
    $connection = $this->model->data('connection');
    $sql = $connection->query($sqlQuery);
    return $sql;

    /**
     * Prepare statement
     * pending
     */
    $stmt = $connection->prepare($sqlQuery);
} //query




public function escapeString ($stringValue) {
    $this->connect();
    $connection = $this->model->data('connection');
    $escapedString = $connection->real_escape_string($stringValue);
    return trim($escapedString);
} //escapeString

public function escapeArray ($array) {
    return array_map(array($this, 'escapeString'), $array);
} //escapeArray




public function insertedID ($echo=false) {
    $connection = $this->model->data('connection');
    if ( !$echo ) return $connection->insert_id;
    echo $connection->insert_id;
} //insertedID




}
