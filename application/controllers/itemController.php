<?php

class itemController {

private $model;


private $dbM;
private $dbV;
private $dbC;


private $itemTypeM;
private $itemTypeC;


public function __construct ($model) {
    $this->model = $model;

    $this->dbM = new databaseModel();
    $this->dbV = new databaseView($this->dbM);
    $this->dbC = new databaseController($this->dbM);

    $this->itemTypeM = new itemTypeModel();
    $this->itemTypeC = new itemTypeController($this->itemTypeM);
} //_construct
















/**
 * For saving a new item
 */
public function createItem ($datas=array()) {
    if ( !is_array($datas) ) {
        echo 'There was an error in saving.<br />'
            ,'Item was not saved.';
        return false;
    }

    $datas = $this->dbC->escapeArray($datas);

    /* Single Item Specification */
    $itemSerialNo = $datas['single-item-serial-no'];
    $itemModelNo = $datas['single-item-model-no'];
    $itemQuantity = $datas['single-item-quantity'];
    $itemQuantityUnit = $datas['single-item-quantity-unit'];
    $itemName = $datas['single-item-name'];
    $itemDescription = $datas['single-item-description'];
    $itemDatePurchase = $datas['single-item-date-purchase'];
    $itemType = $datas['single-item-type'];
    $itemState = $datas['single-item-state'];
    $itemPackageID = $datas['single-item-package-search-id'];

    /* Item Specifications */
    $itemSpecsProcessor = $datas['item-specs-processor'];
    $itemSpecsVideo = $datas['item-specs-video'];
    $itemSpecsDisplay = $datas['item-specs-display'];
    $itemSpecsWebcam = $datas['item-specs-webcam'];
    $itemSpecsAudio = $datas['item-specs-audio'];
    $itemSpecsNetwork = $datas['item-specs-network'];
    $itemSpecsUSBPort = $datas['item-specs-usbports'];
    $itemSpecsMemory = $datas['item-specs-memory'];
    $itemSpecsStorage = $datas['item-specs-storage'];
    $itemSpecsOS = $datas['item-specs-os'];
    $itemSpecsSoftware = $datas['item-specs-software'];

    /* Ownership Information */
    $ownerTypeID = $datas['owner-type'];
    $employeeID = $datas['person-search-id'];
    $departmentID = $datas['department-search-id'];
    $guestID = $datas['guest-search-id'];
    $ownerDateOfPossession = $datas['owner-date-of-possession'];

    $ownerTypeM = new ownerTypeModel();
    $ownerTypeC = new ownerTypeController($ownerTypeM);
    $ownerType = strtolower($ownerTypeC->decodeID($ownerTypeID));

    if ( $ownerType == 'employee' )
        $ownerID = $employeeID;
    else if ( $ownerType == 'department' )
        $ownerID = $departmentID;
    else if ( $ownerType == 'guest' )
        $ownerID = $guestID;

    /**
     * Have the date of purchase of the item
     * to be identical to the date of purchase
     * of the package it belongs to
     */
    if ( $itemPackageID != null || $itemPackageID != '' ) {
        $pQuery = "SELECT date_of_purchase FROM tbl_packages WHERE package_id = $itemPackageID";
        $pResult = $this->dbC->query($pQuery);
        $pRow = $pResult->fetch_assoc();
        $itemDatePurchase = $pRow['date_of_purchase'];
    }


    $itemQuery = "INSERT INTO tbl_items(
            item_serial_no
            ,item_model_no
            ,item_name
            ,item_type
            ,item_state
            ,item_description
            ,quantity
            ,quantity_unit
            ,date_of_purchase
            ,package_id
            ,is_archived
        ) VALUES(
            '$itemSerialNo'
            ,'$itemModelNo'
            ,'$itemName'
            ,'$itemType'
            ,'$itemState'
            ,'$itemDescription'
            ,$itemQuantity
            ,'$itemQuantityUnit'
            ,'$itemDatePurchase'
            ,'$itemPackageID'
            ,'0'
        )";
    $itemResult = $this->dbC->query($itemQuery);
    $itemID = $this->dbC->insertedID();


