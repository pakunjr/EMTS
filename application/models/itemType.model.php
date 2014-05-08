<?php

class itemType_model {

    private $id;
    private $label;

    private $list;

    public function __construct () {
        $dbModel = new database_model();
        $dbController = new database_controller($dbModel);

        /**
         * Generate the list variable
         */
        $arrayData = array();
        $result = $dbController->query("
                SELECT * FROM lst_item_type
            ");
        while ( $row = $result->fetch_assoc() ) {
            $arrayData[$row['label']] = $row['id'];
        }
        $this->list = $arrayData;
    } //End class __construct

    public function getData ($data) {
        return $this->$getData;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class itemType_model
