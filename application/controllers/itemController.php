<?php

class itemController {

private $model;


public function __construct ($model) {
    $this->model = $model;
} //_construct





/**
 * For saving a new item
 */
public function createItem ($datas=array()) {
    if ( !is_array($datas) ) {
        echo 'There was an error in saving.<br />'
            ,'Item was not saved.';
        return false;
    }

    $dbM = new databaseModel();
    $dbV = new databaseView($dbM);
    $dbC = new databaseController($dbM);


    echo '<span style="display: inline-block; padding: 0px 0px 5px 0px;">Saving is underconstruction. Thank you.</span><hr /><br />';

    foreach ( $datas as $label => $value ) {
        echo '<span style="display: inline-block; padding: 5px 15px; border-radius: 5px; border: 1px solid #ccc;">',$label,'</span>'
            ,'<span style="display: inline-block; padding: 5px 15px; border-radius: 5px; border: 1px solid #ccc;">',nl2br($value),'</span>'
            ,'<br />';
    }

    foreach ( $datas as $label => $value ) {
        $datas[$label] = $dbC->escapeString($value);
    }
} //createItem




/**
 * For updating an existing item
 */
public function updateItem ($itemID) {

} //updateItem




/**
 * For archiving an item which is
 * equivalent for delete but the
 * data still exists on the database
 */
public function archiveItem ($itemID) {

} //archiveItem



}
