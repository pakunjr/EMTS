<?php

class databaseView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



public function displaySettings () {
    echo '<div>'
        ,'Below are the settings set for the database connection.<br />'
        ,'<sup>Host:</sup> ',$this->model->data('host'),'<br />'
        ,'<sup>Username:</sup> ',$this->model->data('username'),'<br />'
        ,'<sup>Password:</sup> ',$this->model->data('password'),'<br />'
        ,'<sup>Database Name:</sup> ',$this->model->data('database'),'<br />'
        ,'<sup>Port:</sup> ',$this->model->data('port'),'<br />'
        ,'<sup>Socket:</sup> ',$this->model->data('socket')
        ,'</div>';
} //displaySettings

}
