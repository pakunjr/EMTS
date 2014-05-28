<?php

$inf = new form(array(
        'auto_line_break'   => true
        ,'auto_label'       => true
    ));

echo $inf->openForm(array(
        'id'    => 'new-item-form'
        ,'method'   => 'post'
        ,'action'   => ''
        , 'enctype' => 'multipart/form-data'
    ))


    ,$inf->openFieldset(array('legend'=>'Item Information'))
    ,'<span class="column">'
        ,$inf->text(array('id'=>'serial-no','label'=>'Serial No.'))
        ,$inf->text(array('id'=>'model-no','label'=>'Model No.'))
        ,$inf->text(array('id'=>'item-name','label'=>'Name'))
        ,$inf->textarea(array('id'=>'item-description','label'=>'Description'))
        ,$inf->text(array('id'=>'date-purchase','class'=>'datepicker','label'=>'Date of Purchase'))
    ,'</span>'
    ,'<div class="row">'
    ,$inf->reset(array('value'=>'Clear Item Fields'))
    ,'</div>'
    ,$inf->closeFieldset()


    ,$inf->openFieldset(array('legend'=>'Owner Information'))
        ,$inf->select(array('id'=>'owner-type','label'=>'Owner Type','select_options'=>array(
                'Department'    => 'department'
                ,'Office'       => 'office'
                ,'Person'       => 'person'
                ,'None'         => 'none'
            )))
    ,$inf->closeFieldset()


    ,$inf->submit(array('value'=>'Save Informations'))
    ,$inf->closeForm();

