<?php

class logView {


private $model;
private $controller;


public function __construct ($model=null) {
    $this->model = $model != null ? $model : new logModel();
    $this->controller = new logController($this->model);
} //__construct







public function getItemLog ($itemID) {

    $this->controller->readItemLog($itemID);

    $logContent = unserialize($this->model->data('logContent'));

    if ( is_array($logContent) ) {

        arsort($logContent);
        $output = '<table>'
            .'<tr>'
            .'<th>Date and Time</th>'
            .'<th>Details</th>'
            .'</tr>';
        foreach ( $logContent as $log ) {

            $notes = strlen(trim($log['notes'])) > 0
                ? $log['notes']
                : 'None';

            $output .= '<tr>'
                .'<td>'.$log['datetime'].'</td>'
                .'<td>'
                    .'System User: '.$log['user'].'<br />'
                    .'Action: '.ucfirst($log['action']).'d<br />'
                    .'Additional Notes:<br /><br />'
                    .$notes
                .'</td>'
                .'</tr>';

        }
        $output .= '</table>';

    } else {

        $output = 'There is no Log for this item.';

    }

    return $output;
    
} //getItemLog

}
