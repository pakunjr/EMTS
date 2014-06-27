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
        $query = "
            SELECT label
            FROM lst_item_type
            WHERE
                id = $id
        ";
        $result = $this->dbC->query($query);
        $row = $result->fetch_assoc();

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
        $query = $this->dbC->query("
                SELECT *
                FROM lst_item_type
            ");
        while ( $result = $query->fetch_assoc() ) {
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
