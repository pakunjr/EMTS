<?php

class personView {


private $model;
private $controller;


public function __construct ($model) {
    $this->model = $model;
    $this->controller = new personController($this->model);
} //__construct



public function renderPage ($controller, $action=null, $extra=null) {
    $lm = new loginModel();
    if ( !$lm->data('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case null:
            $this->renderFrontpage(true);
            break;



        case 'search':
            $this->controller->searchPerson($action);
            $this->renderSearch(true);
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage




public function renderFrontpage ($echo=false) {
    $frontpage = '- Underconstruction';

    if ( !$echo ) return $frontpage;
    echo $frontpage;
} //renderFrontpage





/**
 * Render search results
 */
public function renderSearch ($echo=false) {

    $renderedResults = '<table>'
        .'<tr>'
            .'<th>Name</th>'
            .'<th>Gender</th>'
            .'<th>Birthdate</th>'
            .'<th>Email Address</th>'
        .'</tr>';

    $searchList = $this->model->data('searchList');

    if ( !is_array($searchList) ) return false;

    foreach ( $searchList as $informations ) {
        $renderedResults .= '<tr class="search-data">';
        foreach ( $informations as $info ) {
            $renderedResults .= '<td>'.$info.'</td>';
        }
        $renderedResults .= '</tr>';
    }


    $renderedResults .= '</table>';

    if ( !$echo ) return $renderedResults;
    echo $renderedResults;

} //renderSearch


}
