<?php

class itemView {

private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function renderPage ($controller, $action=null, $extra=null) {
    $lm = new loginModel();
    $lc = new loginController($lm);

    if ( !$lm->data('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case null:
            require_once(FORMS_DIR.DS.'item'.DS.'frontpage.php');
            break;


        case 'new_item':
            switch ( $action ) {
                case null:
                    require_once(FORMS_DIR.DS.'item'.DS.'itemNew.php');
                    break;


                default:
                    $GLOBALS['pageView']->pageError('404');
            }
            break;


        case 'update_item':
            switch ( $action ) {
                case null:
                    require_once(FORMS_DIR.DS.'item'.DS.'itemUpdate.php');
                    break;

                default:
                    $GLOBALS['pageView']->pageError('404');
            }
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage

}
