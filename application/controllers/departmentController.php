<?php

class departmentController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct




public function searchDepartment ($searchQuery) {
    $dbM = new databaseModel();
    $dbV = new databaseView($dbM);
    $dbC = new databaseController($dbM);

    $searchList = array();
    $sqlQuery = "
            SELECT dpt.department_id
                ,dpt.department_name_short
                ,dpt.department_name
                ,psn.firstname
                ,psn.middlename
                ,psn.lastname
                ,psn.suffix
            FROM tbl_departments AS dpt
            LEFT JOIN tbl_persons AS psn ON dpt.department_head_id = psn.person_id
            WHERE dpt.department_name_short LIKE '%$searchQuery%'
                OR dpt.department_name LIKE '%$searchQuery%'
        ";

    $result = $dbC->query($sqlQuery);
    while ( $row = $result->fetch_assoc() ) {
        $info = array(
                'id'        => $row['department_id']
                ,'short'    => $row['department_name_short']
                ,'name'     => $row['department_name']
                ,'head'     => $row['lastname'].', '
                    .$row['firstname'].' '
                    .$row['middlename'].' '
                    .$row['suffix']
            );
        array_push($searchList, $info);
    }

    $this->model->data('searchList', $searchList);


} //searchDepartment



}
