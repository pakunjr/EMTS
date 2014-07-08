<?php

class errorController {

private $model;

public function __construct ($model=null) {
    if ( $model == null ) $this->model = new errorModel();
    else $this->model = $model;
} //__construct





public function logError ($eMsg) {
    $timestamp = '[ '.date('Y-m-d :: H:i:s').' ]';
    $URLPath = '[ '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].' ]';
    $currentContent = file_get_contents($this->model->data('filepath'));
    $content = $timestamp.$URLPath.' <> '.$eMsg.'; &newline;';
    file_put_contents($this->model->data('filepath'), $content, FILE_APPEND | LOCK_EX);
} //logError



public function logClean () {
    file_put_contents($this->model->data('filepath'), '', LOCK_EX);
} //logClean

}
