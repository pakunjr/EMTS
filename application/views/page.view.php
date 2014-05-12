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
                $loginModel = new login_model();
                if ( !$loginModel->getData('login_status') )
                    $this->authorizationError();

                $formItemModel = new item_model();
                $formItemView = new item_view($formItemModel);

                switch ( $this->model->getData('display') ) {
                    case 'search_item':
                        switch ( $this->model->getData('action') ) {
                            case 'search':
                                break; //End action search

                            default:
                                $this->getHeader();
                                $formItemView->itemForm('search_item');
                                $this->getFooter();
                        }
                        break; //End display search_item

                    case 'new_item':
                        switch ( $this->model->getData('action') ) {
                            case 'save':
                                if ( !isset($_POST) ) {
                                    echo '<div>Error: There is no data that is to be save into the database.</div>';
                                    return false;
                                }

                                $itemDataArray = array(
                                        'item_serial_no' => $_POST['serial-no']
                                        , 'item_model_no' => $_POST['model-no']
                                        , 'item_name' => $_POST['item-name']
                                        , 'item_type' => $_POST['item-type']
                                        , 'item_description'
                                            => $_POST['item-description']
                                        , 'date_of_purchase'
                                            => $_POST['date-of-purchase']
                                    );

                                $itemModel = new item_model();
                                $itemController = new item_controller($itemModel);
                                $itemController->createItem($itemDataArray);
                                break; //End action save

                            default:
                                $this->getHeader();
                                $formItemView->itemForm('new_item');
                                $this->getFooter();
                        }
                        break; //End display new_item

                    case 'view_item':
                        switch ( $this->model->getData('action') ) {
                            case 'view':
                                break; //End action view

                            default:
                                $this->getHeader();
                                $formItemView->itemForm('view_item');
                                $this->getFooter();
                        }
                        break; //End display view_item

                    case 'update_item':
                        switch ( $this->model->getData('action') ) {
                            case 'update':
                                break; //End action update

                            default:
                                $this->getHeader();
                                $formItemView->itemForm('update_item');
                                $this->getFooter();
                        }
                        break; //End display update_item

                    case 'archive_item':
                        switch ( $this->model->getData('action') ) {
                            case 'archive':
                                break; //End action archive

                            default:
                                $this->getHeader();
                                $formItemView->itemForm('archive_item');
                                $this->getFooter();
                        }
                        break; //End display archive_item

                    default:
                        $this->notFoundError();
                }
                break; //End module item

            case 'package':
                $loginModel = new login_model();
                if ( !$loginModel->getData('login_status') ) {
                    $this->authorizationError();
                }
                break; //End module package

            case 'person':
                $loginModel = new login_model();
                if ( !$loginModel->getData('login_status') ) {
                    $this->authorizationError();
                }
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

    public function authorizationError () {
        $this->getHeader();
        echo '<div style="font-size: 12pt; color: #ff0000;">Error: You are not authorized to access this page.</div>';
        $this->getFooter();
        exit();
    } //End function authorizationError

    public function customError ($customMessage='You have encountered an unidentified error.') {
        $this->getHeader();
        echo $customMessage;
        $this->getFooter();
    } //End function customError

} //End class page_view
