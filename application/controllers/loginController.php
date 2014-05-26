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
    $checkUsername = $dbController->query("
            SELECT password
                ,password_salt
            FROM tbl_accounts
            WHERE username = '$username'
            LIMIT 1
        ");
    if ( $checkUsername->num_rows > 0 ) {
        /**
         * Validate the combination of username and password
         */
        $row = $checkUsername->fetch_array();
        $dbPassword = $row['password'];
        $dbSalt = $row['password_salt'];

        $encryptedPass = $this->encryptPassword($password, $dbSalt);

        if ( $encryptedPass === $dbPassword ) {
            $this->model->set('isAuthorized', true);
            $_SESSION['user'] = 'Horaoh!';
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
    return hash('sha256', $password. $salt);
} //encryptPassword

}
