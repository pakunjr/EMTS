<?php

$formModel = new form_model(array(
        'auto_label'    => true
        , 'auto_line_break' => true
    ));
$formView = new form_view($formModel);

$formOutput = $formView->openForm(array(
        'id'        => 'search-item-form'
        , 'method'  => 'post'
        , 'action'  => URL_BASE. 'item/search_item/view/'
        , 'enctype' => ''
    ))
    . $formView->openFieldset(array('legend'=>'Search Item'))
    . '<span class="fields-column">'
    . $formView->text(array('id'=>'search-item', 'label'=>'Search Item'))
    . '</span>'
    . $formView->closeFieldset()
    . $formView->closeForm();

echo $formOutput;
