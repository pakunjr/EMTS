<?php

class personController {


private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function searchPerson ($searchQuery) {

    $dbM = new databaseModel();
    $dbV = new databaseView($dbM);
    $dbC = new databaseController($dbM);

    $searchList = array();

    $sqlQuery = "
            SELECT person_id
                ,firstname
                ,middlename
                ,lastname
                ,suffix
                ,gender
                ,birthdate
                ,email_address
            FROM tbl_persons
            WHERE firstname LIKE '%$searchQuery%'
                OR middlename lIKE '%$searchQuery%'
                OR lastname LIKE '%$searchQuery%'
                OR suffix LIKE '%$searchQuery%'
                OR email_address LIKE '%$searchQuery%'
            ORDER BY lastname ASC
        ";

    $result = $dbC->query($sqlQuery);
    while ( $row = $result->fetch_assoc() ) {
        $tmpGender = ( $row['gender'] == 'm' ) ?
            'Male' : 'Female';

        $info = array(
                '<input class="search-result-identifier" type="hidden" value="'.$row['person_id'].'" />'
                .'<span class="search-result-label">'
                .$row['lastname'].', '
                .$row['firstname'].' '
                .$row['middlename'].' '
                .$row['suffix']
                .'</span>'
                ,$tmpGender
                ,$row['birthdate']
                ,$row['email_address']
            );
        array_push($searchList, $info);
    }

    $this->model->data('searchList', $searchList);

} //searchPerson



}