    if ( $ownerType != 'none' ) {
        $currentDate = date('Y-m-d');
        $ownershipID = $this->newOwnershipID();
        $ownershipQuery = "INSERT INTO tbl_ownerships(
                ownership_id
                ,owner_id
                ,owner_type
                ,item_id
                ,item_status
                ,date_of_possession
                ,date_of_release
            ) VALUES(
                '$ownershipID'
                ,$ownerID
                ,$ownerTypeID
                ,$itemID
                ,'working'
                ,'$ownerDateOfPossession'
                ,'0000-00-00'
            )";
        $ownershipResult = $this->dbC->query($ownershipQuery);
    } else $ownershipResult = 'n/a';



    /**
     * If item type is set to Devices
     */
    if ( $this->itemTypeC->decode($itemType) == 'Devices' ) {
        $specificationQuery = "INSERT INTO tbl_items_specification(
                item_id
                ,processor
                ,video
                ,display
                ,webcam
                ,audio
                ,network
                ,usb_ports
                ,memory
                ,storage
                ,os
                ,software
            ) VALUES(
                $itemID
                ,'$itemSpecsProcessor'
                ,'$itemSpecsVideo'
                ,'$itemSpecsDisplay'
                ,'$itemSpecsWebcam'
                ,'$itemSpecsAudio'
                ,'$itemSpecsNetwork'
                ,'$itemSpecsUSBPort'
                ,'$itemSpecsMemory'
                ,'$itemSpecsStorage'
                ,'$itemSpecsOS'
                ,'$itemSpecsSoftware'
            )";
        $specificationResult = $this->dbC->query($specificationQuery);
    } else
        $specificationResult = 'n/a';


    /**
     * Set results that is to be returned and display
     * to the user
     */
    $results = array(
            'item'              => $itemResult
            ,'specification'    => $specificationResult
            ,'ownership'        => $ownershipResult
            ,'itemID'           => $itemID
        );

    return $results;

} //createItem












/**
 * For updating an existing item
 */
public function updateItem ($itemID) {

} //updateItem












/**
 * For archiving an item which is
 * equivalent for delete but the
 * data still exists on the database
 */
public function archiveItem ($itemID) {

} //archiveItem














/**
 * Generate new ID that is used for ownership ID
 * ID Format: OSHP(xxxx)(xx)(xxxxx)
 * OSHP (year 4) (month 2) (sequence 5)
 * Length: 15 characters
 */
public function newOwnershipID () {

    $currentYear = date('Y');
    $currentMonth = date('m');

    /**
     * Get the last ownership ID
     */
    $query = "SELECT ownership_id
            FROM tbl_ownerships
            ORDER BY ownership_id DESC
            LIMIT 1
        ";
    $result = $this->dbC->query($query);
    $count = $result->num_rows;

    if ( $count < 1 ) {
        return 'OSHP'.$currentYear.$currentMonth.'00000';
    }


    $row = $result->fetch_assoc();
    $lastID = $row['ownership_id'];

    $parseYear = $lastID[4]
        .$lastID[5]
        .$lastID[6]
        .$lastID[7];
    $parseMonth = $lastID[8].$lastID[9];
    $parseSequence = $lastID[10]
        .$lastID[11]
        .$lastID[12]
        .$lastID[13]
        .$lastID[14];
    $parseSequence = (int)$parseSequence;

    //Do the sequence
    if ( $currentYear == $parseYear
            && $currentMonth == $parseMonth ) {
        $sequence = $parseSequence + 1;

        for ( $i = strlen($sequence); $i < 5; $i++ ) {
            $sequence = '0'.$sequence;
        }
    } else
        $sequence = '00000';

    $generatedID = 'OSHP'.$currentYear.$currentMonth.$sequence;
    return $generatedID;

} //newOwnershipID

















