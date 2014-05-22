<?php

class pageController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct

public function routePage () {
    $uri = $this->model->getData('uri');
    $dividedURI = explode('/', $uri);

    $module = isset($dividedURI[0]) ? $dividedURI[0] : '';
    $controller = isset($dividedURI[1]) ? $dividedURI[1] : '';
    $action = isset($dividedURI[2]) ? $dividedURI[2] : '';
    $extra = '';

    $this->model->setData('module', $module);
    $this->model->setData('controller', $controller);
    $this->model->setData('action', $action);
    $this->model->setData('extra', $extra);
} //routePage

}
