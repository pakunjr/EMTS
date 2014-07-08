<?php

class itemModel {

private $itemID;
private $itemExists;
private $itemArchiveStatus;

private $itemSerialNo;
private $itemModelNo;
private $itemName;
private $itemType;
private $itemState;
private $itemDescription;
private $itemDateOfPurchase;
private $itemQuantity;
private $itemQuantityUnit;



private $packageID;
private $packageName;
private $packageSerialNo;
private $packageDescription;
private $packageDateOfPurchase;

/**
 * Ownership list / history
 */
private $ownershipList;
private $currentOwner;


/**
 * List all items in the database for viewing
 */
private $itemList;



/**
 * Item search
 */
private $itemSearch;




public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value==null ) return $this->$name;
    $this->$name = $value;
} //data

}
