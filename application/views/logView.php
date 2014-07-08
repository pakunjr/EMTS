<?php

class logView {


private $model;


public function __construct ($model=null) {
    if ( $model == null ) $this->model = new logModel();
    else $this->model = $model;
} //__construct







public function getItemLog ($itemID) {
    $dbC = new databaseController();

    $row = $dbC->PDOStatement(array(
        'query' => "SELECT log FROM tbl_items WHERE item_id = ?"
        ,'values'   => array(array('int', $itemID))
        ));
    $row = count($row) > 0 ? $row[0] : '';
    $logContent = trim($row['log'], PHP_EOL);
    $logContent = explode(PHP_EOL, $logContent);
    $displayContent = '';

    foreach ( $logContent as $content ) {
        $displayContent = $content.'<br />'.$displayContent;
    }

    return $displayContent;
} //getItemLog

}
