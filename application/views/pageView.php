<?php

class pageView {

private $model;




public function __construct ($model) {
    $this->model = $model;
} //__construct




public function displayURI () {
    echo 'URI: ',URL_BASE,$this->model->data('uri'),'<br />'
        ,'Module: ',$this->model->data('module'),'<br />'
        ,'Controller: ',$this->model->data('controller'),'<br />'
        ,'Action: ',$this->model->data('action');
} //displayURI




public function renderPage () {
    $URIModule = $this->model->data('module');
    $URIController = $this->model->data('controller');
    $URIAction = $this->model->data('action');
    $URIExtra = $this->model->data('extra');
    switch ( $URIModule ) {
        case 'home':
            $this->getHeader();
            require_once(TEMPLATE_DIR.DS.'home.php');
            $this->getFooter();
            break;



        case 'admin':
            $adminM = new adminModel();
            $adminV = new adminView($adminM);
            $adminV->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'login':
            $loginModel = new loginModel();
            $loginView = new loginView($loginModel);
            $loginController = new loginController($loginModel);
            switch ( $URIController ) {
                case 'validate':
                    if ( isset($_POST['username'])
                            && isset($_POST['password']) ) {
                        $iUsername = $_POST['username'];
                        $iPassword = $_POST['password'];
                        $loginController->validateUser($iUsername, $iPassword);
                    } else $this->pageError('403');
                    break;


                case 'logout':
                    $loginController->logout();
                    break;


                default:
                    $this->pageError('404');
            }
            break;




        case 'items':
            $itemM = new itemModel();
            $itemV = new itemView($itemM);
            $itemV->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'persons':
            $personM = new personModel();
            $personV = new personView($personM);
            $personV->renderPage($URIController, $URIAction, $URIExtra);
            break;


        case 'departments':
            $departmentM = new departmentModel();
            $departmentV = new departmentView($departmentM);
            $departmentV->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'packages':
            $packageM = new packageModel();
            $packageV = new packageView($packageM);
            $packageV->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'owners':
            $ownershipM = new ownershipModel();
            $ownershipV = new ownershipView($ownershipM);
            $ownershipV->renderPage($URIController, $URIAction, $URIExtra);
            break;



        case 'reports':
            $this->pageError('underconstruction');
            break;




        case 'archives':
            $this->pageError('underconstruction');
            break;



        default:
            $this->pageError('404');
    }
} //renderPage




/**
 * Header and Footer
 */
public function getHeader () {
    require_once(TEMPLATE_DIR.DS.'header.php');
} //getHeader

public function getFooter () {
    require_once(TEMPLATE_DIR.DS.'footer.php');
} //getFooter





/**
 * Navigational menus
 */
public function renderNavigation ($echo=true) {
    $loginM = new loginModel();
    $loginV = new loginView($loginM);
    $accessLevel = $loginM->data('accessLevel');

    if ( $accessLevel == 'Administrator' ) {
        $adminBlock = '<span class="menu-items">'
            .'<a href="'.URL_BASE.'admin/">Admin</a>'
            .'<ol type="none" class="submenu">'
            .'<li><a href="'.URL_BASE.'admin/cache/clean/">Clean Server Cache</a></li>'
            .'</ol>'
            .'</span>';
    } else
        $adminBlock = '';


    if ( $loginM->data('isAuthorized') ) {

        $itemsBlock = '<span class="menu-items">'
            .'<a href="'.URL_BASE.'items/">Items</a>'
            .'<ol type="none" class="submenu">'
            .'<li><a href="'.URL_BASE.'items/new_item/">New Item/s</a></li>'
            .'<li><a href="'.URL_BASE.'items/view_all">View Items</a></li>'
            .'</ol>'
            .'</span>';


        $ownersBlock = '<span class="menu-items">'
            .'<a href="'.URL_BASE.'owners/">Owners</a>'
            .'<ol type="none" class="submenu">'
            .'<li><a href="'.URL_BASE.'owners/complete_list/">Complete List</a></li>'
            .'<li><a href="#">Register Employee</a></li>'
            .'<li><a href="#">Register Department</a></li>'
            .'</ol>'
            .'</span>';


        $reportsBlock = '<span class="menu-items">'
            .'<a href="'.URL_BASE.'reports/">Reports</a>'
            .'<ol type="none" class="submenu">'
            .'<li><a href="'.URL_BASE.'reports/generate/">Generate Report</a></li>'
            .'<li><a href="'.URL_BASE.'reports/history/">History Report</a></li>'
            .'</ol>'
            .'</span>';


        $archivesBlock = '<span class="menu-items">'
            .'<a href="'.URL_BASE.'archives/">Archives</a>'
            .'</span>';

    } else {
        $itemsBlock = '';
        $ownersBlock = '';
        $reportsBlock = '';
        $archivesBlock = '';
    }





    $navigation = '<div id="navigation">'
        .'<span>'
        .'<a href="'.URL_BASE.'">'
            .'<img class="logo-emts-60x60" src="'.URL_BASE.'public/img/logo_EMTS_60x60.png" style="display: inline-block; margin: 2px 3px; padding: 0px; vertical-align: middle;" />'
            .'<span style="display: inline-block; padding: 0px 10px 0px 5px; vertical-align: middle;">'
                .'<strong style="letter-spacing: 30pt; font-size: 2.5em;">'.SYSTEM_SHORT.'</strong><br />'
                .'<small>'.SYSTEM_NAME.'</small>'
            .'</span>'
        .'</a>'
        .'</span>'
        .$adminBlock
        .$itemsBlock
        .$ownersBlock
        .$reportsBlock
        .$archivesBlock
        .'<span id="user">'
        .$loginV->renderForm()
        .'</span>'
        .'</div>';

    if ( !$echo ) return $navigation;
    echo $navigation;
} //renderNavigation






/**
 * Page errors
 */
public function pageError ($type) {
    $this->getHeader();
    switch ( $type ) {
        case '403':
            require_once(ERRORS_DIR.DS.'403.php');
            break;


        case '404':
            require_once(ERRORS_DIR.DS.'404.php');
            break;


        case 'loginError':
            require_once(ERRORS_DIR.DS.'loginError.php');
            break;


        case 'underconstruction':
            require_once(ERRORS_DIR.DS.'underconstruction.php');
            break;


        default:
            require_once(ERRORS_DIR.DS.'unknown.php');
    }
    $this->getFooter();
} //pageError

}
