<?php

/**
 * Display a form.
 */
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
    . $formView->text(array('id'=>'search-item', 'class'=>'search-item', 'label'=>'Search Item'))
    . '</span>'
    . $formView->closeFieldset()
    . $formView->closeForm()
    . '<script type="text/javascript" src="'. URL_BASE. 'public/js/search.js"></script>';

echo $formOutput;
