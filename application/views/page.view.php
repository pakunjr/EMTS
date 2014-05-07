<?php

class page_view {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
    } //End function __construct

    public function renderPage () {
        switch ( $this->model->getData('module') ) {
            case 'home':
                switch ( $this->model->getData('display') ) {
                    case '':
                        $this->getHeader();
                        require_once(TEMPLATE_DIR. DS. 'home.php');
                        $this->getFooter();
                        break; //End display default

                    default:
                        $this->notFoundError();
                }
                break; //End module home

            case 'item':
                break; //End module item

            case 'person':
                break; //End module person

            case 'account':
                switch ( $this->model->getData('display') ) {
                    case 'login':
                        switch ( $this->model->getData('action') ) {
                            case 'validate':
                                $loginModel = new login_model();
                                $loginController
                                    = new login_controller($loginModel);

                                if ( $loginModel->getData('login_status') ) {
                                    $this->customError('You are already logged into the system. Logout first then login again if you want to re-login.<br />Thank you.');
                                    exit();
                                } else if ( !isset($_POST['username'])
                                    && !isset($_POST['password']) ) {
                                    $this->customError('You cannot access this page directly. Please login properly, Thank you.');
                                    exit();
                                }

                                $loginController->validateLogin($_POST['username'], $_POST['password']);
                                break; //End action validate

                            default:
                                $this->notFoundError();
                        }
                        break; //End display login

                    case 'logout':
                        $loginModel = new login_model();
                        $loginController = new login_controller($loginModel);
                        $loginController->logout();
                        break; //End display logout

                    default:
                        $this->notFoundError();
                }
                break; //End module account
                
            default:
                $this->notFoundError();
        }
    } //End function renderPage

    public function getHeader () {
        $file = TEMPLATE_DIR. DS. 'header.php';
        if ( file_exists($file) ) require_once($file);
        else echo '<div>Error: Your template header file is missing.</div>';
    } //End function getHeader

    public function getFooter () {
        $file = TEMPLATE_DIR. DS. 'footer.php';
        if ( file_exists($file) ) require_once($file);
        else echo '<div>Error: Your template footer file is missing.</div>';
    } //End function getFooter

    public function notFoundError () {
        $error_file = TEMPLATE_DIR. DS. '404.php';
        $this->getHeader();
        if ( file_exists($error_file) ) require_once($error_file);
        $this->getFooter();
    } //End function notFoundError

    public function customError ($customMessage='You have encountered an unidentified error.') {
        $this->getHeader();
        echo $customMessage;
        $this->getFooter();
    } //End function customError

} //End class page_view
