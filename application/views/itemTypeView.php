<?php

class itemTypeView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



public function renderInstructions ($arrayList=array(), $echo=false) {
    $htmlOutput = '<table>'
        .'<tr>'
            .'<th>Label</th>'
            .'<th>Description</th>'
        .'</tr>';

    foreach ( $arrayList as $label => $description ) {
        $htmlOutput .= '<tr>'
            .'<td>'.$label.'</td>'
            .'<td>'.nl2br($description).'</td>'
            .'</tr>';
    }

    $htmlOutput .= '</table>';


    if ( !$echo ) return $htmlOutput;
    echo $htmlOutput;
} //renderInstructions

}
