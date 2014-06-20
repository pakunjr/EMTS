<?php

class itemView {

private $model;
private $controller;


/**
 * Variables used for forms
 */
private $selectList;
private $descriptionList;


/**
 * Variables used for the ownertype
 */
private $ownerTypeSelectList;
private $ownerTypeDescriptionList;

private $fcs; //form object for single-item form
private $fcp; //form object for package form





public function __construct ($model) {
    $this->model = $model;
    $this->controller = new itemController($this->model);

    /**
     * Initialize item types
     * Consumable, Devices, Tools, and Necessities
     */
    $itemTypeM = new itemTypeModel();
    $itemTypeV = new itemTypeView($itemTypeM);
    $itemTypeC = new itemTypeController($itemTypeM);

    $itemTypeC->generateList();
    $this->selectList = $itemTypeM->data('list');
    $itemTypeC->generateDescriptionList();
    $this->descriptionList = $itemTypeM->data('list');
    $this->descriptionList = $itemTypeV->renderInstructions($this->descriptionList);


    /**
     * Initialize owner types
     */
    $ownerTypeM = new ownerTypeModel();
    $ownerTypeV = new ownerTypeView($ownerTypeM);
    $ownerTypeC = new ownerTypeController($ownerTypeM);

    $ownerTypeC->generateList();
    $this->ownerTypeSelectList = $ownerTypeM->data('list');

    $ownerTypeC->generateDescriptionList();
    $this->ownerTypeDescriptionList = $ownerTypeM->data('list');
    $this->ownerTypeDescriptionList = $ownerTypeV->renderInstructions();


    /**
     * Initialize form objects
     */
    $this->fcs = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));

    $this->fcp = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));
} //__construct




/**
 * Render item page
 */
