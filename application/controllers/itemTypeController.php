<?php

class itemTypeController {

private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new itemTypeModel();
} //__construct






public function idToLabel ($id) {
    $dbC = new databaseController();
    $results = $dbC->PDOStatement(array(
        'query' => "SELECT label FROM lst_item_type WHERE id = ?"
        ,'values'=> array(intval($id))
        ));
    return count($results) > 0
        ? $results[0]['label']
        : '';
} //idToLabel














public function getList ($listType=null) {

    $dbC = new databaseController();

    $results = $dbC->PDOStatement(array(
        'query' => "SELECT * FROM lst_item_type"
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





}
