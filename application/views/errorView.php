<?php

class errorView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



public function displayLog () {


    $fileContent = file_get_contents($this->model->data('filepath'));
    $fileContent = explode(' &newline;', $fileContent);
    unset($fileContent[count($fileContent) - 1]);



    $renderedContent = '';
    $count = 1;
    foreach ( $fileContent as $a ) {
        $renderedContent .= '<b>'.$count.'</b>. '.$a.'<br />';
        $count++;
    }



    $contents = 'Errors and Exceptions encountered and logged by the system:<hr />'
        .'<div style="font-size: 0.9em;">'
        .$renderedContent
        .'<hr />'
        .'<a id="error-log-cleaner" href="'.URL_BASE.'admin/log/clean/"><input type="button" value="Clean Log" /></a>'
        .'<a href="'.URL_BASE.'admin/log/errors/"><input type="button" value="Refresh" /></a>'
        .'</div>';
    echo $contents;


    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#error-log-cleaner').click(function () {
                var $this = $(this);
                popAlert('confirm', {
                    'message': 'Do you want to clean the log?<br />'
                        +'This is undoable.'
                    ,'action': function () {
                        window.location = $this.attr('href');
                    }
                });
                return false;
            });
        });
    </script>
    <?php


} //displayLog

}
