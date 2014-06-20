<?php

class packageController {


private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



public function searchPackage ($searchQuery) {
    $dbM = new databaseModel();
    $dbC = new databaseController($dbM);

    $searchList = array();

    $sqlQuery = "
            SELECT package_id
                ,package_name
                ,package_serial_no
                ,date_of_purchase
            FROM tbl_packages
            WHERE package_name LIKE '%$searchQuery%'
                OR package_serial_no LIKE '%$searchQuery%'
                OR date_of_purchase LIKE '%$searchQuery%'
        ";
    $results = $dbC->query($sqlQuery);
    while ( $row = $results->fetch_assoc() ) {
        $info = array(
                '<input type="hidden" class="search-result-identifier" value="'.$row['package_id'].'" />'
                .'<span class="search-result-label">'
                .$row['package_name'].' ('.$row['package_serial_no'].')'
                .'</span>'
                ,$row['date_of_purchase']
            );
        array_push($searchList, $info);
    }
    $this->model->data('searchList', $searchList);

} //searchPackage


}
