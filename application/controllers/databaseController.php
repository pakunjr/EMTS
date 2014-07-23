<?php

class databaseController {

private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new databaseModel();
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





























/**
 * PDO prepared statements
 */

public function PDOConnect () {
    $errM = new errorModel();
    $errC = new errorController($errM);

    $host = $this->model->data('host');
    $database = $this->model->data('database');
    $username = $this->model->data('username');
    $password = $this->model->data('password');

    $connection = new PDO(
            'mysql:host='.$host.';dbname='.$database
            ,$username
            ,$password
            ,array(
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION
                ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_BOTH
                )
        );
    $this->model->data('connection', $connection);
} //PDOConnect










public function PDOStatement ($options=array()) {

    $errM = new errorModel();
    $errC = new errorController($errM);

    $this->PDOConnect();
    $connection = $this->model->data('connection');

    $query = isset($options['query']) ? $options['query'] : '';
    $values = isset($options['values']) && is_array($options['values'])
        ? $options['values']
        : array();

    try {
        $stmt = $connection->prepare($query);
        if ( !$stmt )
            throw new PDOException('PDOException: Failed to prepared the query');
    } catch (PDOException $e) {
        $errC->logError($e->getMessage().'<br /><br />SQL Query:<br />'.$query);
    }

    /**
     * Bind parameters, values of the placeholders
     * either question marks or named parameters
     */
    $placeholder = 0;
    foreach ( $values as $value ) {

        $valType = gettype($value);

        if ( $valType == 'array' ) {
            $phValue = $value[1];
            $placeholder = $value[0];
            $type = gettype($value[1]);
        } else if ( $valType == 'string' || $valType == 'integer' ) {
            $phValue = $value;
            $type = gettype($value);
            $placeholder++;
        } else
            $errC->logError('Invalid value type.<br />Type: '.$valType.'<br />Value: '.$value);

        if ( $type == 'integer' ) {
            $type = PDO::PARAM_INT;
            $phValue = intval($phValue);
        } else {
            $type = PDO::PARAM_STR;
            $phValue = strval($phValue);
        }

        $bindResult = $stmt->bindValue($placeholder, $phValue, $type) ?
            '1' : '0';

    }

    try {

        /**
         * Check the query if it is either SELECT, INSERT,
         * UPDATE, or DELETE and apply appropriate action/s
         */
        if ( strpos($query, 'SELECT') !== false ) {
            $array = array();
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ( $row = $stmt->fetch() ) {
                array_push($array, $row);
            }

            return $array;

        } else if ( strpos($query, 'INSERT') !== false
                || strpos($query, 'UPDATE') !== false
                || strpos($query, 'DELETE') !== false ) {

            return $stmt->execute();

        } else return false;

    } catch (PDOException $e) {
        $errC->logError($e->getMessage().'<br /><br />SQL Query:<br />'.$query);
    }

} //PDOStatement









public function PDOClose () {
    $this->model->data('connection')->close();
} //PDOClose








public function PDOLastInsertID () {
    return $this->model->data('connection')->lastInsertID();
} //PDOLastInsertID






}
