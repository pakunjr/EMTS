<?php

class item_controller {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
    } //End funciton __construct

    public function createItem ($data) {
        $this->model->setData('item_serial_no', $data['serial-no']);
        $this->model->setData('item_model_no', $data['model-no']);
        $this->model->setData('item_name', $data['item-name']);
        $this->model->setData('item_type', $data['item-type']);
        $this->model->setData('item_description', $data['item-description']);
        $this->model->setData('item_date_of_purchase', $data['date-of-purchase']);
        return $this->model->create();
    } //End function createItem

} //End class item_controller
