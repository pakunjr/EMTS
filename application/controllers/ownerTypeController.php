<?php

class ownerTypeController {


private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new ownerTypeModel();
} //__construct





public function selectOptions () {
    $dbC = new databaseController();
    $list = $GLOBALS['cache']->get('ownerType_list');
    if ( $list == null ) {
        $list = array();
        $results = $dbC->PDOStatement(array(
            'query' => "SELECT * FROM lst_owner_type"
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
    $this->model->data('selectOptions', $list);
} //selectOptions






public function getList ($listType=null) {

    $dbC = new databaseController();

    $results = $dbC->PDOStatement(array(
        'query' => "SELECT * FROM lst_owner_type"
        ));

    $list = array();
    foreach ( $results as $result ) {
        if ( $listType == 'select_options' )
            $list[$result['owner_label']] = $result['id'];
        else {
            array_push($list, array(
                    'id'            => $result['id']
                    ,'label'        => $result['owner_label']
                    ,'description'  => $result['owner_description']
                ));
        }
    }

    return $list;

} //getList










public function idToLabel ($id) {
    $dbC = new databaseController();

    $results = $dbC->PDOStatement(array(
        'query'     => "SELECT owner_label FROM lst_owner_type WHERE id=?"
        ,'values'   => array(intval($id))
        ));

    return count($results) > 0
        ? $results[0]['owner_label']
        : '';
} //idToLabel





}
