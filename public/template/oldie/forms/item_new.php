<?php

/**
 * Get list of itemType.
 */
$itemTypeModel = new itemType_model();
$itemTypeList = $itemTypeModel->getData('list');

/**
 * Display the form.
 */
$formModel = new form_model(array(
        'auto_label'    => true
        , 'auto_line_break' => true
    ));
$formView = new form_view($formModel);

$formOutput = $formView->openForm(array(
        'id'    => 'new-item-form'
        , 'method'  => 'post'
        , 'action'  => URL_BASE. 'item/new_item/save/'
        , 'enctype' => 'multipart/form-data'
    ))
    . $formView->openFieldset(array('legend'=>'New Item'))
    . '<span class="fields-column">'
    . $formView->text(array('id'=>'serial-no', 'label'=>'Serial No.'))
    . $formView->text(array('id'=>'model-no', 'label'=>'Model No.'))
    . $formView->text(array('id'=>'item-name', 'label'=>'Name'))
    . $formView->select(array('id'=>'item-type', 'label'=>'Type', 'select_options'=>$itemTypeList))
    . $formView->textarea(array('id'=>'item-description', 'label'=>'Description'))
    . $formView->text(array('id'=>'date-of-purchase', 'class'=>'date-picker', 'label'=>'Date of Purchase'))
    . '</span><span class="fields-column">'
    . $formView->text(array('id'=>'item-owner', 'class'=>'search-item', 'label'=>'Owner'))
    . $formView->text(array('id'=>'package', 'class'=>'search-package', 'label'=>'Package'))
    . '</span>'
    . $formView->closeFieldset()
    . $formView->submit(array('value'=>'Save', 'auto_line_break'=>false))
    . $formView->reset(array('value'=>'Reset Fields'))
    . $formView->closeForm()
    . '<script type="text/javascript" src="'. URL_BASE. 'public/js/search.js"></script>';

echo $formOutput;
