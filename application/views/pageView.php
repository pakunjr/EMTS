<?php

class pageView {

private $model;




public function __construct ($model) {
    $this->model = $model;
} //__construct




public function displayURI () {
    echo 'URI: ',URL_BASE,$this->model->get('uri'),'<br />'
        ,'Module: ',$this->model->get('module'),'<br />'
        ,'Controller: ',$this->model->get('controller'),'<br />'
        ,'Action: ',$this->model->get('action');
} //displayURI




public function renderPage () {
    /* $POKeyword = 'page-'.$_SERVER['REQUEST_URI'];
    $pageOutput = $GLOBALS['cache']->get($POKeyword);
    if ( $pageOutput != null && $pageOutput != NULL ) {
        echo $pageOutput,'<!-- This is the cached output -->';
        exit();
    } */


    ob_start();
    require_once(TEMPLATE_DIR.DS.'header.php');
    $pageModule = $this->model->get('module');
    $pageController = $this->model->get('controller');
    $pageAction = $this->model->get('action');
    switch ( $pageModule ) {
        case 'home':
            require_once(TEMPLATE_DIR.DS.'home.php');
            break;



        case 'login':
            $model = new loginModel();
            $view = new loginView($model);
            $controller = new loginController($model);
            switch ( $pageController ) {
                case 'validate':
                    if ( isset($_POST['username'])
                            && isset($_POST['password']) ) {
                        $iUsername = $_POST['username'];
                        $iPassword = $_POST['password'];
                        $controller->validateUser($iUsername, $iPassword);
                    } else $this->pageError('403');
                    break;


                case 'logout':
                    $controller->logout();
                    break;


                default:
                    $this->pageError('404');
            }
            break;




        case 'items':
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


    $pageOutput = ob_get_contents();
    //$GLOBALS['cache']->set($POKeyword, $pageOutput, 1800);
    ob_end_flush();
    echo '<!-- This is the uncached output -->';
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
