<?php

class pageView {

private $model;




public function __construct ($model) {
    $this->model = $model;
} //__construct




public function displayURI () {
    echo 'URI: ',URL_BASE,$this->model->data('uri'),'<br />'
        ,'Module: ',$this->model->data('module'),'<br />'
        ,'Controller: ',$this->model->data('controller'),'<br />'
        ,'Action: ',$this->model->data('action');
} //displayURI




public function renderPage () {
    require_once(TEMPLATE_DIR.DS.'header.php');
    $URIModule = $this->model->data('module');
    $URIController = $this->model->data('controller');
    $URIAction = $this->model->data('action');
    $URIExtra = $this->model->data('extra');
    switch ( $URIModule ) {
        case 'home':
            require_once(TEMPLATE_DIR.DS.'home.php');
            break;



        case 'login':
            $loginModel = new loginModel();
            $loginView = new loginView($loginModel);
            $loginController = new loginController($loginModel);
            switch ( $URIController ) {
                case 'validate':
                    if ( isset($_POST['username'])
                            && isset($_POST['password']) ) {
                        $iUsername = $_POST['username'];
                        $iPassword = $_POST['password'];
                        $loginController->validateUser($iUsername, $iPassword);
                    } else $this->pageError('403');
                    break;


                case 'logout':
                    $loginController->logout();
                    break;


                default:
                    $this->pageError('404');
            }
            break;




        case 'items':
            $itemModel = new itemModel();
            $itemView = new itemView($itemModel);
            $itemController = new itemController($itemModel);
            $itemView->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'owners':
            break;



        case 'reports':
            break;




        case 'archives':
            break;



        default:
            $this->pageError('404');
    }
    require_once(TEMPLATE_DIR.DS.'footer.php');
} //renderPage






public function pageError ($type) {
    switch ( $type ) {
        case '403':
            require_once(ERRORS_DIR.DS.'403.php');
            break;


        case '404':
            require_once(ERRORS_DIR.DS.'404.php');
            break;


        case 'loginError':
            require_once(ERRORS_DIR.DS.'loginError.php');
            break;


        default:
            require_once(ERRORS_DIR.DS.'unknown.php');
    }
} //pageError

}
