<?php

class errorModel {

private $filepath;


public function __construct () {
    $this->filepath = LIBRARY_DIR.DS.'errorLog.php';
} //__construct


public function data ($data, $value=null) {
    if ( $value == null ) return $this->$data;
    $this->$data = $value;
}

}