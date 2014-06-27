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
    $list = $GLOBALS['cache']->get('ownerType_list');
    if ( $list == null ) {
        $list = array();
        $query = $this->dbC->query("
                SELECT *
                FROM lst_ownership_type
            ");
        while ( $result = $query->fetch_assoc() ) {
            array_push($list, array(
                    'id'    => $result['id']
                    ,'label'    => $result['ownership_label']
                    ,'description'  => $result['ownership_description']
                ));
        }
        $GLOBALS['cache']->set('ownerType_list', $list, 3600*24);
    }
    $this->model->data('list', $list);
} //generateList





public function decodeID ($id) {
    $query = $this->dbC->query("
            SELECT
                ownership_label
            FROM lst_ownership_type
            WHERE
                id = $id
        ");
    $result = $query->fetch_assoc();
    return $result['ownership_label'];
} //decodeID



}
