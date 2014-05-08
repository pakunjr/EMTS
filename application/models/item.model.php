<?php

class item_model {

    private $item_id;
    private $item_serial_no;
    private $item_model_no;
    private $item_name;
    private $item_type;
    private $item_description;
    private $item_date_of_purchase;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class item
