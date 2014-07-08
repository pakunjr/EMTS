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
 * Generate new ID that is used for ownership ID
 * ID Format: OSHP(xxxx)(xx)(xxxxx)
 * OSHP (year 4) (month 2) (sequence 5)
 * Length: 15 characters
 */
public function newOwnershipID () {

    $currentYear = date('Y');
    $currentMonth = date('m');

    /**
     * Get the last ownership ID
     */
    $results = $this->dbC->PDOStatement(array(
        'query' => "SELECT ownership_id
            FROM tbl_ownerships
            ORDER BY ownership_id DESC
            LIMIT 1"
        ,'values'   => array()
        ));

    $count = count($results);
    if ( $count < 1 ) return 'OSHP'.$currentYear.$currentMonth.'00000';

    $row = $results[0];
    $lastID = $row['ownership_id'];

    $parseYear = $lastID[4]
        .$lastID[5]
        .$lastID[6]
        .$lastID[7];
    $parseMonth = $lastID[8].$lastID[9];
    $parseSequence = $lastID[10]
        .$lastID[11]
        .$lastID[12]
        .$lastID[13]
        .$lastID[14];
    $parseSequence = (int)$parseSequence;

    /**
     * Do the sequencing
     */
    if ( $currentYear == $parseYear
            && $currentMonth == $parseMonth ) {

        $sequence = $parseSequence + 1;

        for ( $i = strlen($sequence); $i < 5; $i++ ) {
            $sequence = '0'.$sequence;
        }

    } else
        $sequence = '00000';

    $generatedID = 'OSHP'.$currentYear.$currentMonth.$sequence;
    return $generatedID;

} //newOwnershipID

























/**
 * Get the owner information using the
 * ownership information
 */
public function getOwnerInformation ($ownershipID) {
    $ownershipResult = $this->dbC->PDOStatement(array(
        'query' => "SELECT
                ownshp.owner_id
                ,ownshpType.owner_label AS owner_type
            FROM tbl_ownerships AS ownshp
            LEFT JOIN lst_owner_type AS ownshpType ON ownshp.owner_type = ownshpType.id
            WHERE
                ownership_id = ?
            LIMIT 1"
        ,'values'   => array($ownershipID)
        ));
    $ownershipRow = $ownershipResult[0];


    $ownerID = $ownershipRow['owner_id'];
    $ownerType = strtolower($ownershipRow['owner_type']);

    if ( $ownerType == 'employee' ) {


        $result = $this->dbC->PDOStatement(array(
            'query' => "SELECT
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
                    employees.employee_id = ?
                LIMIT 1"
            ,'values'   => array(array('int', $ownerID))
            ));
        $row = $result[0];
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


        $result = $this->dbC->PDOStatement(array(
            'query' => "SELECT
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
                    departments.department_id = ?
                LIMIT 1"
            ,'values'   => array(array('int', $ownerID))
            ));
        $row = $result[0];
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


        $result = $this->dbC->PDOStatement(array(
            'query' => "SELECT
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
                    guests.guest_id = ?
                LIMIT 1"
            ,'values'   => array(array('int', $ownerID))
            ));
        $row = $result[0];
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



    $results = $this->dbC->PDOStatement(array(
        'query' => "SELECT
                ownshp.owner_id
                ,ownshpType.owner_label AS owner_type
            FROM tbl_ownerships AS ownshp
            LEFT JOIN lst_owner_type AS ownshpType ON ownshp.owner_type = ownshpType.id
            GROUP BY
                ownshp.owner_id
                ,ownshp.owner_type"
        ,'values'   => array()
        ));
    foreach ( $results as $ownshpRow ) {
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

        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT
                    persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix

                    ,employees.employee_id
                FROM tbl_employees AS employees
                LEFT JOIN tbl_persons AS persons ON employees.person_id = persons.person_id
                WHERE
                    employees.employee_id = ?
                    AND (
                        persons.firstname LIKE ?
                        OR persons.middlename LIKE ?
                        OR persons.lastname LIKE ?
                        OR persons.suffix LIKE ?
                    )"
            ,'values'   => array(
                    array('int', $ownerID)
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                )
            ));
        foreach ( $results as $empRow ) {
            $ownerLabel = $empRow['lastname'].', '.$empRow['firstname'].' '.$empRow['middlename'].' '.$empRow['suffix'];
            array_push($ownerList, array(
                    'ownerID'   => $empRow['employee_id']
                    ,'ownerLabel'   => $ownerLabel
                    ,'ownerType'    => 'Employee'
                ));
        }

    } //IDEmployees foreach


    foreach ( $IDDepartments AS $ownerID ) {

        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT
                    department_id
                    ,department_name
                    ,department_name_short
                FROM tbl_departments
                WHERE
                    department_id = ?
                    AND (
                        department_name LIKE ?
                        OR department_name_short LIKE ?
                    )"
            ,'values'   => array(
                    array('int', $ownerID)
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                )
            ));
        foreach ( $results as $dptRow ) {
            $ownerLabel = $dptRow['department_name_short'].' ('.$dptRow['department_name'].')';
            array_push($ownerList, array(
                    'ownerID'   => $dptRow['department_id']
                    ,'ownerLabel'   => $ownerLabel
                    ,'ownerType'    => 'Department'
                ));
        }

    } //IDDepartments foreach


    foreach ( $IDGuests AS $ownerID ) {
        
        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT
                    persons.firstname
                    ,persons.middlename
                    ,persons.lastname
                    ,persons.suffix

                    ,guests.guest_id
                FROM tbl_guests AS guests
                LEFT JOIN tbl_persons AS persons ON guests.person_id = persons.person_id
                WHERE
                    guests.guest_id = ?
                    AND (
                        persons.firstname LIKE ?
                        OR persons.middlename LIKE ?
                        OR persons.lastname LIKE ?
                        OR persons.suffix LIKE ?
                    )"
            ,'values'   => array(
                    array('int', $ownerID)
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                    ,"%$searchKeyword%"
                )
            ));
        foreach ( $results as $gRow ) {
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
