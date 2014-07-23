<?php

class errorController {

private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new errorModel();
} //__construct





public function logError ($eMsg) {

    $dbt = debug_backtrace();
    $caller = array_shift($dbt);
    $file = basename($caller['file']);
    $line = $caller['line'];

    $timestamp = '[ '.date('Y-m-d :: H:i:s').' ]';
    $URLPath = '[ '.$_SERVER['REQUEST_URI'].' ]';
    $location = '[ '.$file.' : '.$line.' ]';

    $currentContent = file_get_contents($this->model->data('filepath'));
    $currentContent = unserialize($currentContent);
    $currentContent = is_array($currentContent) ? $currentContent : array();

    $content = $timestamp.$URLPath.$location.' <> '.$eMsg;
    array_push($currentContent, $content);

    $finalContent = serialize($currentContent);

    file_put_contents($this->model->data('filepath'), $finalContent, LOCK_EX);

} //logError



public function logClean () {
    file_put_contents($this->model->data('filepath'), '', LOCK_EX);
} //logClean

}
