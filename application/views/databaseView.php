<?php

class databaseView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} // __construct

public function displaySettings () {
    echo '<div>'
        ,'Below are the settings set for the database connection.<br />'
        ,'<sup>Host:</sup> ',$this->model->getData('host'),'<br />'
        ,'<sup>Username:</sup> ',$this->model->getData('username'),'<br />'
        ,'<sup>Password:</sup> ',$this->model->getData('password'),'<br />'
        ,'<sup>Database Name:</sup> ',$this->model->getData('database'),'<br />'
        ,'<sup>Port:</sup> ',$this->model->getData('port'),'<br />'
        ,'<sup>Socket:</sup> ',$this->model->getData('socket')
        ,'</div>';
} // displaySettings

}
