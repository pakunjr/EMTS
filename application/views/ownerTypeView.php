<?php

class ownerTypeView {


private $model;
private $controller;


public function __construct ($model) {
    $this->model = $model;
    $this->controller = new ownerTypeController($this->model);
} //__construct


public function renderInstructions ($echo=false) {
    $this->controller->generateDescriptionList();
    $descriptionList = $this->model->data('list');

    $lists = '';
    foreach ( $descriptionList as $label => $description ) {
        $lists .= '<tr>'
            .'<td>'.$label.'</td>'
            .'<td>'.$description.'</td>'
            .'</tr>';
    }

    $instructions = '<table>'
        .'<tr>'
        .'<th>Label</th>'
        .'<th>Description</th>'
        .'</tr>'
        .$lists
        .'</table>';

    if ( !$echo ) return $instructions;
    echo $instructions;
} //renderInstructions


}