/**
 * Read item
 */
public function readItem ($itemID) {

    $sqlQuery = "
        SELECT
            items.item_id
            ,items.item_serial_no
            ,items.item_model_no
            ,items.item_name
            ,items.item_state
            ,items.item_description
            ,items.date_of_purchase AS itemDOP
            ,items.quantity
            ,items.quantity_unit
            ,items.is_archived

            ,itemType.label AS item_type

            ,specs.processor
            ,specs.video
            ,specs.display
            ,specs.webcam
            ,specs.audio
            ,specs.network
            ,specs.usb_ports
            ,specs.memory
            ,specs.storage
            ,specs.os
            ,specs.software

            ,packages.package_id
            ,packages.package_name
            ,packages.package_serial_no
            ,packages.package_description
            ,packages.date_of_purchase AS packageDOP
        FROM tbl_items AS items
        LEFT JOIN
            lst_item_type AS itemType ON items.item_type = itemType.id
        LEFT JOIN
            tbl_items_specification AS specs ON items.item_id = specs.item_id
        LEFT JOIN
            tbl_packages AS packages ON items.item_id = packages.package_id
        LEFT JOIN
            tbl_ownerships AS ownership ON items.item_id = ownership.item_id
        WHERE
            items.item_id = $itemID
    ";
    $result = $this->dbC->query($sqlQuery);

    /**
     * Check the existence of the item
     */
    if ( $result->num_rows < 1 ) {
        $this->model->data('itemExists', false);
        return false;
    }

    $row = $result->fetch_assoc();

    $itemID = $row['item_id'];

    /**
     * Get the ownership history of the item
     */
    $ownershipM = new ownershipModel();
    $ownershipC = new ownershipController($ownershipM);
    $ownershipQuery = "
        SELECT
            ownership_id
            ,owner_type
            ,item_status
            ,date_of_possession
            ,date_of_release
        FROM tbl_ownerships
        WHERE
            item_id = $itemID
    ";
    $ownershipResult = $this->dbC->query($ownershipQuery);
    $ownershipList = array();
    while ( $ownershipRow = $ownershipResult->fetch_assoc() ) {
        array_push($ownershipList, array(
                'ID'            => $ownershipRow['ownership_id']
                ,'ownerType'    => $ownershipRow['owner_type']
                ,'itemOwner'    => $ownershipC->getOwnerName($ownershipRow['ownership_id'])
                ,'itemStatus'   => $ownershipRow['item_status']
                ,'dateOfPossession' => $ownershipRow['date_of_possession']
                ,'dateOfRelease'    => $ownershipRow['date_of_release']
                ,'ownershipStatus'  => $ownershipRow['date_of_release'] == '0000-00-00' ? 'active' : 'inactive'
            ));
    }


    /**
     * Setters
     */
    $this->model->data('itemExists', true);
    $this->model->data('itemArchiveStatus', $row['is_archived']);

    $this->model->data('itemID', $itemID);
    $this->model->data('itemSerialNo', $row['item_serial_no']);
    $this->model->data('itemModelNo', $row['item_model_no']);
    $this->model->data('itemName', $row['item_name']);
    $this->model->data('itemType', $row['item_type']);
    $this->model->data('itemState', $row['item_state']);
    $this->model->data('itemDescription', $row['item_description']);
    $itemDOP = ($row['itemDOP'] == '0000-00-00') ?
        'N/A' : $row['itemDOP'];
    $this->model->data('itemDateOfPurchase', $itemDOP);
    $this->model->data('itemQuantity', $row['quantity']);
    $this->model->data('itemQuantityUnit', $row['quantity_unit']);

    $this->model->data('itemSpecsProcessor', $row['processor']);
    $this->model->data('itemSpecsVideo', $row['video']);
    $this->model->data('itemSpecsDisplay', $row['display']);
    $this->model->data('itemSpecsWebcam', $row['webcam']);
    $this->model->data('itemSpecsAudio', $row['audio']);
    $this->model->data('itemSpecsNetwork', $row['network']);
    $this->model->data('itemSpecsUSBPorts', $row['usb_ports']);
    $this->model->data('itemSpecsMemory', $row['memory']);
    $this->model->data('itemSpecsStorage', $row['storage']);
    $this->model->data('itemSpecsOS', $row['os']);
    $this->model->data('itemSpecsSoftware', $row['software']);

    $this->model->data('packageID', $row['package_id']);
    $this->model->data('packageName', $row['package_name']);
    $this->model->data('packageSerialNo', $row['package_serial_no']);
    $this->model->data('packageDescription', $row['package_description']);
    $this->model->data('packageDateOfPurchase', $row['packageDOP']);

    $this->model->data('ownershipList', $ownershipList);
} //readItem




















