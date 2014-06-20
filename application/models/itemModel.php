<?php

class itemModel {

private $itemID;


private $itemSerialNo;
private $itemModelNo;
private $itemName;
private $itemType;
private $itemDescription;
private $itemDateOfPurchase;



public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value==null ) return $this->$name;
    $this->$name = $value;
} //data

}
