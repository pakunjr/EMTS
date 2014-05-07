<?php

class database_controller {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
        $this->connect();
    } //End function __construct

    /**
     * Print the settings of the object created.
     */
    public function printSettings ($print=false) {
        $o = '<div style="display: block; font-family: Helvetica, Arial, sans-serif;"><b style="display: block; padding: 3px 0px; text-decoration: underline;">Below are the settings of the database connection on an object.</b><br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">HOST:</span>'
            . $this->model->getData('host'). '<br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">USERNAME:</span>'
            . $this->model->getData('username'). '<br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">PASSWORD:</span>'
            . $this->model->getData('password'). '<br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">DATABASE:</span>'
            . $this->model->getData('database'). '<br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">PORT:</span>'
            . $this->model->getData('port'). '<br />'
            . '<span style="display: inline-block; width: 100px; font-size: 9pt;">SOCKET:</span>'
            . $this->model->getData('socket'). '</div>';

        if ( $print ) echo $o;
        else return $o;
    } //End function printSettings

    /**
     * Connect to the database.
     */
    public function connect () {
        $this->model->setData('connection', new mysqli(
                $this->model->getData('host')
                , $this->model->getData('username')
                , $this->model->getData('password')
                , $this->model->getData('database')
            ));

        if ( $this->model->getData('connection')->connect_errno ) {
            echo '<div>Error: Cannot connect to the database.<br />'
                , $this->model->getData('connection')->connect_error
                , '</div>';
        }
    } //End function connect

    /**
     * Process the SQL query
     */
    public function query ($query) {
        $sql = $this->model->getData('connection')->query($query);
        return $sql;
    } //End function query

    /**
     * Close the database connection
     */
    public function close () {
        $this->model->getData('connection')->close();
    } //End function close

} //End class database_controller
