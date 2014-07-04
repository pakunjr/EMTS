<?php

class personController {


private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function searchPerson ($searchQuery, $searchType='employee') {

    $dbM = new databaseModel();
    $dbC = new databaseController($dbM);

    $searchList = array();

    if ( $searchType == 'guest' ) {

        $SQLQuery = "SELECT
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
                persons.firstname LIKE ?
                OR persons.middlename LIKE ?
                OR persons.lastname LIKE ?
                OR persons.suffix LIKE ?
                OR persons.email_address LIKE ?
            ORDER BY
                persons.lastname ASC
                ,persons.firstname ASC
                ,persons.middlename ASC
                ,persons.suffix DESC
        ";
        $SQLValues = array(
                "%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
            );
    } else { //include employee

        $SQLQuery = "SELECT
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
                persons.firstname LIKE ?
                OR persons.middlename lIKE ?
                OR persons.lastname LIKE ?
                OR persons.suffix LIKE ?
                OR persons.email_address LIKE ?
            ORDER BY
                persons.lastname ASC
                ,persons.firstname ASC
                ,persons.middlename ASC
                ,persons.suffix DESC
        ";
        $SQLValues = array(
                "%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
            );
    }


    $results = $dbC->PDOStatement(array(
            'query' => $SQLQuery
            ,'values'   => $SQLValues
        ));

    foreach ( $results as $row ) {
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
