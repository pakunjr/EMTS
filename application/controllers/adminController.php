<?php

class adminController {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



/**
 * Clean the contents of the phpFastCache
 */
public function cleanCache () {
    $GLOBALS['cache']->clean();
    header('location: '.URL_BASE);
} //cleanCache

}
