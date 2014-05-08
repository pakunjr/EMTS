<?php

$packageModel = new package_model();
$itemtypeModel = new itemtype_model();

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
    . $formView->select(array('id'=>'item-type', 'label'=>'Type', 'select_options'=>$item_type_array))
    . $formView->textarea(array('id'=>'item-description', 'label'=>'Description'))
    . $formView->text(array('id'=>'date-of-purchase', 'class'=>'date-picker', 'label'=>'Date of Purchase'))
    . '</span><span class="fields-column">'
    . $formView->text(array('id'=>'item-owner', 'label'=>'Owner'))
    . $formView->select(array('id'=>'package', 'label'=>'Package', 'select_options'=>$package_array))
    . '</span>'
    . $formView->closeFieldset()
    . $formView->submit(array('value'=>'Save', 'auto_line_break'=>false))
    . $formView->reset(array('value'=>'Reset Fields'))
    . $formView->closeForm();

echo $formOutput;
