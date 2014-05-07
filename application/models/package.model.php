<?php

class package_model {

    private $package_id;
    private $package_name;
    private $package_serial_no;
    private $package_description;
    private $package_items;
    private $date_of_purchase;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class package_model
