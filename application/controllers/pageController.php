<?php

class pageController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct

public function routePage () {
    $uri = $this->model->get('uri');
    $dividedURI = explode('/', $uri);

    $module = isset($dividedURI[0]) ? $dividedURI[0] : 'home';
    $controller = isset($dividedURI[1]) ? $dividedURI[1] : '';
    $action = isset($dividedURI[2]) ? $dividedURI[2] : '';
    $extra = '';

    $this->model->set('module', $module);
    $this->model->set('controller', $controller);
    $this->model->set('action', $action);
    $this->model->set('extra', $extra);
} //routePage

}
