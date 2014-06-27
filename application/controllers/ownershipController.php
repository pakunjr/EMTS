<?php

class ownershipController {

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









/**
 * Get the owner information using the
 * ownership information
 */
public function getOwnerInformation ($ownershipID) {
    $ownershipQuery = "
            SELECT
                ownshp.owner_id
                ,ownshpType.ownership_label AS owner_type
            FROM tbl_ownerships AS ownshp
            LEFT JOIN lst_ownership_type AS ownshpType ON ownshp.owner_type = ownshpType.id
            WHERE
                ownership_id = '$ownershipID'
        ";
    $ownershipResult = $this->dbC->query($ownershipQuery);
    $ownershipRow = $ownershipResult->fetch_assoc();

    $ownerID = $ownershipRow['owner_id'];
    $ownerType = strtolower($ownershipRow['owner_type']);

    if ( $ownerType == 'employee' ) {
        $query = "
            SELECT
                persons.firstname
                ,persons.middlename
                ,persons.lastname
                ,persons.suffix

                ,employees.occupation
                ,employees.employment_status

                ,department.department_name
                ,department.department_name_short
            FROM tbl_employees AS employees
            LEFT JOIN
                tbl_persons AS persons ON employees.person_id = persons.person_id
            LEFT JOIN
                tbl_departments AS department ON employees.department_id = department.department_id
            WHERE
                employees.employee_id = $ownerID
        ";
        $result = $this->dbC->query($query);
        $row = $result->fetch_assoc();
        return array(
                'firstname'     => $row['firstname']
                ,'middlename'   => $row['middlename']
                ,'lastname'     => $row['lastname']
                ,'suffix'       => $row['suffix']
                ,'departmentName'   => $row['department_name']
                ,'departmentShort'  => $row['department_name_short']

                ,'ownerType'        => 'employee'
            );
    } else if ( $ownerType == 'department' ) {
        $query = "
            SELECT
                departments.department_name
                ,departments.department_name_short
                ,departments.department_description

                ,persons.firstname
                ,persons.middlename
                ,persons.lastname
                ,persons.suffix

                ,employees.occupation
                ,employees.employment_status
            FROM tbl_departments AS departments
            LEFT JOIN
                tbl_employees AS employees ON departments.department_head_id = employees.employee_id
            LEFT JOIN
                tbl_persons AS persons ON employees.person_id = persons.person_id
            WHERE
                departments.department_id = $ownerID
        ";
        $result = $this->dbC->query($query);
        $row = $result->fetch_assoc();
        return array(
                'departmentName'    => $row['department_name']
                ,'departmentShort'  => $row['department_name_short']
                ,'departmentDescription'    => $row['department_description']
                ,'personFirstname'  => $row['firstname']
                ,'personMiddlename' => $row['middlename']
                ,'personLastname'   => $row['lastname']
                ,'personSuffix'     => $row['suffix']
                ,'employeeOccupation'   => $row['occupation']
                ,'employeeStatus'       => $row['employment_status']

                ,'ownerType'            => 'department'
            );
    } else if ( $ownerType == 'guest' ) {
        $query = "
            SELECT
                persons.firstname
                ,persons.middlename
                ,persons.lastname
                ,persons.suffix

                ,guests.occupation
                ,guests.company_name
                ,guests.company_details
            FROM tbl_guests AS guests
            LEFT JOIN
                tbl_persons AS persons ON guests.person_id = persons.person_id
            WHERE
                guests.guest_id = $ownerID
        ";
        $result = $this->dbC->query($query);
        $row = $result->fetch_assoc();
        return array(
                'firstname'     => $row['firstname']
                ,'middlename'   => $row['middlename']
                ,'lastname'     => $row['lastname']
                ,'suffix'       => $row['suffix']
                ,'guestOccupation'   => $row['occupation']
                ,'guestCompanyName'  => $row['company_name']
                ,'guestCompanyDetails'  => $row['company_details']

                ,'ownerType'            => 'guest'
            );
    } else {
        return array(
                'ownerType' => 'none'
            );
    }
} //getOwnerInformation















/**
 * Get the name / label only of the owner
 */
public function getOwnerName ($ownershipID) {
    $ownerInfo = $this->getOwnerInformation($ownershipID);

    switch ( $ownerInfo['ownerType'] ) {
        case 'employee':
            return $ownerInfo['lastname'].', '.$ownerInfo['firstname'].' '.$ownerInfo['middlename'].' '.$ownerInfo['suffix'];
            break;


        case 'department':
            return $ownerInfo['departmentShort'].' ('.$ownerInfo['departmentName'].')';
            break;


        case 'guest':
            return $ownerInfo['lastname'].', '.$ownerInfo['firstname'].' '.$ownerInfo['middlename'].' '.$ownerInfo['suffix'];
            break;


        default:
            return 'None';
    }
} //getOwnerName














public function searchOwners ($searchKeyword) {

    $ownerList = array();

    $IDEmployees = array();
    $IDDepartments = array();
    $IDGuests = array();

    $ownshpQuery = $this->dbC->query("
            SELECT
                ownshp.owner_id
                ,ownshpType.ownership_label AS owner_type
            FROM tbl_ownerships AS ownshp
            LEFT JOIN lst_ownership_type AS ownshpType ON ownshp.owner_type = ownshpType.id
            GROUP BY
                ownshp.owner_id
                ,ownshp.owner_type
        ");
    while ( $ownshpRow = $ownshpQuery->fetch_assoc() ) {
        $ownshpRow['owner_type'] = strtolower($ownshpRow['owner_type']);
        switch ( $ownshpRow['owner_type'] ) {
            case 'employee':
                array_push($IDEmployees, $ownshpRow['owner_id']);
                break;

            case 'department':
                array_push($IDDepartments, $ownshpRow['owner_id']);
                break;

            case 'guest':
                array_push($IDGuests, $ownshpRow['owner_id']);
                break;

            default:
        }
    }


    foreach ( $IDEmployees AS $ownerID ) {
        $empQuery = $this->dbC->query("
                SELECT
                    persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix

                    ,employees.employee_id
                FROM tbl_employees AS employees
                LEFT JOIN tbl_persons AS persons ON employees.person_id = persons.person_id
                WHERE
                    employees.employee_id = $ownerID
                    AND (
                        persons.firstname LIKE '%$searchKeyword%'
                        OR persons.middlename LIKE '%$searchKeyword%'
                        OR persons.lastname LIKE '%$searchKeyword%'
                        OR persons.suffix LIKE '%$searchKeyword%'
                    )
            ");
        while ( $empRow = $empQuery->fetch_assoc() ) {
            $ownerLabel = $empRow['lastname'].', '.$empRow['firstname'].' '.$empRow['middlename'].' '.$empRow['suffix'];
            array_push($ownerList, array(
                    'ownerID'   => $empRow['employee_id']
                    ,'ownerLabel'   => $ownerLabel
                    ,'ownerType'    => 'Employee'
                ));
        }
    } //IDEmployees foreach


    foreach ( $IDDepartments AS $ownerID ) {
        $dptQuery = $this->dbC->query("
                SELECT
                    department_id
                    ,department_name
                    ,department_name_short
                FROM tbl_departments
                WHERE
                    department_id = $ownerID
                    AND (
                        department_name LIKE '%$searchKeyword%'
                        OR department_name_short LIKE '%$searchKeyword%'
                    )
            ");
        while ( $dptRow = $dptQuery->fetch_assoc() ) {
            $ownerLabel = $dptRow['department_name_short'].' ('.$dptRow['department_name'].')';
            array_push($ownerList, array(
                    'ownerID'   => $dptRow['department_id']
                    ,'ownerLabel'   => $ownerLabel
                    ,'ownerType'    => 'Department'
                ));
        }
    } //IDDepartments foreach


    foreach ( $IDGuests AS $ownerID ) {
        $gQuery = $this->dbC->query("
                SELECT
                    persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix

                    ,guests.guest_id
                FROM tbl_guests AS guests
                LEFT JOIN tbl_persons AS persons ON guests.person_id = persons.person_id
                WHERE
                    guests.guest_id = $ownerID
                    AND (
                        persons.firstname LIKE '%$searchKeyword%'
                        OR persons.middlename LIKE '%$searchKeyword%'
                        OR persons.lastname LIKE '%$searchKeyword%'
                        OR persons.suffix LIKE '%$searchKeyword%'
                    )
            ");
        while ( $gRow = $gQuery->fetch_assoc() ) {
            $ownerLabel = $gRow['lastname'].', '.$gRow['firstname'].' '.$gRow['middlename'].' '.$gRow['suffix'];
            array_push($ownerList, array(
                    'ownerID'   => $gRow['guest_id']
                    ,'ownerLabel'   => $ownerLabel
                    ,'ownerType'    => 'Guest'
                ));
        }
    } //IDGuests foreach

    $this->model->data('searchList', $ownerList);

} //searchOwners




}
