<?php

class databaseView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct

public function displaySettings () {
    echo '<div>'
        ,'Below are the settings set for the database connection.<br />'
        ,'<sup>Host:</sup> ',$this->model->get('host'),'<br />'
        ,'<sup>Username:</sup> ',$this->model->get('username'),'<br />'
        ,'<sup>Password:</sup> ',$this->model->get('password'),'<br />'
        ,'<sup>Database Name:</sup> ',$this->model->get('database'),'<br />'
        ,'<sup>Port:</sup> ',$this->model->get('port'),'<br />'
        ,'<sup>Socket:</sup> ',$this->model->get('socket')
        ,'</div>';
} //displaySettings

}
