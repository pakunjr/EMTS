<?php

class personController {


private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function searchPerson ($searchQuery, $searchType='employee') {

    $dbM = new databaseModel();
    $dbV = new databaseView($dbM);
    $dbC = new databaseController($dbM);

    $searchList = array();

    if ( $searchType == 'guest' ) {
        $sqlQuery = "
                SELECT
                    guests.guest_id AS owner_id
                    ,persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix
                    ,persons.gender
                    ,persons.birthdate
                    ,persons.email_address
                FROM tbl_guests AS guests
                LEFT JOIN
                    tbl_persons AS persons ON guests.person_id = persons.person_id
                WHERE
                    persons.firstname LIKE '%$searchQuery%'
                    OR persons.middlename lIKE '%$searchQuery%'
                    OR persons.lastname LIKE '%$searchQuery%'
                    OR persons.suffix LIKE '%$searchQuery%'
                    OR persons.email_address LIKE '%$searchQuery%'
                ORDER BY persons.lastname ASC
            ";
    } else { //include employee
        $sqlQuery = "
                SELECT
                    employees.employee_id AS owner_id
                    ,persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix
                    ,persons.gender
                    ,persons.birthdate
                    ,persons.email_address
                FROM tbl_employees AS employees
                LEFT JOIN
                    tbl_persons AS persons ON employees.person_id = persons.person_id
                WHERE
                    persons.firstname LIKE '%$searchQuery%'
                    OR persons.middlename lIKE '%$searchQuery%'
                    OR persons.lastname LIKE '%$searchQuery%'
                    OR persons.suffix LIKE '%$searchQuery%'
                    OR persons.email_address LIKE '%$searchQuery%'
                ORDER BY persons.lastname ASC
            ";
    }


    $result = $dbC->query($sqlQuery);
    while ( $row = $result->fetch_assoc() ) {
        $tmpGender = ( $row['gender'] == 'm' ) ?
            'Male' : 'Female';

        $info = array(
                '<input class="search-result-identifier" type="hidden" value="'.$row['owner_id'].'" />'
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
