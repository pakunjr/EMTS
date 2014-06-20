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
     * Check authority to access the admin page.
     */
    $loginM = new loginModel();
    if ( !$loginM->data('isAuthorized')
        && ($loginM->data('accessLevel') != 'Administrator'
            || $loginM->data('accessLevelID') != '1') )
        $GLOBALS['pageView']->pageError('403');

    switch ( $controller ) {
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


        default:
            $GLOBALS['pageView']->pageError('404');
    }

} //renderPage

}
