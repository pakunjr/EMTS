<?php

class packageView {


private $model;
private $controller;

public function __construct ($model) {
    $this->model = $model;
    $this->controller = new packageController($this->model);
} //__construct





public function renderPage ($controller, $action=null, $extra=null) {
    switch ( $controller ) {
        case null:
            break;


        case 'search':
            $this->controller->searchPackage($action);
            $this->renderSearch(true);
            break;


        case 'new':
            $GLOBALS['pageView']->pageError('underconstruction');
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage






public function renderSearch ($echo=false) {

    $searchList = $this->model->data('searchList');

    if ( !is_array($searchList) ) return false;

    $packageSearch = '<table>'
        .'<tr>'
        .'<th>Name (Serial No)</th>'
        .'<th>Purchase Date</th>'
        .'</tr>';
    foreach ( $searchList as $info ) {
        $packageSearch .= '<tr class="search-data">'
            .'<td>'
                .'<input class="prime-data" type="hidden" value="'.$info['id'].'" />'
                .'<span class="prime-label">'
                .$info['name'].' ( '.$info['serialNo'].' )'
                .'</span>'
            .'</td>'
            .'<td>'.$info['purchaseDate'].'</td>'
            .'</tr>';
    }
    $packageSearch .= '</table>';

    if ( !$echo ) return $packageSearch;
    echo $packageSearch;

} //renderSearch


}
