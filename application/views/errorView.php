<?php

class errorView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct



public function displayLog () {


    $fileContent = file_get_contents($this->model->data('filepath'));
    $fileContent = unserialize($fileContent);
    $fileContent = is_array($fileContent) ? $fileContent : array();

    if ( count($fileContent) > 0 ) {

        $renderedContent = '<table><tr>'
            .'<th>#</th>'
            .'<th>Error Details</th>'
            .'</tr>';
        $count = 1;
        foreach ( $fileContent as $a ) {
            $renderedContent .= '<tr>'
                .'<td>'.$count.'</td>'
                .'<td>'.$a.'</td>'
                .'</tr>';
            $count++;
        }
        $renderedContent .= '</table>';
        $cleanBtn = '<a id="error-log-cleaner" href="'.URL_BASE.'admin/log/clean/"><input type="button" value="Clean Log" /></a>';

    } else {

        $renderedContent = 'No error/s have been logged as of yet.';
        $cleanBtn = '';

    }

    $contents = 'Errors and Exceptions encountered and logged by the system:<hr />'
        .'<div style="font-size: 0.9em;">'
        .$renderedContent
        .'<hr />'
        .$cleanBtn
        .'<a href="'.URL_BASE.'admin/log/errors/"><input type="button" value="Refresh" /></a>'
        .'</div>';
    echo $contents;


    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            if ( $('#error-log-cleaner').length > 0 ) {

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

            }
        });
    </script>
    <?php


} //displayLog

}
