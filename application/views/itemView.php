<?php

class itemView {

private $model;
private $controller;



public function __construct ($model) {
    $this->model = $model;
    $this->controller = new itemController($this->model);
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
                    $results = $this->controller->createItem($_POST);
                    $this->renderSaveResults($results, true);
                    break;



                default:
                    $GLOBALS['pageView']->pageError('404');
            }
            break;




        case 'view_all':
            $GLOBALS['pageView']->getHeader();
            $this->viewAll($action, true);
            $GLOBALS['pageView']->getFooter();
            break;



        case 'view':
            $GLOBALS['pageView']->getHeader();
            $this->renderItemInformation($action, true);
            $GLOBALS['pageView']->getFooter();
            break;



        case 'update_item':
            if ( $action == 'save' ) {
                $result = $this->controller->updateItem($_POST);
                $this->renderUpdateResults($result);
            } else {
                $GLOBALS['pageView']->getHeader();
                $this->renderForm('update', true, $action);
                $GLOBALS['pageView']->getFooter();
            }
            break;



        case 'delete_item':
            $this->controller->archiveItem($action);
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage




















/**
 * Generate and return single item form
 */
public function renderForm ($action='create', $echo=false, $id=null) {
    if ( $action == 'create' )
        $formHead = '<b style="font-size: 1.5em;">New Item</b><hr />';
    else if ( $action == 'update' )
        $formHead = '<b style="font-size: 1.5em;">Update Item</b><hr />';

    $errM = new errorModel();
    $errC = new errorController($errM);

    $dbM = new databaseModel();
    $dbC = new databaseController($dbM);

    $fcs = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));

    $itemStateM = new itemStateModel();
    $itemStateV = new itemStateView($itemStateM);

    $itemTypeM = new itemTypeModel();
    $itemTypeV = new itemTypeView($itemTypeM);

    $ownerTypeM = new ownerTypeModel();
    $ownerTypeV = new ownerTypeView($ownerTypeM);
    $ownerTypeC = new ownerTypeController($ownerTypeM);

    $ownshpM = new ownershipModel();
    $ownshpC = new ownershipController($ownshpM);

    $packageM = new packageModel();
    $packageC = new packageController($packageM);




    if ( $action == 'update' ) {

        if ( $id === null ) {
            echo 'Error: Failed to update the item, no passed ID.';
            $errC->logError('Failed to show update form, no item ID passed');
            return false;
        }
        $action = URL_BASE.'items/update_item/save/';
        $itemID = $id;

        $results = $dbC->PDOStatement(array(
            'query' => "SELECT
                    items.item_serial_no
                    ,items.item_model_no
                    ,items.item_name
                    ,items.item_type
                    ,items.item_state
                    ,items.item_description
                    ,items.quantity
                    ,items.quantity_unit
                    ,items.date_of_purchase
                    ,items.package_id

                    ,specs.id AS specsID
                    ,specs.processor
                    ,specs.video
                    ,specs.display
                    ,specs.webcam
                    ,specs.audio
                    ,specs.network
                    ,specs.usb_ports
                    ,specs.memory
                    ,specs.storage
                    ,specs.os
                    ,specs.software
                FROM tbl_items AS items
                LEFT JOIN tbl_items_specification AS specs ON items.item_id = specs.item_id
                WHERE items.item_id = ?
                LIMIT 1"
            ,'values'   => array(array('int', $itemID))
            ));
        $row = $results[0];

        $itemSerialNo = $row['item_serial_no'];
        $itemModelNo = $row['item_model_no'];
        $itemName = $row['item_name'];
        $itemType = $row['item_type'];
        $itemState = $row['item_state'];
        $itemDescription = $row['item_description'];
        $quantity = $row['quantity'];
        $quantityUnit = $row['quantity_unit'];
        $dateOfPurchase = $row['date_of_purchase'];
        $packageID = $row['package_id'];
        $packageName = $packageID != '' ?
            $packageC->getPackageName($packageID) : '';

        $itemSpecsID = $row['specsID'];
        $itemSpecsProcessor = $row['processor'];
        $itemSpecsVideo = $row['video'];
        $itemSpecsDisplay = $row['display'];
        $itemSpecsWebcam = $row['webcam'];
        $itemSpecsAudio = $row['audio'];
        $itemSpecsNetwork = $row['network'];
        $itemSpecsUSBPorts = $row['usb_ports'];
        $itemSpecsMemory = $row['memory'];
        $itemSpecsStorage = $row['storage'];
        $itemSpecsOS = $row['os'];
        $itemSpecsSoftware = $row['software'];

        $ownshpResult = $dbC->PDOStatement(array(
            'query' => "SELECT
                    ownership_id
                    ,owner_id
                    ,owner_type
                    ,date_of_possession
                FROM tbl_ownerships
                WHERE
                    item_id = ?
                    AND date_of_release = ?
                LIMIT 1"
            ,'values'    => array(
                    array('int', $itemID)
                    ,'0000-00-00'
                )
            ));
        $ownshpResult = count($ownshpResult) > 0 ?
            $ownshpResult[0] : null;

        if ( $ownshpResult != null ) {
            $ownershipID = $ownshpResult['ownership_id'];
            $ownerID = $ownshpResult['owner_id'];
            $ownerType = $ownshpResult['owner_type'];
            $ownerTypeLabel = $ownerTypeC->decodeID($ownerType);
            $ownerName = $ownshpC->getOwnerName($ownershipID);
        } else {
            $ownershipID = '';
            $ownerID = '';
            $ownerType = $ownerTypeC->decodeLabel('None');
            $ownerTypeLabel = '';
            $ownerName = '';
        }



        $ownerEmpID = $ownerTypeLabel == 'Employee' ?
            $ownerID : '';
        $ownerEmpName = $ownerTypeLabel == 'Employee' ?
            $ownerName : '';

        $ownerDeptID = $ownerTypeLabel == 'Department' ?
            $ownerID : '';
        $ownerDeptName = $ownerTypeLabel == 'Department' ?
            $ownerName : '';

        $ownerGuestID = $ownerTypeLabel == 'Guest' ?
            $ownerID : '';
        $ownerGuestName = $ownerTypeLabel == 'Guest' ?
            $ownerName : '';

        $dateOfPossession = $ownshpResult['date_of_possession'];

        $submitBtnValue = 'Update Item';

    } else {

        $action = URL_BASE.'items/new_item/save/';

        $itemID = '';

        $itemSerialNo = '';
        $itemModelNo = '';
        $itemName = '';
        $itemType = '';
        $itemState = '';
        $itemDescription = '';
        $quantity = '1';
        $quantityUnit = 'pc.';
        $dateOfPurchase = date('Y-m-d');
        $packageID = '';
        $packageName = '';

        $itemSpecsID = '';
        $itemSpecsProcessor = '';
        $itemSpecsVideo = '';
        $itemSpecsDisplay = '';
        $itemSpecsWebcam = '';
        $itemSpecsAudio = '';
        $itemSpecsNetwork = '';
        $itemSpecsUSBPorts = '';
        $itemSpecsMemory = '';
        $itemSpecsStorage = '';
        $itemSpecsOS = '';
        $itemSpecsSoftware = '';

        $ownershipID = '';
        $ownerType = '';
        $ownerEmpID = '';
        $ownerEmpName = '';
        $ownerDeptID = '';
        $ownerDeptName = '';
        $ownerGuestID = '';
        $ownerGuestName = '';
        $dateOfPossession = '';

        $submitBtnValue = 'Save Item';

    }





    $singleItemForm = $formHead.'<div id="form-container-single">'
        .$fcs->openForm(array(
            'id'    => 'new-single-item-form'
            ,'method'   => 'post'
            ,'action'   => $action
            ,'enctype'  => 'multipart/form-data'
        ))
        .$fcs->hidden(array('id'=>'item-id','value'=>$itemID))
        .$fcs->openFieldset(array('legend'=>'Item Information'))
        .'<div class="row">'
            .'<span class="column">'
            .$fcs->text(array('id'=>'single-item-serial-no','label'=>'Serial No.','value'=>$itemSerialNo))
            .$fcs->text(array('id'=>'single-item-model-no','label'=>'Model No.','value'=>$itemModelNo))
            .$fcs->text(array('id'=>'single-item-quantity','label'=>'Quantity','class'=>'numeric','value'=>$quantity))
            .$fcs->text(array('id'=>'single-item-quantity-unit','label'=>'Quantity-Unit','value'=>$quantityUnit))
            .'</span><!-- .column -->'


            .'<span class="column">'
            .$fcs->text(array('id'=>'single-item-name','label'=>'Item Name','value'=>$itemName))
            .$fcs->select(array('id'=>'single-item-state','label'=>'Item State','select_options'=>$itemStateV->generateSelectOptions(),'default_option'=>$itemState))
            .'<div class="note" data-for="single-item-state">'.$itemStateV->generateNote().'</div>'
            .$fcs->textarea(array('id'=>'single-item-description','label'=>'Item Description','value'=>$itemDescription))
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
            .'</span><!-- .column -->'


            .'<span class="column">'
            .$fcs->text(array('id'=>'single-item-date-purchase','label'=>'Date of Purchase','class'=>'datepicker','value'=>$dateOfPurchase))
            .$fcs->select(array('id'=>'single-item-type','label'=>'Item Type','select_options'=>$itemTypeV->generateSelectOptions(),'default_option'=>$itemType))
            .'<div class="note" data-for="single-item-type">'.$itemTypeV->generateNote().'</div>'
            .$fcs->hidden(array('id'=>'single-item-package-search-id','value'=>$packageID))
            .$fcs->text(array('id'=>'single-item-package-search','label'=>'Search Package','value'=>$packageName))
            .'<div class="note" data-for="single-item-package-search">If this item belongs to a package, search the package through here, if the package is missing, please add it using the <a href="'.URL_BASE.'packages/new/">package form</a>.<br /><br />Press `Esc` key to automatically clear the search box.</div>'
            .'<div class="search-results hidden" data-search="single-item-package-search" data-result="single-item-package-search-id" data-url="'.URL_BASE.'packages/search/"></div>'
            .'</span><!-- .column -->'
        .'</div><!-- .row -->'
        .$fcs->closeFieldset()


        /**
         * Item specification
         */
        .$fcs->openFieldset(array('id'=>'single-item-specification-form','class'=>'hidden','legend'=>'Item Specifications'))
        .'<div class="row">'
            .'<span class="column">'
            .$fcs->text(array('id'=>'item-specs-processor','label'=>'Processor','value'=>$itemSpecsProcessor))
            .$fcs->text(array('id'=>'item-specs-video','label'=>'Video','value'=>$itemSpecsVideo))
            .$fcs->text(array('id'=>'item-specs-display','label'=>'Display','value'=>$itemSpecsDisplay))
            .$fcs->text(array('id'=>'item-specs-webcam','label'=>'Webcam','value'=>$itemSpecsWebcam))
            .$fcs->text(array('id'=>'item-specs-audio','label'=>'Audio','value'=>$itemSpecsAudio))
            .'</span>'


            .'<span class="column">'
            .$fcs->text(array('id'=>'item-specs-network','label'=>'Network','value'=>$itemSpecsNetwork))
            .$fcs->text(array('id'=>'item-specs-usbports','label'=>'USB Ports','value'=>$itemSpecsUSBPorts))
            .$fcs->text(array('id'=>'item-specs-memory','label'=>'Memory','value'=>$itemSpecsMemory))
            .$fcs->textarea(array('id'=>'item-specs-storage','label'=>'Storage','value'=>$itemSpecsStorage))
            .'</span>'


            .'<span class="column">'
            .$fcs->text(array('id'=>'item-specs-os','label'=>'Operating System','value'=>$itemSpecsOS))
            .$fcs->textarea(array('id'=>'item-specs-software','label'=>'Other Software','value'=>$itemSpecsSoftware))
            .'</span>'
        .'</div>'
        .$fcs->closeFieldset()


        /**
         * Ownership type
         */
        .$fcs->openFieldset(array('legend'=>'Owner Details'))
        .$fcs->hidden(array('id'=>'ownership-id','value'=>$ownershipID))
        .'<div class="row">'
            .'<span class="column">'
            .$fcs->select(array('id'=>'owner-type','label'=>'Owner Type','select_options'=>$ownerTypeV->generateSelectOptions(),'default_option'=>$ownerType))
            .'<div class="note" data-for="owner-type">'.$ownerTypeV->generateNote().'</div>'
            .'</span>'
        .'</div>'

        /**
         * Employee form
         */
        .'<div id="owner-type-employee-form" class="row owner-type-form">'
            .'<span class="column">'
            .$fcs->hidden(array('id'=>'person-search-id','value'=>$ownerEmpID))
            .$fcs->text(array('id'=>'person-search','label'=>'Search Owner','value'=>$ownerEmpName))
            .'<div class="note" data-for="person-search">Type in either firstname, middlename, or lastname and a dropdown list will show up matching your search query of the person. If the person doesn\'t exists in the system, please use this <a href="'.URL_BASE.'persons/registration" target="_blank">employee registration form</a>.<br /><br />Press Esc key to automatically clear the search box.</div>'
            .'<div class="search-results hidden" data-search="person-search" data-url="'.URL_BASE.'persons/search_employee/" data-result="person-search-id"></div>'
            .'</span>'
        .'</div>'

        /**
         * Department form
         */
        .'<div id="owner-type-department-form" class="row owner-type-form hidden">'
            .'<span class="column">'
            .$fcs->hidden(array('id'=>'department-search-id','value'=>$ownerDeptID))
            .$fcs->text(array('id'=>'department-search','label'=>'Search Department','value'=>$ownerDeptName))
            .'<div class="note" data-for="department-search">Type in either the acronym of the department, or a part of the name of the department and a dropdown list will show up matching your search query of the department.<br /><br />Press Esc key to automatically clear the search box.</div>'
            .'<div class="search-results hidden" data-search="department-search" data-url="'.URL_BASE.'departments/search/" data-result="department-search-id"></div>'
            .'</span>'
        .'</div>'

        /**
         * Guest form
         */
        .'<div id="owner-type-guest-form" class="row owner-type-form hidden">'
            .'<span class="column">'
            .$fcs->hidden(array('id'=>'guest-search-id','value'=>$ownerGuestID))
            .$fcs->text(array('id'=>'guest-search', 'label'=>'Search Guest','value'=>$ownerGuestName))
            .'<div class="note" data-for="guest-search">Type in either firstname, middlename, or lastname and a dropdown list will show up matching your search query of the person. If the person doesn\'t exists in the system, please use this <a href="'.URL_BASE.'persons/registration/guest" target="_blank">guest registration form</a>.<br /><br />Press Esc key to automatically clear the search box.</div>'
            .'<div class="search-results hidden" data-search="guest-search" data-url="'.URL_BASE.'persons/search_guest/" data-result="guest-search-id"></div>'
            .'</span>'
        .'</div>'


        .$fcs->text(array('id'=>'owner-date-of-possession','class'=>'datepicker','label'=>'Date of Possession','value'=>date('Y-m-d')))


        .$fcs->closeFieldset()
        .$fcs->submit(array('value'=>$submitBtnValue,'auto_line_break'=>false))
        .$fcs->closeForm()
        .'</div>'
        .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';


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



        .'<a class="frontpage-option" href="'.URL_BASE.'items/view_all/">'
        .'<div class="frontpage-option-image"></div>'
        .'<div class="frontpage-option-name">View Items</div>'
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


















