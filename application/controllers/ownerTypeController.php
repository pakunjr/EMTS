<?php

class ownerTypeController {


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
    $otList = $GLOBALS['cache']->get('ownerType_IDLabel_list');
    if ( $otList == null ) {
        $list = array();
        $result = $this->dbC->query("
                SELECT id
                    ,ownership_label
                FROM lst_ownership_type
            ");
        while ( $row = $result->fetch_assoc() ) {
            $id = $row['id'];
            $label = ucfirst($row['ownership_label']);
            $list[$label] = $id;
        }
        $this->model->data('list', $list);
        $GLOBALS['cache']->set('ownerType_IDLabel_list', $list, 3600*24);
        return false;
    }
    $this->model->data('list', $otList);
} //generateList



public function generateDescriptionList () {
    $otList = $GLOBALS['cache']->get('ownerType_labelDescription_list');
    if ( $otList == null ) {
        $list = array();
        $result = $this->dbC->query("
                SELECT ownership_label
                    ,ownership_description
                FROM lst_ownership_type
            ");
        while ( $row = $result->fetch_assoc() ) {
            $label = $row['ownership_label'];
            $description = $row['ownership_description'];
            $list[$label] = $description;
        }
        $this->model->data('list', $list);
        $GLOBALS['cache']->set('ownerType_labelDescription_list', $list, 3600*24);
        return false;
    }
    $this->model->data('list', $otList);
} //generateDescriptionList



}
