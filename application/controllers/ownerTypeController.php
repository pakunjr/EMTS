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
        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT *
                FROM lst_owner_type"
            ,'values'   => array()
            ));
        foreach ( $results as $result ) {
            array_push($list, array(
                    'id'    => $result['id']
                    ,'label'    => $result['owner_label']
                    ,'description'  => $result['owner_description']
                ));
        }
        $GLOBALS['cache']->set('ownerType_list', $list, 3600*24);
    }
    $this->model->data('list', $list);
} //generateList





public function decodeID ($id) {
    $result = $this->dbC->PDOStatement(array(
        'query' => "SELECT
                owner_label
            FROM lst_owner_type
            WHERE
                id = ?
            LIMIT 1"
        ,'values'   => array(array('int', $id))
        ));
    return count($result) > 0 ? $result[0]['owner_label'] : '';
} //decodeID

public function decodeLabel ($label) {
    $result = $this->dbC->PDOStatement(array(
        'query' => "SELECT id FROM lst_owner_type WHERE owner_label = ?"
        ,'values'   => array($label)
        ));
    return count($result) > 0 ? $result[0]['id'] : '';
} //decodeLabel



}
