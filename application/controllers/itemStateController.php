<?php

class itemStateController {

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
    $list = $GLOBALS['cache']->get('itemState_list');
    if ( $list == null ) {
        $list = array();
        $query = $this->dbC->query("
                SELECT
                    id
                    ,label
                    ,description
                FROM lst_item_state
            ");
        while ( $result = $query->fetch_assoc() ) {
            array_push($list, array(
                    'id'        => $result['id']
                    ,'label'     => $result['label']
                    ,'description'  => $result['description']
                ));
        }
        $GLOBALS['cache']->set('itemState_list', $list, 3600*24);
    }
    $this->model->data('list', $list);
} //generateList




}