/**
 * Render save results
 */
public function renderSaveResults ($results=array(), $echo=false) {

    /**
     * Redirect the user to the single item view to avoid
     * double entry of same item (when refreshing the page)
     * if redirection is not present
     */
    header('location: '.URL_BASE.'items/view/'.$results['itemID'].'/');
    exit(); //This is to stop further execution of the script / page



    $itemResult = $results['item'] == '1' ?
        'Success' : 'Failed';


    if ( $results['ownership'] == '1' )
        $ownershipResult = 'Success';
    else if ( $results['ownership'] == 'n/a' )
        $ownershipResult = 'Not Applicable';
    else
        $ownershipResult = 'Failed';



    if ( $results['specification'] == '1' )
        $specificationResult = 'Success';
    else if ( $results['specification'] == 'n/a' )
        $specificationResult = 'Not Applicable';
    else
        $specificationResult = 'Failed';


    $output = '<div>'
        .'Saving results:<hr />'
        .'Item: '.$itemResult.'<br />'
        .'Ownership: '.$ownershipResult.'<br />'
        .'Specifications: '.$specificationResult.'<br /><br />'
        .'<a href="'.URL_BASE.'items/view/'.$results['itemID'].'/">View ITEM</a>'
        .'</div>';

    if ( !$echo ) return $output;
    echo $output;
} //renderSaveResults



