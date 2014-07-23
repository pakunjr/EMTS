<?php

class itemView {


private $model;
private $controller;



public function __construct ($model=null) {

    $this->model = $model != null ? $model : new itemModel();
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

        case 'search_package_item':
            $this->controller->searchPackageItem($action);
            $this->displayPackageItemSearch(true);
            break;



        case 'search':
            $this->itemSearch($action);
            break;


        case 'new_item':
            switch ( $action ) {
                case null:
                    $GLOBALS['pageView']->getHeader();
                    $this->itemForm('create', true);
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
            $this->displayAllItem($action, true);
            $GLOBALS['pageView']->getFooter();
            break;

        case 'view':
            $GLOBALS['pageView']->getHeader();
            $this->displayItem($action, true);
            $GLOBALS['pageView']->getFooter();
            break;

        case 'update_item':
            if ( $action == 'save' ) {
                $result = $this->controller->updateItem($_POST);
                $this->renderUpdateResults($result);
            } else {
                $GLOBALS['pageView']->getHeader();
                //$this->renderForm('update', true, $action);
                $this->itemForm('update', true, $action);
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



































public function itemForm ($action='create', $echo=false, $id=null) {

    $logC = new logController();
    $errC = new errorController();
    $itemStateC = new itemStateController();
    $itemTypeC = new itemTypeController();
    $ownerTypeC = new ownerTypeController();

    $formLabel = $action == 'create'
        ? '<b style="font-size: 1.5em;">New Item</b><hr />'
        : '<b style="font-size: 1.5em;">Update Item</b><hr />';

    $submitLabel = $action == 'create'
        ? 'Create Item'
        : 'Update Item';


    $formAction = $action == 'create'
        ? URL_BASE.'items/new_item/save/'
        : URL_BASE.'items/update_item/save/';
        

    $f = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));


    if ( $action == 'update' ) {

        $this->controller->readItem($id);

        if ( !$this->model->data('itemExists')
                && $action == 'update' ) {
            
            $output = 'It seems like the item doesn\'t exists in the database or the system.<br />Please contact our system administrator for further details.<br />Thank you.';

            $errC->logError('Failed to read Item ('.$id.'), the item doesn\' exists.');

            if ( !$echo ) return $output;
            echo $output;
            return;
            
        }

    } else $this->controller->readItem(null);

    $itemStateList = $itemStateC->getList('select_options');
    $itemTypeList = $itemTypeC->getList('select_options');
    $ownerTypeList = $ownerTypeC->getList('select_options');

    $itemDetails = $this->model->data('itemInformation');

    if ( $itemDetails['is_archived'] == 1 ) {
        header('location: '.URL_BASE.'items/view_all/');
        return;
    }

    $itemInformation = $f->openFieldset(array('legend'=>'Item Information'))
        .'<span class="column">'
        .$f->hidden(array(
            'id'=>'item-id'
            ,'value'=>$itemDetails['item_id']))
        .$f->text(array(
            'id'=>'item-name'
            ,'label'=>'Name'
            ,'value'=>$itemDetails['item_name']))
        .$f->text(array(
            'id'=>'item-serial-no'
            ,'label'=>'Serial No.'
            ,'value'=>$itemDetails['item_serial_no']))
        .$f->text(array(
            'id'=>'item-model-no'
            ,'label'=>'Model No.'
            ,'value'=>$itemDetails['item_model_no']))
        .$f->hidden(array(
            'id'=>'package-id'
            ,'value'=>$itemDetails['package_id']))
        .$f->text(array(
            'id'=>'package-label'
            ,'label'=>'Package Deal'
            ,'value'=>$itemDetails['package_id'] != ''
                ? $itemDetails['package_name'].' ('.$itemDetails['package_serial_no'].')'
                : ''))
        .'</span>'

        .'<span class="column">'
        .$f->text(array(
            'id'=>'item-quantity'
            ,'class'=>'decimal'
            ,'label'=>'Quantity'
            ,'value'=>$itemDetails['item_quantity']))
        .$f->text(array(
            'id'=>'item-quantity-unit'
            ,'label'=>'Quantity Unit'
            ,'value'=>$itemDetails['item_quantity_unit']))
        .$f->select(array(
            'id'=>'item-type'
            ,'label'=>'Type'
            ,'select_options'=>$itemTypeList
            ,'default_option'=>$itemDetails['item_type']))
        .$f->select(array(
            'id'=>'item-state'
            ,'label'=>'State'
            ,'select_options'=>$itemStateList
            ,'default_option'=>$itemDetails['item_state']))
        .$f->text(array(
            'id'=>'item-date-purchase'
            ,'class'=>'datepicker'
            ,'label'=>'Date of Purchase'
            ,'value'=>$itemDetails['item_DOP']))
        .'</span>'

        .'<span class="column">'
        .$f->textarea(array(
            'id'=>'item-description'
            ,'label'=>'Description'
            ,'value'=>$itemDetails['item_description']))
        .$f->checkbox(array(
            'id'=>'has-component'
            ,'label'=>'Has Component'
            ,'checked'=>$itemDetails['has_components']))
        .$f->checkbox(array(
            'id'=>'is-component'
            ,'label'=>'Is Component'
            ,'checked'=>is_array($itemDetails['component_of_name'])
                ? true : false))
        .$f->hidden(array(
            'id'=>'host-item-id'
            ,'value'=>is_array($itemDetails['component_of_name'])
                ? $itemDetails['component_of_name']['id']
                : ''))
        .$f->text(array(
            'id'=>'host-item'
            ,'label'=>'Host Item'
            ,'value'=>is_array($itemDetails['component_of_name'])
                ? $itemDetails['component_of_name']['name']
                    .' ( '
                    .$itemDetails['component_of_name']['serial_no']
                    .' <> '
                    .$itemDetails['component_of_name']['model_no']
                    .' )'
                : ''))
        .'</span>'
        .$f->closeFieldset();

    $ownerTypeLabel = $ownerTypeC->idToLabel($itemDetails['co_type']);

    $defIdEmployee = '';
    $defIdDepartment = '';
    $defIdGuest = '';
    $defValueEmployee = '';
    $defValueDepartment = '';
    $defValueGuest = '';

    switch ( $ownerTypeLabel ) {
        case 'Employee':
            $defIdEmployee = $itemDetails['co_id'];
            $defValueEmployee = $itemDetails['co_name'];
            break;

        case 'Department':
            $defIdDepartment = $itemDetails['co_id'];
            $defValueDepartment = $itemDetails['co_name'];
            break;

        case 'Guest':
            $defIdGuest = $itemDetails['co_id'];
            $defValueGuest = $itemDetails['co_name'];
            break;

        default:
    }

    $ownerInformation = $f->openFieldset(array('legend'=>'Owner Information'))
        .$f->hidden(array(
            'id'=>'current-ownership-id'
            ,'value'=>$itemDetails['co_ownership_id']))
        .$f->hidden(array(
            'id'=>'current-owner-type'
            ,'value'=>$itemDetails['co_type']))
        .$f->hidden(array(
            'id'=>'current-owner-id'
            ,'value'=>$itemDetails['co_id']))

        .$f->select(array(
            'id'=>'owner-type'
            ,'label'=>'Type'
            ,'select_options'=>$ownerTypeList
            ,'default_option'=>$itemDetails['co_type']))

        .$f->hidden(array(
            'id'=>'employee-id'
            ,'value'=>$defIdEmployee))
        .$f->hidden(array(
            'id'=>'department-id'
            ,'value'=>$defIdDepartment))
        .$f->hidden(array(
            'id'=>'guest-id'
            ,'value'=>$defIdGuest))

        .'<span class="column">'
            .'<span id="employee-block">'.$f->text(array(
                'id'=>'employee-label'
                ,'label'=>'Employee'
                ,'value'=>$defValueEmployee)).'</span>'
            .'<span id="department-block">'.$f->text(array(
                'id'=>'department-label'
                ,'label'=>'Department'
                ,'value'=>$defValueDepartment)).'</span>'
            .'<span id="guest-block">'.$f->text(array(
                'id'=>'guest-label'
                ,'label'=>'Guest'
                ,'value'=>$defValueGuest)).'</span>'
        .'</span>'

        .'<span class="column">'
        .'<span id="dop-block">'.$f->text(array(
            'id'=>'date-of-possession'
            ,'class'=>'datepicker'
            ,'label'=>'Date of Possession'
            ,'value'=>$itemDetails['co_DOP'])).'</span>'
        .'</span>'
        .$f->closeFieldset();


    $output = $formLabel
        .$f->openForm(array(
            'id'    => 'item-form'
            ,'method'   => 'post'
            ,'action'   => $formAction
            ,'enctype'  => 'multipart/form-data'
        ))
        .$itemInformation
        .$ownerInformation
        .$f->submit(array('value'=>$submitLabel))
        .$f->closeForm()
        .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';

    if ( !$echo ) return $output;
    echo $output;
} //itemForm




























public function itemSearch ($query) {

    $this->controller->searchItem($query);
    $itemList = $this->model->data('itemList');

    if ( $itemList == null ) {
        echo '<div>Sorry, but it seems like your query didn\'t match any of our information.</div>';
        return;
    }

    $output = '<table>'
        .'<tr>'
        .'<th>Item<br />'
            .'<span style="color: #f00;">Serial No.</span><br />'
            .'<span style="color: #03f;">Model No.</span></th>'
        .'<th>Type</th>'
        .'<th>Description</th>'
        .'<th>State</th>'
        .'<th>Quantity</th>'
        .'<th>Date of Purchase</th>'
        .'</tr>';

    foreach ( $itemList as $itemID => $itemInfo ) {
        $output .= '<tr class="search-data">'
            .'<td>'
                .'<input type="hidden" class="prime-data" value="'.$itemID.'" />'
                .'<span class="prime-label hidden">'
                    .$itemInfo['name'].' ( '.$itemInfo['serial_no'].' :: '.$itemInfo['model_no'].' )'
                .'</span>'
                .$itemInfo['name'].'<br />'
                .'<span style="color: #f00;">'.$itemInfo['serial_no'].'</span><br />'
                .'<span style="color: #03f;">'.$itemInfo['model_no'].'</span>'
            .'</td>'
            .'<td>'.$itemInfo['type'].'</td>'
            .'<td>'.$itemInfo['description'].'</td>'
            .'<td>'.$itemInfo['state'].'</td>'
            .'<td>'.$itemInfo['quantity'].' '.$itemInfo['quantity_unit'].'</td>'
            .'<td>'.$itemInfo['dop'].'</td>'
            .'</tr>';
    }

    $output .= '</table>';

    echo $output;

} //itemSearch




































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
 * Render save results
 */
public function renderSaveResults ($itemID, $echo=false) {

    if ( $itemID == null ) {
        echo 'Item failed to save.<br /><a href="',URL_BASE,'items/new_item/"><input type="button" value="Go Back" /></a>';
    } else
        header('location: '.URL_BASE.'items/view/'.$itemID.'/');

} //renderSaveResults



public function renderUpdateResults ($results) {

    if ( !$results['success'] ) {
        echo $GLOBALS['pageView']->getHeader()
            ,'Item failed to update.<br /><a href="',URL_BASE,'items/update_item/'.$results['itemID'].'/"><input type="button" value="Go Back" /></a>'
            ,$GLOBALS['pageView']->getFooter();
    } else
        header('location: '.URL_BASE.'items/view/'.$results['itemID'].'/');

} //renderUpdateResults





















public function displayPackageItemSearch ($echo=false) {
    $itemResults = $this->model->data('itemSearch');

    if ( $itemResults === false || !is_array($itemResults) ) {
        echo 'There are no results found.';
        return;
    }

    $output = '<table>'
        .'<tr>'
        .'<th>Serial No.</th>'
        .'<th>Model No.</th>'
        .'<th>Name</th>'
        .'<th>Date of Purchase</th>'
        .'</tr>';


    foreach ( $itemResults as $itemResult ) {
        $label = $itemResult['name'].' ( '.$itemResult['serialNo'].', '.$itemResult['modelNo'].' )';
        $output .= '<tr class="search-results">'
            .'<td>'
                .'<span class="search-result-label">'.$label.'</span>'
                .'<span class="search-result-identifier">'.$itemResult['itemID'].'</span>'
                .$itemResult['serialNo']
            .'</td>'
            .'<td>'.$itemResult['modelNo'].'</td>'
            .'<td>'.$itemResult['name'].'</td>'
            .'<td>'.$itemResult['dateOfPurchase'].'</td>'
            .'</tr>';
    }

    $output .= '</table>';

    if ( !$echo ) return $output;
    echo $output;
} //displayPackageItemSearch




























/**
 * List all items
 */
public function displayAllItem ($page='1', $echo=false) {

    $items = $this->controller->readAllItem();
    $itemTypeC = new itemTypeController();
    $itemStateC = new itemStateController();

    if ( $items == null )

        $itemsTable = 'There are no items to be displayed.';

    else {

        $itemsTable = '<table style="font-size: 0.85em;">'
            .'<tr>'
            .'<th>'
                .'Item<br />'
                .'<span style="color: #03f;">Serial No.</span><br />'
                .'<span style="color: #f00;">Model No.</span>'
            .'</th>'
            .'<th>Type</th>'
            .'<th>State</th>'
            .'<th>Description</th>'
            .'<th>Quantity</th>'
            .'<th>Current Owner</th>'
            .'<th>Date of Purchase</th>'
            .'<th>Package<br />'
                .'<span style="color: #03f;">Serial No.</span>'
            .'</th>'
            .'<th>Actions</th>'
            .'</tr>';

        foreach ( $items as $item ) {

            $archivedStatus = $item['is_archived'] == 1
                ? 'archived-item'
                : '';

            $actionBtns = $item['is_archived'] == 0
                ? '<a href="'.URL_BASE.'items/update_item/'.$item['item_id'].'/">'
                    .'<input type="button" value="Update" />'
                    .'</a>'
                    .'<a href="'.URL_BASE.'items/delete_item/'.$item['item_id'].'/">'
                    .'<input class="btn-red" type="button" value="Delete" />'
                    .'</a>'
                : '';

            $itemsTable .= '<tr class="item-block '.$archivedStatus.'">'
                .'<td>'
                    .'<input class="prime-data" type="hidden" value="'.$item['item_id'].'" />'
                    .$item['item_name'].'<br />'
                    .'<span style="color: #03f;">'.$item['item_serial_no'].'</span><br />'
                    .'<span style="color: #f00;">'.$item['item_model_no'].'</span>'
                .'</td>'
                .'<td>'.$itemTypeC->idToLabel($item['item_type']).'</td>'
                .'<td>'.$itemStateC->idToLabel($item['item_state']).'</td>'
                .'<td>'.nl2br($item['item_description']).'</td>'
                .'<td>'.$item['item_quantity'].' '.$item['item_quantity_unit'].'</td>'
                .'<td>'.$item['co_name'].'</td>'
                .'<td>'.$item['item_DOP'].'</td>'
                .'<td>'
                    .$item['package_name'].'<br />'
                    .'<span style="color: #03f;">'.$item['package_serial_no'].'</span>'
                .'</td>'
                .'<td>'
                .$actionBtns
                .'</td>'
                .'</tr>';

        }

        $itemsTable .= '</table>'
            .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';

    }

    $output = '<span style="font-size: 1.5em;">Item List</span><hr />'
        .$itemsTable;

    if ( !$echo ) return $output;
    echo $output;

} //displayAllItem































/**
 * Render single item information for viewing
 */
public function displayItem ($itemID, $echo=false) {

    $this->controller->readItem($itemID);

    $dbC = new databaseController();
    $logV = new logView();
    $ownerTypeC = new ownerTypeController();
    $itemStateC = new itemStateController();
    $itemTypeC = new itemTypeController();
    $packageC = new packageController();
    $loginM = new loginModel();

    if ( !$this->model->data('itemExists') ) {
        echo 'The item you were searching for doesn\'t exists.<br /><a href="',URL_BASE,'items/"><input type="button" value="Back" /></a>';
        return;
    }

    $ii = $this->model->data('itemInformation');

    $itemQuantity = $ii['item_quantity'].' '.$ii['item_quantity_unit'];
    $itemState = $itemStateC->idToLabel($ii['item_state']);
    $itemType = $itemTypeC->idToLabel($ii['item_type']);
    $itemInformation = '<span class="column">'
        .'<table style="font-size: 0.9em;">'
        .'<tr>'
            .'<td><b>Serial No.</b></td>'
            .'<td>'.$ii['item_serial_no'].'</td>'
            .'<td></td>'
            .'<td><b>Package\'s Name</b></td>'
            .'<td>'.$ii['package_name'].'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>Model No.</b></td>'
            .'<td>'.$ii['item_model_no'].'</td>'
            .'<td></td>'
            .'<td><b>Package\'s Serial No.</b></td>'
            .'<td>'.$ii['package_serial_no'].'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>Type</b></td>'
            .'<td>'.$itemType.'</td>'
            .'<td></td>'
            .'<td><b>Package\'s Description</b></td>'
            .'<td>'.$ii['package_description'].'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>State</b></td>'
            .'<td>'.$itemState.'</td>'
            .'<td></td>'
            .'<td><b>Package\'s Date of Purchase</b></td>'
            .'<td>'.$ii['package_DOP'].'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>Description</b></td>'
            .'<td>'.nl2br($ii['item_description']).'</td>'
            .'<td></td>'
            .'<td></td>'
            .'<td>'.'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>Quantity</b></td>'
            .'<td>'.$itemQuantity.'</td>'
            .'<td></td>'
            .'<td></td>'
            .'<td>'.'</td>'
        .'</tr>'
        .'<tr>'
            .'<td><b>Date of Purchase</b></td>'
            .'<td>'.$ii['item_DOP'].'</td>'
            .'<td></td>'
            .'<td></td>'
            .'<td>'.'</td>'
        .'</tr>'
        .'</table>'
        .'</span>';

    $components = $this->controller->getComponents($itemID);
    if ( is_array($components) && $components != null ) {

        $componentsList = '<table>'
            .'<tr>'
            .'<th>'
                .'Name<br />'
                .'<span style="color: #03f;">Serial No.</span><br />'
                .'<span style="color: #f00;">Model No.</span>'
            .'</th>'
            .'<th>Type</th>'
            .'<th>State</th>'
            .'<th>Description</th>'
            .'<th>Quantity</th>'
            .'<th>Date of Purchase</th>'
            .'<th>Actions</th>'
            .'</tr>';

        foreach ( $components as $component ) {

            $archivedStatus = $component['is_archived'] == 1
                ? 'archived-item'
                : '';

            $actionBtns = $component['is_archived'] == 0
                ? '<a href="'.URL_BASE.'items/update_item/'.$component['item_id'].'/">'
                    .'<input type="button" value="Update" />'
                    .'</a>'
                    .'<a href="'.URL_BASE.'items/delete_item/'.$component['item_id'].'/">'
                    .'<input class="btn-red" type="button" value="Delete" />'
                    .'</a>'
                : '';

            $componentsList .= '<tr class="item-block '.$archivedStatus.'">'
                .'<td>'
                    .'<input class="prime-data" type="hidden" value="'.$component['item_id'].'" />'
                    .$component['item_name'].'<br />'
                    .'<span style="color: #03f;">'.$component['item_serial_no'].'</span><br />'
                    .'<span style="color: #f00;">'.$component['item_model_no'].'</span>'
                .'</td>'
                .'<td>'.$itemTypeC->idToLabel($component['item_type']).'</td>'
                .'<td>'.$itemStateC->idToLabel($component['item_state']).'</td>'
                .'<td>'.nl2br($component['item_description']).'</td>'
                .'<td>'.$component['quantity'].' '.$component['quantity_unit'].'</td>'
                .'<td>'.$component['item_dop'].'</td>'
                .'<td>'
                .$actionBtns
                .'</td>'
                .'</tr>';

        }

        $componentsList = '</table>';

    } else $componentsList = '';

    if ( is_array($ii['ownership_history'])
        && count($ii['ownership_history']) > 0 ) {
        $ownershipHistory = '<div id="ownership-history-trigger" style="padding: 15px 20px; border: 1px solid #ccc;"><b>Ownership History</b><hr />'
            .'<table id="ownership-history" style="font-size: 0.85em;">'
            .'<tr>'
                .'<th>Owner Type</th>'
                .'<th>Owner Name</th>'
                .'<th>Item Status</th>'
                .'<th>Date of Possession</th>'
                .'<th>Date of Release</th>'
            .'</tr>';

        foreach ( $ii['ownership_history'] as $oh ) {
            $otLabel = $ownerTypeC->idToLabel($oh['owner_type']);
            $isLabel = $itemStateC->idToLabel($oh['item_status']);

            $ownershipHistory .= '<tr>'
                .'<td>'.$otLabel.'</td>'
                .'<td>'.$oh['owner_name'].'</td>'
                .'<td>'.$isLabel.'</td>'
                .'<td>'.$oh['owner_DOP'].'</td>'
                .'<td>'.$oh['owner_DOR'].'</td>'
                .'</tr>';
        }

        $ownershipHistory .= '</table></div>';
    } else $ownershipHistory = 'This item has no ownership history.';

    $logInformation = '<div id="item-log-trigger" style="padding: 15px 20px; border: 1px solid #ccc; font-size: 0.95em;">'
        .'<b>Item Log</b><hr />'
        .'<div id="item-log" style="max-height: 350px; overflow-y: auto; font-size: 0.9em;">'
        .$logV->getItemLog($itemID)
        .'</div>'
        .'</div>';


    if ( $ii['is_archived'] == '1' || $ii['is_archived'] == 1 ) {
        $archiveStatus = ' <span style="color: #f00; font-size: 0.65em;">( This item has already been archived. )</span>';
        $deleteBtn = '';
    } else {
        $archiveStatus = '';
        $deleteBtn = '<a class="item-delete-btn" href="'.URL_BASE.'items/delete_item/'.$itemID.'/">'
            .'<input type="button" value="Delete Item" />'
            .'</a>';
    }


    $output = '<span style="font-size: 1.5em;">'.$ii['item_name'].$archiveStatus.'</span><hr />'
        .'<div class="row">'
        .$itemInformation
        .'</div>'

        .'<div class="row">'
        .$componentsList
        .'</div>'

        .'<div class="row" style="margin: 10px 0px;">'
        .$ownershipHistory
        .'</div>'

        .'<div class="row">'
        .$logInformation
        .'</div>'

        .'<br />'
        .'<a href="'.URL_BASE.'items/update_item/'.$itemID.'/">'
        .'<input type="button" value="Update Item" />'
        .'</a>'
        .$deleteBtn
        .'<script type="text/javascript" src="'.URL_TEMPLATE.'js/item.js"></script>';

    if ( !$echo ) return $output;
    echo $output;

} //displayItem


    
}