public function renderPage ($controller, $action=null, $extra=null) {
    $lm = new loginModel();
    $lc = new loginController($lm);

    if ( !$lm->data('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case null:
            $GLOBALS['pageView']->getHeader();
            $this->renderFrontpage(true);
            $GLOBALS['pageView']->getFooter();
            break;


        case 'new_item':
            switch ( $action ) {
                case null:
                    $GLOBALS['pageView']->getHeader();
                    $this->renderForm('create', true);
                    $GLOBALS['pageView']->getFooter();
                    break;


                case 'save':
                    $GLOBALS['pageView']->getHeader();
                    $this->displaySentVariables();
                    $GLOBALS['pageView']->getFooter();
                    break;



                default:
                    $GLOBALS['pageView']->pageError('404');
            }
            break;


        case 'update_item':
            $GLOBALS['pageView']->getHeader();
            switch ( $action ) {
                case null:
                    require_once(FORMS_DIR.DS.'item'.DS.'itemUpdate.php');
                    break;

                default:
                    $GLOBALS['pageView']->pageError('404');
            }
            $GLOBALS['pageView']->getFooter();
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage




/**
 * Generate and return single item form
 */
public function renderForm ($action='create', $echo=false) {

    $fcs = $this->fcs;
    $selectList = $this->selectList;
    $descriptionList = $this->descriptionList;

    if ( $action == 'create' ) {
        $singleItemForm = '<div id="form-container-single">'
            .$fcs->openForm(array(
                'id'    => 'new-single-item-form'
                ,'method'   => 'post'
                ,'action'   => URL_BASE.'items/new_item/save/'
                ,'enctype'  => 'multipart/form-data'
            ))
            .$fcs->openFieldset(array('legend'=>'Single Item Form'))
            .'<div class="row">'
                .'<span class="column">'
                .$fcs->text(array('id'=>'single-item-serial-no','label'=>'Serial No.'))
                .$fcs->text(array('id'=>'single-item-model-no','label'=>'Model No.'))
                .$fcs->text(array('id'=>'single-item-name','label'=>'Item Name'))
                .'</span>'


                .'<span class="column">'
                .$fcs->textarea(array('id'=>'single-item-description','label'=>'Item Description'))
                .'<div class="note" data-for="single-item-description">'
                    .'Please indicate the specifications of the item in the description box.<br />'
                    .'<hr />'
                    .'Examples are given below:<br /><small>(Please note that these are just examples)</small><br /><br />'

                    .'<u>for a Thumbdrive:</u>'
                    .'<div style="margin-top: 5px; padding-left: 10px;">'
                        .'Color: Red and Black<br />'
                        .'Brand: SanDisk<br />'
                        .'Capacity: 16 GB'
                    .'</div><br />'

                    .'<u>for a Bond Paper:</u>'
                    .'<div style="margin-top: 5px; padding-left: 10px;">'
                        .'Texture / Color: Plain white<br />'
                        .'Size: A4<br />'
                        .'300 pcs.'
                    .'</div><br />'

                    .'<u>for an Office Table</u>'
                    .'<div style="margin-top: 5px; padding-left: 10px;">'
                        .'Texture / Color: Wooden brown<br />'
                        .'Dimensions: 24\'\' H x 24\'\' W x 36\'\' D<br />'
                        .'No. of Drawers: 1 long and 3 short'
                    .'</div>'
                .'</div>'
                .$fcs->text(array('id'=>'single-item-date-purchase','label'=>'Date of Purchase','class'=>'datepicker'))
                .'</span>'

                .'<span class="column">'
                .$fcs->select(array('id'=>'single-item-type','label'=>'Item Type','select_options'=>$selectList))
                .'<div class="note" data-for="single-item-type">'.$descriptionList.'</div>'
                .$fcs->hidden(array('id'=>'single-item-package-search-id'))
                .$fcs->text(array('id'=>'single-item-package-search','label'=>'Search Package'))
                .'<div class="note" data-for="single-item-package-search">If this item belongs to a package, search the package through here, if the package is missing, please add it using the <a href="'.URL_BASE.'packages/new/">package form</a>.<br /><br />Press `Esc` key to automatically clear the search box.</div>'
                .'<div class="search-results hidden" data-search="single-item-package-search" data-result="single-item-package-search-id" data-url="'.URL_BASE.'packages/search/"></div>'
                .'</span>'
            .'</div>'
            .$fcs->closeFieldset()

            /**
             * Ownership type
             */
            .$fcs->openFieldset(array('legend'=>'Owner Details'))
            .'<div class="row">'
                .'<span class="column">'
                .$fcs->select(array('id'=>'owner-type','label'=>'Owner Type','select_options'=>array(
                        'Employee'      => 'employee'
                        ,'Department'   => 'department'
                        ,'Guest'        => 'guest'
                    )))
                .'<div class="note" data-for="owner-type">'.$this->ownerTypeDescriptionList.'</div>'
                .'</span>'
            .'</div>'

            /**
             * Employee form
             */
            .'<div id="owner-type-employee-form" class="row owner-type-form">'
                .'<span class="column">'
                .$fcs->hidden(array('id'=>'person-search-id'))
                .$fcs->text(array('id'=>'person-search','label'=>'Search Owner'))
                .'<div class="note" data-for="person-search">Type in either firstname, middlename, or lastname and a dropdown list will show up matching your search query of the person.<br /><br />Press Esc key to automatically clear the search box.</div>'
                .'<div class="search-results hidden" data-search="person-search" data-url="'.URL_BASE.'persons/search/" data-result="person-search-id"></div>'
                .'</span>'
            .'</div>'

            /**
             * Department form
             */
            .'<div id="owner-type-department-form" class="row owner-type-form hidden">'
                .'<span class="column">'
                .$fcs->hidden(array('id'=>'department-search-id'))
                .$fcs->text(array('id'=>'department-search','label'=>'Search Department'))
                .'<div class="note" data-for="department-search">Type in either the acronym of the department, or a part of the name of the department and a dropdown list will show up matching your search query of the department.<br /><br />Press Esc key to automatically clear the search box.</div>'
                .'<div class="search-results hidden" data-search="department-search" data-url="'.URL_BASE.'departments/search/" data-result="department-search-id"></div>'
                .'</span>'
            .'</div>'

            /**
             * Guest form
             */
            .'<div id="owner-type-guest-form" class="row owner-type-form hidden">'
                .'<span class="column">'
                .$fcs->text(array('id'=>'guest-firstname','label'=>'Firstname'))
                .$fcs->text(array('id'=>'guest-middlename','label'=>'Middlename'))
                .$fcs->text(array('id'=>'guest-lastname','label'=>'Lastname'))
                .$fcs->text(array('id'=>'guest-suffix','label'=>'Suffix'))
                .'</span>'
                .'<span class="column">'
                .$fcs->text(array('id'=>'guest-home-address','label'=>'Home Address'))
                .$fcs->text(array('id'=>'guest-current-address','label'=>'Current Address'))
                .$fcs->text(array('id'=>'guest-mobile-no-a','label'=>'Mobile No. 1'))
                .$fcs->text(array('id'=>'guest-mobile-no-b','label'=>'Mobile No. 2'))
                .'</span>'
                .'<span class="column">'
                .$fcs->text(array('id'=>'guest-tel-no','label'=>'Telephone No.'))
                .$fcs->text(array('id'=>'guest-email-address','label'=>'Email Address'))
                .$fcs->select(array('id'=>'guest-gender','label'=>'Gender','select_options'=>array(
                        'Male'      => 'm'
                        ,'Female'   => 'f'
                    )))
                .$fcs->text(array('id'=>'guest-birthdate','label'=>'Birthdate','class'=>'datepicker'))
                .'</span>'
            .'</div>'


            .$fcs->closeFieldset()
            .$fcs->submit(array('value'=>'Save item','auto_line_break'=>false))
            .$fcs->closeForm()
            .'</div>'
            .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';
    } else {
        $GLOBALS['pageView']->pageError('404');
        return false;
    }


    if ( !$echo ) return $singleItemForm;
    echo $singleItemForm;

} //renderForm






/**
 * Render the frontpage
 */
public function renderFrontpage ($echo=false) {
    $frontpage = '<div class="frontpage-title">Items</div>'
        .'<a class="frontpage-option" href="'.URL_BASE.'items/new_item/">'
        .'<div class="frontpage-option-image"><img src="'.URL_TEMPLATE.'img/items_frontpageOptionImage_newItem.jpg" /></div>'
        .'<div class="frontpage-option-name">New Item/s</div>'
        .'</a>'


        .'<a class="frontpage-option" href="'.URL_BASE.'items/update_item/">'
        .'<div class="frontpage-option-image"><img src="'.URL_TEMPLATE.'img/items_frontpageOptionImage_updateItem.jpg" /></div>'
        .'<div class="frontpage-option-name">Update an Item</div>'
        .'</a>';

    if ( !$echo ) return $frontpage;
    echo $frontpage;
} //renderFrontpage






/**
 * Display sent variables either POST or GET
 */
public function displaySentVariables () {
    echo '<table>'
        ,'<tr>'
            ,'<th>Variable Type</th>'
            ,'<th>Label</th>'
            ,'<th>Value</th>'
        ,'</tr>';

    if ( isset($_POST) ) {
        foreach ( $_POST as $label => $value ) {
            echo '<tr>'
                ,'<td>POST</td>'
                ,'<td>',$label,'</td>'
                ,'<td>',$value,'</td>'
                ,'</tr>';
        }
    } else if ( isset($_GET) ) {
        foreach ( $_GET as $label => $value ) {
            echo '<tr>'
                ,'<td>GET</td>'
                ,'<td>',$label,'</td>'
                ,'<td>',$value,'</td>'
                ,'</tr>';
        }
    } else {
        echo 'There are no sent variables';
    }

    echo '</table>';
} //displaySentVariables


    
}