public function renderUpdateResults ($result) {
    if ( $result === false )
        echo 'Something went wrong and the item failed to update.';

    header('location: '.URL_BASE.'items/view/'.$result.'/');
} //renderUpdateResults


















/**
 * List all items
 */
public function viewAll ($page='1', $echo=false) {
    $this->controller->readAll();
    $itemList = $this->model->data('itemList');

    $form = new form(array(
            'auto_line_break'   => false
            ,'auto_label'       => true
        ));

    $filter = '<b style="font-size: 1.5em;">Items <small>( Click on an item to view its information )</small></b><hr />'
        .'<div id="item-view-filter">'
        .$form->openForm(array(
                'id'    => 'item-view-filter-form'
                ,'method'   => 'get'
                ,'action'   => ''
                ,'enctype'  => 'multipart/form-data'
            ))
        .$form->select(array('id'=>'item-filter-by','label'=>'Filter by','select_options'=>array(
                'Item Type' => 'type'
                ,'Owner'    => 'owner'
                ,'Package'  => 'package'
            )))
        .$form->select(array('id'=>'item-filter-item-type','auto_label'=>false))


        .$form->text(array('id'=>'item-filter-item-owner','auto_label'=>false))
        .$form->hidden(array('id'=>'item-filter-item-owner-id'))
        .'<div class="search-results hidden" data-search="item-filter-item-owner" data-url="'.URL_BASE.'owners/search/" data-result="item-filter-item-owner-id"></div>'


        .$form->text(array('id'=>'item-filter-item-package','auto_label'=>false))
        .$form->hidden(array('id'=>'item-filter-item-package-id'))
        .'<div class="search-results hidden" data-search="item-filter-item-package" data-url="'.URL_BASE.'packages/search/" data-result="item-filter-item-package-id"></div>'


        .$form->submit(array('value'=>'Filter'))
        .$form->closeForm()
        .'</div>';




    if ( !is_array($itemList) ) {
        $output = 'There are no items.';
        if ( !$echo ) return $output;
        echo $output;
        return false;
    }

    $output = $filter.'<table id="item-list" class="tabular-view">'
        .'<tr>'
            .'<th>'
                .'Item<br />'
                .'<span style="display: inline-block; text-align: left;">'
                .'<small style="color: #f00;">Serial No.</small><br />'
                .'<small style="color: #03f;">Model No.</small>'
                .'</span>'
            .'</th>'
            .'<th>Type</th>'
            .'<th>Quantity</th>'
            .'<th>State</th>'
            .'<th>Description</th>'
            .'<th>Date of Purchase</th>'
            .'<th>Owner</th>'
            .'<th>'
                .'Package<br />'
                .'<small style="color: #053;">Serial No.</small>'
            .'</th>'
            .'<th>Item Status</th>'
            .'<th>Actions</th>'
        .'</tr>';

    foreach ( $itemList as $infos ) {
        $archiveStatus = $infos['itemArchiveStatus'] == '0' ?
            '<small style="color: #053;">Item OK</small>'
            : '<small style="color: #f00;">Item no longer exist.</small>';

        $output .= '<tr class="item-data" data-id="'.$infos['itemID'].'" data-url="'.URL_BASE.'items/view/'.$infos['itemID'].'">'
            .'<td>'
                .$infos['itemName'].'<br />'
                .'<small style="color: #f00;">'.$infos['itemSerialNo'].'</small><br />'
                .'<small style="color: #03f;">'.$infos['itemModelNo'].'</small>'
            .'</td>'
            .'<td>'.$infos['itemType'].'</td>'
            .'<td>'.$infos['itemQuantity'].' '.$infos['itemQuantityUnit'].'</td>'
            .'<td>'.$infos['itemState'].'</td>'
            .'<td>'.nl2br($infos['itemDescription']).'</td>'
            .'<td>'.$infos['itemDOP'].'</td>'
            .'<td>'.$infos['itemOwner'].'</td>'
            .'<td>'
                .$infos['packageName'].'<br />'
                .'<small style="color: #053;">'.$infos['packageSerialNo'].'</small>'
            .'</td>'
            .'<td>'.$archiveStatus.'</td>'
            .'<td>'
                .'<a class="update-button" href="'.URL_BASE.'items/update_item/'.$infos['itemID'].'/"><input type="button" value="Update" /></a>'
                .'<a class="delete-button" href="'.URL_BASE.'items/delete_item/'.$infos['itemID'].'/"><input class="btn-red" type="button" value="Delete" /></a>'
            .'</td>'
            .'</tr>';
    }

    $output .= '</table>'
        .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';

    if ( !$echo ) return $output;
    echo $output;
} //viewAll















