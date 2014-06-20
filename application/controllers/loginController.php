<?php

class loginController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct




public function validateUser ($username, $password) {
    $dbModel = new databaseModel();
    $dbController = new databaseController($dbModel);

    /**
     * Check existence of the username
     */
    $validateQuery = $dbController->query("
            SELECT acc.username
                ,acc.password
                ,acc.password_salt
                ,acc.access_level AS access_level_id
                ,person.firstname
                ,person.middlename
                ,person.lastname
                ,person.suffix
                ,person.email_address
                ,access.name AS access_level
            FROM tbl_accounts AS acc
            LEFT JOIN tbl_persons AS person ON acc.owner_id = person.person_id
            LEFT JOIN lst_access_level AS access ON acc.access_level = access.id
            WHERE acc.username = '$username'
        ");
    if ( $validateQuery->num_rows > 0 ) {
        /**
         * Validate the combination of username and password
         */
        $row = $validateQuery->fetch_array();
        $dbPassword = $row['password'];
        $dbSalt = $row['password_salt'];

        $encryptedPass = $this->encryptPassword($password, $dbSalt);

        if ( $encryptedPass === $dbPassword ) {
            $this->model->data('isAuthorized', true);
            $_SESSION['user'] = array(
                    'username'          => $row['username']
                    ,'accessLevelID'    => $row['access_level_id']
                    ,'accessLevel'      => $row['access_level']
                    ,'firstname'        => $row['firstname']
                    ,'middlename'       => $row['middlename']
                    ,'lastname'         => $row['lastname']
                    ,'suffix'           => $row['suffix']
                    ,'email'            => $row['email_address']
                );
            header('location: '.URL_BASE);
        } else
            $GLOBALS['pageView']->pageError('loginError');
    } else
        $GLOBALS['pageView']->pageError('loginError');

} //validateUser



public function logout () {
    session_destroy();
    header('location: '.URL_BASE);
} //logout



public function encryptPassword ($password, $salt) {
    return hash('sha256', $password.$salt);
} //encryptPassword

}
