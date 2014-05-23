<?php

class pageView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} // __construct

public function displayURI () {
    echo 'URI: ',URL_BASE,$this->model->getData('uri'),'<br />'
        ,'Module: ',$this->model->getData('module'),'<br />'
        ,'Controller: ',$this->model->getData('controller'),'<br />'
        ,'Action: ',$this->model->getData('action');
} // displayURI

public function renderPage () {
    require_once(TEMPLATE_DIR.DS.'header.php');
    $module = $this->model->getData('module');
    switch ( $module ) {
        case 'home':
            require_once(TEMPLATE_DIR.DS.'home.php');
            break;

        case 'item':
            break;

        case 'person':
            break;

        case 'ownership':
            break;

        case 'user':
            break;

        default:
            require_once(ERRORS_DIR.DS.'404.php');
    }
    require_once(TEMPLATE_DIR.DS.'footer.php');
} // renderPage

}
