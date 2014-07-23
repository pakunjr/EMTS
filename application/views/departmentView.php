<?php

class departmentView {

private $model;
private $controller;

public function __construct ($model) {
    $this->model = $model;
    $this->controller = new departmentController($this->model);
} //__construct



public function renderPage ($controller, $action=null, $extra=null) {
    $lm = new loginModel();
    if ( !$lm->data('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case null:
            break;


        case 'search':
            $this->controller->searchDepartment($action);
            $this->renderSearch(true);
            break;


        default:
    }

} //renderPage




public function renderSearch ($echo=false) {

    $searchList = $this->model->data('searchList');

    if ( !is_array($searchList) ) return false;

    $searchResult = '<table>'
        .'<tr>'
            .'<th>Short</th>'
            .'<th>Name</th>'
            .'<th>Head</th>'
        .'</tr>';

    foreach ( $searchList as $info ) {
        $searchResult .= '<tr class="search-data">'
            .'<td>'
                .'<input class="prime-data" type="hidden" value="'.$info['id'].'" />'
                .'<span class="prime-label hidden">'.$info['short'].' ('.$info['name'].')</span>'
                .$info['short']
            .'</td>'
            .'<td>'.$info['name'].'</td>'
            .'<td>'.$info['head'].'</td>'
            .'</tr>';
    }

    $searchResult .= '</table>';

    if ( !$echo ) return $searchResult;
    echo $searchResult;
} //renderSearch


}
