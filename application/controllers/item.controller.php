<?php

class item_controller {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
    } //End funciton __construct

    public function createItem ($data) {
        foreach ( $data as $i => $v ) {
            $this->model->setData($i, $v);
        }
        $this->model->create();
    } //End function createItem

} //End class item_controller