/**
 * Read all items
 */
public function readAll () {
    $sqlQuery = "
        SELECT
            items.item_id
            ,items.item_serial_no
            ,items.item_model_no
            ,items.item_name
            ,items.item_state
            ,items.item_description
            ,items.date_of_purchase AS itemDOP
            ,items.quantity
            ,items.quantity_unit
            ,items.is_archived

            ,itemType.label AS item_type

            ,packages.package_name
            ,packages.package_serial_no
            ,packages.package_description
            ,packages.date_of_purchase AS packageDOP
        FROM tbl_items AS items
        LEFT JOIN
            lst_item_type AS itemType ON items.item_type = itemType.id
        LEFT JOIN
            tbl_packages AS packages ON items.package_id = packages.package_id
        ORDER BY
            items.item_type ASC
            ,items.date_of_purchase DESC
            ,items.is_archived ASC
    ";

    $itemList = array();

    $result = $this->dbC->query($sqlQuery);

    while ( $row = $result->fetch_assoc() ) {

        $itemID = $row['item_id'];
        $ownershipQuery = "
                SELECT
                    ownership_id
                    ,owner_type
                FROM tbl_ownerships
                WHERE
                    item_id = $itemID
                    AND date_of_release = '0000-00-00'
            ";
        $ownershipResult = $this->dbC->query($ownershipQuery);
        if ( $ownershipResult->num_rows > 0 ) {

            $ownershipRow = $ownershipResult->fetch_assoc();
            $ownershipID = $ownershipRow['ownership_id'];
            $ownerType = $ownershipRow['owner_type'];

            $ownershipM = new ownershipModel();
            $ownershipC = new ownershipController($ownershipM);
            $itemOwner = $ownershipC->getOwnerName($ownershipID);

        } else $itemOwner = 'None';

        /**
         * $itemOwner is the current owner of
         * the item
         */



        $itemDOP = $row['itemDOP'] == '0000-00-00' ?
            'N/A' : $row['itemDOP'];
        array_push($itemList, array(
                'itemID'        => $itemID
                ,'itemSerialNo' => $row['item_serial_no']
                ,'itemModelNo'  => $row['item_model_no']
                ,'itemName'     => $row['item_name']
                ,'itemType'     => $row['item_type']
                ,'itemState'    => $row['item_state']
                ,'itemDescription'  => $row['item_description']
                ,'itemDOP'          => $itemDOP
                ,'itemQuantity'     => $row['quantity']
                ,'itemQuantityUnit' => $row['quantity_unit']
                ,'itemArchiveStatus'    => $row['is_archived']

                ,'itemOwner'        => $itemOwner

                ,'packageName'      => $row['package_name']
                ,'packageSerialNo'  => $row['package_serial_no']
                ,'packageDescription'   => $row['package_description']
                ,'packageDOP'           => $row['packageDOP']
            ));
    }

    $this->model->data('itemList', $itemList);
} //readAll




}
