<?php

class packageController {


private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new packageModel();
} //__construct



public function searchPackage ($searchQuery) {
    $dbM = new databaseModel();
    $dbC = new databaseController($dbM);

    $searchList = array();

    $results = $dbC->PDOStatement(array(
        'query' => "SELECT
                package_id
                ,package_name
                ,package_serial_no
                ,date_of_purchase
            FROM tbl_packages
            WHERE
                package_name LIKE ?
                OR package_serial_no LIKE ?
                OR date_of_purchase LIKE ?"
        ,'values'   => array(
                "%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
            )
        ));

    foreach ( $results as $row ) {
        $info = array(
                'id'    => $row['package_id']
                ,'name' => $row['package_name']
                ,'serialNo'     => $row['package_serial_no']
                ,'purchaseDate' => $row['date_of_purchase']
            );
        array_push($searchList, $info);
    }

    $this->model->data('searchList', $searchList);

} //searchPackage











public function idToLabel ($packageId) {

    $dbC = new databaseController();

    $result = $dbC->PDOStatement(array(
        'query' => "SELECT package_name, package_serial_no
            FROM tbl_packages
            WHERE package_id = ?"
        ,'values'   => array(intval($packageId))
        ));

    return count($result) > 0 ? $result[0]['package_name'].' ( '.$result[0]['package_serial_no'].' )' : '';

} //idToLabel












public function getPackageName ($packageID) {
    $dbM = new databaseModel();
    $dbC = new databaseController($dbM);

    $result = $dbC->PDOStatement(array(
        'query' => "SELECT
                package_name
                ,package_serial_no
            FROM tbl_packages
            WHERE package_id = ?
            LIMIT 1"
        ,'values'   => array(intval($packageID))
        ));
    if ( count($result) < 1 ) return '';
    $result = $result[0];
    return $result['package_name'].' ('.$result['package_serial_no'].')';
} //getPackageName



}
