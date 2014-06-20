<?php

class itemTypeController {

private $model;

private $dbM;
private $dbV;
private $dbC;

public function __construct ($model) {
    $this->model = $model;


    $this->dbM = new databaseModel();
    $this->dbV = new databaseView($this->dbM);
    $this->dbC = new databaseController($this->dbM);
} //__construct



public function generateList () {

    $itList = $GLOBALS['cache']->get('itemType_IDLabel_list');
    if ( $itList == null ) {
        $list = array();
        $result = $this->dbC->query("
                SELECT id
                    ,label
                FROM lst_item_type
            ");
        while ( $row = $result->fetch_assoc() ) {
            $label = $row['label'];
            $id = $row['id'];
            $list[$label] = $id;
        }
        $this->model->data('list', $list);
        $GLOBALS['cache']->set('itemType_IDLabel_list', $list, 3600*24);
        return false;
    }
    $this->model->data('list', $itList);

} //generateList



public function generateDescriptionList () {

    $itList = $GLOBALS['cache']->get('itemType_labelDescription_list');
    if ( $itList == null ) {
        $list = array();
        $result = $this->dbC->query("
                SELECT label
                    ,description
                FROM lst_item_type
            ");
        while ( $row = $result->fetch_assoc() ) {
            $label = $row['label'];
            $description = $row['description'];
            $list[$label] = $description;
        }
        $this->model->data('list', $list);
        $GLOBALS['cache']->set('itemType_labelDescription_list', $list, 3600*24);
        return false;
    }
    $this->model->data('list', $itList);

} //generateDescriptionList


}
