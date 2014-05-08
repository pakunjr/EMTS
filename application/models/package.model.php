<?php

class package_model {

    private $package_id;
    private $package_name;
    private $package_serial_no;
    private $package_description;
    private $package_items;
    private $date_of_purchase;

    private $list;

    public function __construct () {
        $dbModel = new database_model();
        $dbController = new database_controller($dbModel);

        /**
         * Generate the list variable
         */
        $arrayData = array();
        $result = $dbController->query("
                SELECT package_id
                    , package_name
                    , package_serial_no
                FROM tbl_packages
            ");
        while ( $row = $result->fetch_assoc() ) {
            $arrayData[$row['package_name']. '( '. $row['package_serial_no']. ' )'] = $row['package_id'];
        }
        $this->list = $arrayData;
    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

} //End class package_model
