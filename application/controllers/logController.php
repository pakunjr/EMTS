<?php

class logController {

private $model;

public function __construct ($model=null) {
    $this->model = $model != null ? $model : new logModel();
} //__construct




public function logItem ($itemID, $action, $notes='') {
    
    $dbC = new databaseController();
    $loginM = new loginModel();
    $errC = new errorController();

    $details = array(
        'user'      => $loginM->getUserName()
        ,'datetime' => date('Y-m-d H:i:s')
        ,'action'   => $action
        ,'notes'    => $notes
        );

    $row = $dbC->PDOStatement(array(
        'query' => "SELECT log FROM tbl_items WHERE item_id = ?"
        ,'values'   => array(intval($itemID))
        ));
    $row = $row[0];
    $currentLog = unserialize($row['log']);
    $currentLog = is_array($currentLog) ? $currentLog : array();

    array_push($currentLog, $details);

    $logContent = serialize($currentLog);

    $result = $dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items SET log = ? WHERE item_id = ?"
        ,'values'   => array(
                $logContent
                ,intval($itemID)
            )
        ));

    if ( !$result ) $errC->logError('Item log failed ( '.$itemID.' )');
    
} //logItem




public function readItemLog ($itemID) {

    $dbC = new databaseController();

    $row = $dbC->PDOStatement(array(
        'query' => "SELECT log FROM tbl_items WHERE item_id = ?"
        ,'values'   => array(intval($itemID))
        ));

    $row = count($row) > 0 ? $row[0]['log'] : '';
    $this->model->data('logContent', $row);

} //readItemLog

}