/**
 * Render single item information for viewing
 */
public function renderItemInformation ($itemID, $echo=false) {
    $this->controller->readItem($itemID);

    if ( !$this->model->data('itemExists') ) {
        $output = 'Error: This item never exists.<br /><br />'
            .'<a href="'.URL_BASE.'items/view_all/">View Item/s</a>';


        if ( !$echo ) return $output;
        echo $output;
        return false;
    }

    $archiveNotice = '';
    if ( $this->model->data('itemArchiveStatus') == '1' ) {
        $archiveNotice = '<div style="color: #f00;">This item no longer exists as it has been archived in the system.</div><br /><hr />';
    }
    
    /**
     * Display item information
     */
    $output = $archiveNotice.'Item Information<hr />'
        .'<div class="row" style="font-size: 0.95em;">'
            .'<table class="tabular-view">'
            .'<tr>'
                .'<th>Serial No.</th>'
                .'<th>Model No.</th>'
                .'<th>Name</th>'
                .'<th>Type</th>'
                .'<th>Quantity</th>'
                .'<th>Current Owner</th>'
                .'<th>State</th>'
                .'<th>Description</th>'
                .'<th>Date of Purchase</th>'
            .'</tr>'
            .'<tr>'
                .'<td>'.$this->model->data('itemSerialNo').'</td>'
                .'<td>'.$this->model->data('itemModelNo').'</td>'
                .'<td>'.$this->model->data('itemName').'</td>'
                .'<td>'.$this->model->data('itemType').'</td>'
                .'<td>'.$this->model->data('itemQuantity').' '.$this->model->data('itemQuantityUnit').'</td>'
                .'<td>'.$this->model->data('currentOwner').'</td>'
                .'<td>'.$this->model->data('itemState').'</td>'
                .'<td>'.nl2br($this->model->data('itemDescription')).'</td>'
                .'<td>'.$this->model->data('itemDateOfPurchase').'</td>'
            .'</tr>'
            .'</table>'
        .'</div>';

    /**
     * Display item specification if
     * the item is categorized as a
     * device and the specifications
     * have been set
     */
    if ( $this->model->data('itemType') == 'Devices' && (
            $this->model->data('itemSpecsProcessor') != ''
            && $this->model->data('itemSpecsVideo') != ''
            && $this->model->data('itemSpecsDisplay') != ''
            && $this->model->data('itemSpecsWebcam') != ''
            && $this->model->data('itemSpecsAudio') != ''
            && $this->model->data('itemSpecsNetwork') != ''
            && $this->model->data('itemSpecsUSBPorts') != ''
            && $this->model->data('itemSpecsMemory') != ''
            && $this->model->data('itemSpecsStorage') != ''
            && $this->model->data('itemSpecsOS') != ''
            && $this->model->data('itemSpecsSoftware') != ''
        ) ) {
        $output .= '<br />Item Specifications<hr />'
            .'<div class="row" style="font-size: 0.9em;">'
                .'<table class="tabular-view">'
                .'<tr>'
                    .'<th>Processor</th>'
                    .'<th>Video</th>'
                    .'<th>Display</th>'
                    .'<th>Webcam</th>'
                    .'<th>Audio</th>'
                    .'<th>Network</th>'
                    .'<th>USBPorts</th>'
                    .'<th>Memory</th>'
                    .'<th>Storage</th>'
                    .'<th>OS</th>'
                    .'<th>Software</th>'
                .'</tr>'
                .'<tr>'
                    .'<td>'.$this->model->data('itemSpecsProcessor').'</td>'
                    .'<td>'.$this->model->data('itemSpecsVideo').'</td>'
                    .'<td>'.$this->model->data('itemSpecsDisplay').'</td>'
                    .'<td>'.$this->model->data('itemSpecsWebcam').'</td>'
                    .'<td>'.$this->model->data('itemSpecsAudio').'</td>'
                    .'<td>'.$this->model->data('itemSpecsNetwork').'</td>'
                    .'<td>'.$this->model->data('itemSpecsUSBPorts').'</td>'
                    .'<td>'.$this->model->data('itemSpecsMemory').'</td>'
                    .'<td>'.nl2br($this->model->data('itemSpecsStorage')).'</td>'
                    .'<td>'.$this->model->data('itemSpecsOS').'</td>'
                    .'<td>'.nl2br($this->model->data('itemSpecsSoftware')).'</td>'
                .'</tr>'
                .'</table>'
            .'</div>';
    }

    /**
     * Display package information if
     * the item belongs to a package
     */
    if ( $this->model->data('packageID') != ''
            || $this->model->data('packageID') != null ) {
        $output .= '<br />Package Information<hr />'
            .'<div class="row" style="font-size: 0.9em;">'
                .'<table class="tabular-view">'
                .'<tr>'
                    .'<th>Name</th>'
                    .'<th>Serial No.</th>'
                    .'<th>Description</th>'
                    .'<th>Date of Purchase</th>'
                .'</tr>'
                .'<tr>'
                    .'<td>'.$this->model->data('packageName').'</td>'
                    .'<td>'.$this->model->data('packageSerialNo').'</td>'
                    .'<td>'.nl2br($this->model->data('packageDescription')).'</td>'
                    .'<td>'.$this->model->data('packageDateOfPurchase').'</td>'
                .'</tr>'
                .'</table>'
            .'</div>';
    }


    /**
     * Display ownership information / history
     */
    $ownershipList = $this->model->data('ownershipList');
    if ( is_array($ownershipList) ) {
        $output .= '<br />Ownership History<hr />'
            .'<div class="row">'
            .'<table class="tabular-view">'
            .'<tr>'
                .'<th>ID</th>'
                .'<th>Owner Type</th>'
                .'<th>Owner</th>'
                .'<th>Item Status</th>'
                .'<th>Date of Possession</th>'
                .'<th>Date of Release</th>'
                .'<th>Ownership Status</th>'
            .'</tr>';

        foreach ( $ownershipList as $ownership ) {
            $output .= '<tr>'
                .'<td>'.$ownership['ID'].'</td>'
                .'<td>'.$ownership['ownerType'].'</td>'
                .'<td>'.$ownership['itemOwner'].'</td>'
                .'<td>'.$ownership['itemStatus'].'</td>'
                .'<td>'.$ownership['dateOfPossession'].'</td>'
                .'<td>'.$ownership['dateOfRelease'].'</td>'
                .'<td>'.$ownership['ownershipStatus'].'</td>'
                .'</tr>';
        }

        $output .= '</table>'
            .'</div>';
    }


    $output .= '<br /><div>'
        .'<a href="'.URL_BASE.'items/update_item/'.$this->model->data('itemID').'/"><input type="button" value="Update Item" /></a>'
        .'<a href="'.URL_BASE.'items/view_all/"><input type="button" value="View other Items" /></a>'
        .'</div>';


    if ( !$echo ) return $output;
    echo $output;
} //renderItemInformation


    
}
