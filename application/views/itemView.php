<?php

class itemView {

private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function renderPage ($controller, $action) {
    $lm = new loginModel();
    $lc = new loginController($lm);

    if ( !$lm->get('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case 'new_item':
            switch ( $action ) {
                case null:
                    require_once(FORMS_DIR.DS.'item'.DS.'itemNew.php');
                    break;


                default:
            }
            break;


        case 'new_package':
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage

}
