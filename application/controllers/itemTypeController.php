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




public function decode ($id, $echo=false) {

    $cacheKeyword = 'itemType_label_'.$id;
    $label = $GLOBALS['cache']->get($cacheKeyword);
    if ( $label == null ) {

        $result = $this->dbC->PDOStatement(array(
            'query' => "SELECT label FROM lst_item_type WHERE id = ?"
            ,'values'   => array(array('int', $id))
            ));
        $row = $result[0];

        $label = $row['label'];
        $GLOBALS['cache']->set($cacheKeyword, $label, 3600);

    }

    if ( !$echo ) return $label;
    echo $label;
} //decode






public function generateList () {
    $list = $GLOBALS['cache']->get('itemType_list');
    if ( $list == null ) {
        $list = array();
        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT * FROM lst_item_type"
            ,'values'   => array()
            ));
        foreach ( $results as $result ) {
            array_push($list, array(
                    'id'    => $result['id']
                    ,'label'    => $result['label']
                    ,'description'  => $result['description']
                ));
        }
        $GLOBALS['cache']->set('itemType_list', $list, 3600*24);
    }
    $this->model->data('list', $list);
} //generateList





}
