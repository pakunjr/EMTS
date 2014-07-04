<?php

class adminView {

private $model;
private $controller;

public function __construct ($model) {
    $this->model = $model;
    $this->controller = new adminController($this->model);
} //__construct


/**
 * Render pages under the admin module
 */
public function renderPage ($controller, $action=null, $extra=null) {

    /**
     * Check authorization of the user if he / she is
     * an administrator of the system, this will also
     * serve as a detector if the user is logged-in into
     * the system
     */
    $loginM = new loginModel();
    if ( $loginM->data('accessLevel') != 'Administrator'
        || $loginM->data('accessLevelID') != '1' ) {
        $GLOBALS['pageView']->pageError('403');
    }

    switch ( $controller ) {
        case '':
            $GLOBALS['pageView']->getHeader();
            $this->renderFrontpage(true);
            $GLOBALS['pageView']->getFooter();
            break;


        case 'cache':
            switch ( $action ) {
                case 'clean':
                    $GLOBALS['cache']->clean();
                    header('location: '.URL_BASE);
                    break;


                default:
                    $GLOBALS['pageView']->pageError('403');
            }
            break;


        case 'log':
            $errM = new errorModel();
            $errV = new errorView($errM);
            $errC = new errorController($errM);

            switch ( $action ) {
                case 'errors':
                    $GLOBALS['pageView']->getHeader();
                    $errV->displayLog();
                    $GLOBALS['pageView']->getFooter();
                    break;


                case 'clean':
                    $errC->logClean();
                    header('location: '.URL_BASE.'admin/log/errors/');


                default:
                    $GLOBALS['pageView']->pageError('403');
            }
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }

} //renderPage











public function renderFrontpage ($echo=false) {
    $frontpage = '<div class="frontpage-title">Administration</div>'

        .'<a class="frontpage-option" href="'.URL_BASE.'admin/cache/clean/">'
        .'<div class="frontpage-option-image"></div>'
        .'<div class="frontpage-option-name">Clean the server Cache</div>'
        .'</a>'



        .'<a class="frontpage-option" href="'.URL_BASE.'admin/log/errors/">'
        .'<div class="frontpage-option-image"></div>'
        .'<div class="frontpage-option-name">Error / Exception Logs</div>'
        .'</a>';

    if ( !$echo ) return $frontpage;
    echo $frontpage;
} //renderFrontpage

}
