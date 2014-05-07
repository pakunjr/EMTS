<?php

class ownership_model {

    private $ownership_id;
    private $ownership_type;
    private $owner_id;
    private $item_id;
    private $item_status;
    private $date_of_possession;
    private $date_of_release;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class ownership_model
