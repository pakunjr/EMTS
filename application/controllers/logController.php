<?php

class logController {

private $model;

public function __construct ($model=null) {
    if ( $model == null ) $this->model = new logModel();
    else $this->model = $model;
} //__construct




public function logItem ($itemID, $action, $notes='') {
    $dbC = new databaseController();
    $loginM = new loginModel();
    $errC = new errorController();

    $content = 'The item was '.$action.'d by '.$loginM->getUserName().' @ '.date('Y-m-d H:i:s').$notes;

    $row = $dbC->PDOStatement(array(
        'query' => "SELECT log FROM tbl_items WHERE item_id = ?"
        ,'values'   => array(array('int', $itemID))
        ));
    $row = $row[0];
    $currentLog = $row['log'];
    $logContent = $currentLog.PHP_EOL.$content.PHP_EOL;

    $result = $dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items SET log = ?"
        ,'values'   => array($logContent)
        ));
    if ( !$result ) $errC->logError('Item log failed ( '.$itemID.' )');
} //logItem

}
