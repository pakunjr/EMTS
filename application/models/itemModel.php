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


private $itemSpecsProcessor;
private $itemSpecsVideo;
private $itemSpecsDisplay;
private $itemSpecsWebcam;
private $itemSpecsAudio;
private $itemSpecsNetwork;
private $itemSpecsUSBPorts;
private $itemSpecsMemory;
private $itemSpecsStorage;
private $itemSpecsOS;
private $itemSpecsSoftware;


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


public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value==null ) return $this->$name;
    $this->$name = $value;
} //data

}
