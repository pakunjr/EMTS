<?php

class form_model {

    private $form_id;
    private $auto_label;
    private $auto_line_break;

    public function __construct ($o=array()) {
        $this->form_id = isset($o['id']) ? $o['id'] : '';
        $this->auto_label = isset($o['auto_label']) ?
            $o['auto_label'] : false;
        $this->auto_line_break = isset($o['auto_line_break']) ?
            $o['auto_line_break'] : false;
    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End setData

} //End class form_model
