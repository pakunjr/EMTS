<div id="form-chooser" style="margin-bottom: 10px;">
    <span id="chooser-single-item" class="button-option unhighlightable selected">Single Item</span>
    <span id="chooser-package" class="button-option unhighlightable">Package / Bundle</span>
</div>

<hr />


<div id="form-container-single">
<?php

    $fcs = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));

    echo $fcs->openForm(array(
            'id'    => 'new-single-item-form'
            ,'method'   => 'post'
            ,'action'   => ''
            ,'enctype'  => 'multipart/form-data'
        ))
        ,$fcs->openFieldset(array('legend'=>'Single Item Form'))
        ,'<div class="row">'
            ,'<span class="column">'
            ,$fcs->text(array('id'=>'single-item-serial-no','label'=>'Serial No.'))
            ,$fcs->text(array('id'=>'single-item-model-no','label'=>'Model No.'))
            ,$fcs->text(array('id'=>'single-item-name','label'=>'Item Name'))
            ,'</span>'



            ,'<span class="column">'
            ,$fcs->textarea(array('id'=>'single-item-description','label'=>'Item Description'))
            ,$fcs->text(array('id'=>'single-item-date-purchase','label'=>'Date of Purchase','class'=>'datepicker'))
            ,'</span>'
        ,'</div>'
        ,$fcs->closeFieldset()


        ,$fcs->openFieldset(array('legend'=>'Owner Details'))
        ,'<div class="row">'
            ,'<span class="column">'
            ,$fcs->text(array('id'=>'person-search','label'=>'Search Owner'))
            ,'</span>'


            ,'<span class="column">'
            ,$fcs->text(array('id'=>'date-start-ownership','label'=>'Start date of Ownership'))
            ,'</span>'


            ,'<span class="column">'
            ,$fcs->text(array('id'=>'date-end-ownership','label'=>'End date of Ownership'))
            ,'</span>'
        ,'</div>'
        ,$fcs->closeFieldset()
        ,$fcs->submit(array('value'=>'Save item','auto_line_break'=>false))
        ,$fcs->closeForm();

?>
</div>







<div id="form-container-package" class="hidden">
<?php

    $fcp = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));

    echo $fcp->openForm(array(
            'id'    => 'new-package-item-form'
            ,'method'   => 'post'
            ,'action'   => ''
            ,'enctype'  => 'multipart/form-data'
        ))
        ,$fcp->openFieldset(array('legend'=>'Package Form'))
        ,'<div class="row">'
            ,'<span class="column">'

            ,'</span>'
        ,'</div>'
        ,$fcp->closeFieldset()
        ,$fcp->submit(array('value'=>'Save Package','auto_line_break'=>false))
        ,$fcp->reset(array('value'=>'Reset Fields'))
        ,$fcp->closeForm();

?>
</div>







<script type="text/javascript">
$(document).ready(function () {


    var $fc = $('#form-chooser')
        ,$fsi = $fc.children('#chooser-single-item')
        ,$fp = $fc.children('#chooser-package')

        ,$sc = $('#form-container-single')
        ,$pc = $('#form-container-package');

    $fc.children('.button-option').click(function () {
        if ( $fsi.hasClass('selected') ) {
            $sc.slideUp(100, function () {
                $fsi.removeClass('selected');
                $pc.slideDown(150, function () {
                    $fp.addClass('selected');
                });
            });
        } else {
            $pc.slideUp(100, function () {
                $fp.removeClass('selected');
                $sc.slideDown(150, function () {
                    $fsi.addClass('selected');
                });
            });
        }
    });


    var $fcsi = $('#form-container-single')
        ,$fcpi = $('#form-container-package');


});
</script>
