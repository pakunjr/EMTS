<?php

class itemStateView {

private $model;
private $controller;








public function __construct ($model) {

    $this->model = $model;
    $this->controller = new itemStateController($this->model);

} //__construct






public function generateSelectOptions () {
    $this->controller->generateList();
    $option = array();
    foreach ( $this->model->data('list') as $i ) {
        $id = $i['id'];
        $label = $i['label'];
        $option[$label] = $id;
    }
    return $option;
} //generateSelectOption






public function generateNote ($echo=false) {
    $this->controller->generateList();
    $output = '<table>'
        .'<tr>'
        .'<th>Label</th>'
        .'<th>Description</th>'
        .'</tr>';
    foreach ( $this->model->data('list') as $i ) {
        $output .= '<tr>'
            .'<td>'.$i['label'].'</td>'
            .'<td>'.$i['description'].'</td>'
            .'</tr>';
    }
    $output .= '</table>';

    if ( !$echo ) return $output;
    echo $output;
} //generateNote

}
