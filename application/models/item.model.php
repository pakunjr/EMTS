<?php

class item_model {

    private $item_id;
    private $item_serial_no;
    private $item_model_no;
    private $item_name;
    private $item_type;
    private $item_description;
    private $item_date_of_purchase;

    public function __construct () {

    } //End function __construct

    public function getData ($data) {
        return $this->$data;
    } //End function getData

    public function setData ($data, $value) {
        $this->$data = $value;
    } //End function setData

    public function create () {
        $dbModel = new database_model();
        $dbController = new database_controller($dbModel);

        /**
         * Check if item already exist.
         */
        $existenceResult = $dbController->query("
                SELECT * FROM tbl_items
                WHERE
                    item_serial_no = '$this->item_serial_no'
                    AND item_model_no = '$this->item_model_no'
            ");
        if ( $existenceResult->num_rows > 0 ) return false;

        /**
         * Create item in the database.
         */
        $result = $dbController->query("
                INSERT INTO tbl_items(
                        item_serial_no
                        , item_model_no
                        , item_name
                        , item_type
                        , item_description
                        , date_of_purchase
                    ) VALUES(
                        '$this->item_serial_no'
                        , '$this->item_model_no'
                        , '$this->item_name'
                        , '$this->item_type'
                        , '$this->item_description'
                        , '$this->item_date_of_purchase'
                    )
            ");
        
        if ( !$result ) return false;

        return true;
    } //End function create

} //End class item
