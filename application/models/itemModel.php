<?php

class itemModel {

private $itemID;
private $itemExists;
private $itemArchiveStatus;

/**
 * Single item information used for the update
 * form of an item
 */
private $itemInformation;


/**
 * List all items in the database for viewing
 */
private $itemList;



/**
 * Item search
 */
private $itemSearch;


/**
 * Form variables
 */
private $formVariables;




public function __construct () {

} //__construct



public function data ($name, $value=null) {
    if ( $value==null ) return $this->$name;
    $this->$name = $value;
} //data

}
