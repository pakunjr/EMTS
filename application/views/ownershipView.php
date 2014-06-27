<?php

class ownershipView {

private $model;
private $controller;






public function __construct ($model) {

    $this->model = $model;
    $this->controller = new ownershipController($this->model);

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
            $this->controller->searchOwners($action);
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








public function renderSearch ($echo=false) {
    $searchList = $this->model->data('searchList');
    $renderedResults = '<table>'
        .'<tr>'
        .'<th>Owner</th>'
        .'<th>Type</th>'
        .'</tr>';

    if ( !is_array($searchList) ) return false;

    foreach ( $searchList as $i ) {
        $renderedResults .= '<tr class="search-data">'
            .'<td>'
                .'<input class="search-result-identifier" type="hidden" value="'.$i['ownerID'].'" />'
                .'<span class="search-result-label">'.$i['ownerLabel'].'</span>'
            .'</td>'
            .'<td>'.$i['ownerType'].'</td>'
            .'</tr>';
    }

    $renderedResults .= '</table>';

    if ( !$echo ) return $renderedResults;
    echo $renderedResults;
} //renderSearch






}
