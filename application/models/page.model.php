<?php

class page_model {

    private $module;
    private $display;
    private $action;

    public function __construct ($url) {
        $url_split = explode('/', $url);
        if ( isset($url_split[0]) ) $this->module = $url_split[0];
        else header('location: '. URL_BASE. 'home/');
        if ( isset($url_split[1]) ) $this->display = $url_split[1];
        if ( isset($url_split[2]) ) $this->action = $url_split[2];
    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class page_model
