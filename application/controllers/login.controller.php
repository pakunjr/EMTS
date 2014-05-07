<?php

class login_controller {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
    } //End function __construct

    public function validateLogin ($username, $raw_password) {
        $dbModel = new database_model();
        $dbController = new database_controller($dbModel);

        $result = $dbController->query("
                SELECT acc.account_id
                    , acc.username
                    , acc.password
                    , acc.password_salt
                    , emp.employee_id
                    , emp.occupation
                    , emp.designated_office_id
                    , emp.designated_department_id
                    , person.person_id
                    , person.firstname
                    , person.middlename
                    , person.lastname
                    , person.suffix
                FROM tbl_accounts AS acc
                LEFT JOIN tbl_employees AS emp
                    ON acc.owner_id = emp.employee_id
                LEFT JOIN tbl_persons AS person
                    ON emp.person_id = person.person_id
                WHERE username = '$username'
            ");

        if ( $result->num_rows < 1 ) {
            $this->model->setData('login_status', false);
            return false;
        }

        $row = $result->fetch_assoc();

        if ( $this->model->encryptPassword($raw_password, $row['password_salt']) === $row['password'] ) {

            $_SESSION['user']['account_id'] = $row['account_id'];
            $_SESSION['user']['username'] = $row['username'];

            $_SESSION['user']['employee_id'] = $row['employee_id'];
            $_SESSION['user']['occupation'] = $row['occupation'];
            $_SESSION['user']['designated_office_id']
                = $row['designated_office_id'];
            $_SESSION['user']['designated_department_id']
                = $row['designated_department_id'];

            $_SESSION['user']['person_id'] = $row['person_id'];
            $_SESSION['user']['firstname'] = $row['firstname'];
            $_SESSION['user']['middlename'] = $row['middlename'];
            $_SESSION['user']['lastname'] = $row['lastname'];
            $_SESSION['user']['suffix'] = $row['suffix'];

            $this->model->setData('login_status', true);
            header('location: '. URL_BASE. 'home/');
        }
        else header('location: '. URL_BASE. 'home/');
    } //End function validateLogin

    public function logout () {
        session_destroy();
        header('location: '. URL_BASE. 'home/');
    } //End function logout

} //End class login_controller
