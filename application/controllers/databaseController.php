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

    $query = $options['query'];
    $values = $options['values'];

    try {
        $stmt = $connection->prepare($query);
        if ( !$stmt )
            throw new PDOException('PDOException: Failed to prepared the query');
    } catch (PDOException $e) {
        $errC->logError($e->getMessage());
        $errC->logError('Failed to prepare the SQL Query ( '.$query.' )');
    }

    /**
     * Bind parameters, values of the placeholders
     * either question marks or named parameters
     */
    $placeholder = 0;
    foreach ( $values as $value ) {

        /**
         * HOW TO:
         *
         * if the value is an array and the first index[0] value
         * is either 'int' or 'string', treat the first index[0]
         * as the type instead of placeholder and vice versa
         *
         * if the value is only a variable or string, treat
         * it as string and assume placeholders as question
         * marks
         */

        $valType = gettype($value);

        if ( $valType == 'array' ) {
            $phValue = $value[1];
            if ( $value[0] == 'string' || $value[0] == 'int' ) {
                $placeholder++;
                $type = $value[0];
            } else {
                $placeholder = $value[0];
                $type = isset($value[2]) ? $value[2] : 'string';
            }
        } else if ( $valType == 'string' || $valType == 'int' ) {
            $phValue = $value;
            $type = 'string';
            $placeholder++;
        } else {
            $errC->logError('Unknown value passed into the query ( '.$value.' )');
        }

        if ( $type == 'string' ) {
            $type = PDO::PARAM_STR;
            $phValue = strval($phValue);
        } else if ( $type == 'int' ) {
            $type = PDO::PARAM_INT;
            $phValue = intval($phValue);
        }

        $bindResult = $stmt->bindValue($placeholder, $phValue, $type) ?
            '1' : '0';

        if ( $bindResult != '1' )
            $errC->logError('Failed to bind paramter / value for '.$phValue);

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
        $errC->logError($e->getMessage());
        $errC->logError('SQL query is not processed ( '.$query.' )');
    }

} //PDOStatement









public function PDOClose () {
    $this->model->data('connection')->close();
} //PDOClose








public function PDOLastInsertID () {
    return $this->model->data('connection')->lastInsertID();
} //PDOLastInsertID






}
