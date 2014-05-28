<?php

class itemModel {

private $item_id;



public function __construct () {

} //__construct




public function get ($data) {
    return $this->$data;
} //get



public function set ($data, $value) {
    $this->$data = $value;
} //set

}
