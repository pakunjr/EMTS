<?php

class itemStateController {

private $model;

private $dbM;
private $dbV;
private $dbC;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new itemStateModel();

    $this->dbM = new databaseModel();
    $this->dbV = new databaseView($this->dbM);
    $this->dbC = new databaseController($this->dbM);
} //__construct








public function getList ($listType=null) {

    $dbC = new databaseController();

    $results = $dbC->PDOStatement(array(
        'query' => "SELECT * FROM lst_item_state"
        ));

    $list = array();
    foreach ( $results as $result ) {
        if ( $listType == 'select_options' )
            $list[$result['label']] = $result['id'];
        else {
            array_push($list, array(
                    'id'            => $result['id']
                    ,'label'        => $result['label']
                    ,'description'  => $result['description']
                ));
        }
    }

    return $list;

} //getList








public function idToLabel ($id) {
    $dbC = new databaseController();
    $results = $dbC->PDOStatement(array(
        'query' => "SELECT label FROM lst_item_state WHERE id = ?"
        ,'values'   => array(intval($id))
        ));
    return count($results) > 0
        ? $results[0]['label']
        : '';
} //idToLabel







}
