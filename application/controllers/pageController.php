<?php

class pageController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct


/**
 * Set the route of the page and apply it on the model
 * for rendering later on the view
 */
public function routePage () {
    $uri = $this->model->data('uri');
    $dividedURI = explode('/', $uri);



    $module = isset($dividedURI[0]) ?
        $dividedURI[0] : 'home';
    $controller = isset($dividedURI[1]) ?
        $dividedURI[1] : null;
    $action = isset($dividedURI[2]) ?
        $dividedURI[2] : null;
    $extra = null;



    $URILength = count($dividedURI);
    if ( $URILength > 3 ) {
        $extra = '';
        for ( $i = 3; $i < $URILength; $i++ ) {
            $extra .= $dividedURI[$i].'/';
        }
    }


    $this->model->data('module', $module);
    $this->model->data('controller', $controller);
    $this->model->data('action', $action);
    $this->model->data('extra', $extra);
} //routePage


}
