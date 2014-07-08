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

    $errM = new errorModel();
    $errC = new errorController($errM);

    $logC = new logController();


    $ownshpM = new ownershipModel();
    $ownshpC = new ownershipController($ownshpM);

    if ( !is_array($datas) ) {
        $errMsg = 'There was an error in saving. Item is not saved.';
        echo $errMsg;
        $errC->logError($errMsg);
        return false;
    }



    //Single item informations
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
    $itemPackageID = $itemPackageID != null && $itemPackageID != '' ?
        $itemPackageID : '0';

    //Component information
    $hasComponent = isset($datas['has-component']) ? '1' : '0';
    $componentOf = isset($datas['is-component']) ?
        $datas['item-search-id'] : '0';



    //Ownership information
    $ownerTypeID = $datas['owner-type'];
    $employeeID = $datas['person-search-id'];
    $departmentID = $datas['department-search-id'];
    $guestID = $datas['guest-search-id'];
    $ownerDateOfPossession = $datas['owner-date-of-possession'];

    $ownerTypeM = new ownerTypeModel();
    $ownerTypeC = new ownerTypeController($ownerTypeM);
    $ownerType = strtolower($ownerTypeC->decodeID($ownerTypeID));

    if ( $ownerType == 'employee' ) $ownerID = $employeeID;
    else if ( $ownerType == 'department' ) $ownerID = $departmentID;
    else if ( $ownerType == 'guest' ) $ownerID = $guestID;
    else $ownerID = null;



    /**
     * Have the date of purchase of the item
     * to be identical to the date of purchase
     * of the package it belongs to
     */
    if ( $itemPackageID != null
            && $itemPackageID != ''
            && $itemPackageID != '0' ) {

        $results = $this->dbC->PDOStatement(array(
            'query' => "SELECT date_of_purchase
                FROM tbl_packages
                WHERE package_id = ?"
            ,'values'   => array(array('int', $itemPackageID))
            ));
        $itemDatePurchase = $results[0]['date_of_purchase'];

    }





    /**
     * Save the item information
     */
    $itemResult = $this->dbC->PDOStatement(array(
            'query' => "INSERT INTO tbl_items(
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
                    ,has_components
                    ,component_of
                ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)"
            ,'values'   => array(
                    $itemSerialNo
                    ,$itemModelNo
                    ,$itemName
                    ,array('int', $itemType)
                    ,array('int', $itemState)
                    ,$itemDescription
                    ,$itemQuantity
                    ,$itemQuantityUnit
                    ,$itemDatePurchase
                    ,array('int', $itemPackageID)
                    ,array('int', 0)
                    ,array('int', $hasComponent)
                    ,array('int', $componentOf)
                )
        )) ? '1' : '0';

    if ( $itemResult != '1' ) {
        $itemID = null;
        $errC->logError('Item failed to be created');
        return false;
    }

    $itemID = $this->dbC->PDOLastInsertID();
    $logC->logItem($itemID, 'create');




    /**
     * Save ownership of the item if it is set
     */
    if ( $ownerType != 'none' ) {
        $ownershipID = $ownshpC->newOwnershipID();
        $ownershipResult = $this->dbC->PDOStatement(array(
            'query' => "INSERT INTO tbl_ownerships(
                    ownership_id
                    ,owner_id
                    ,owner_type
                    ,item_id
                    ,item_status
                    ,date_of_possession
                    ,date_of_release
                ) VALUES(?,?,?,?,?,?,?)"
            ,'values'   => array(
                    $ownershipID
                    ,array('int', $ownerID)
                    ,array('int', $ownerTypeID)
                    ,array('int', $itemID)
                    ,array('int', $itemState)
                    ,$ownerDateOfPossession
                    ,'0000-00-00'
                )
            )) ? '1' : '0';


        if ( $ownershipResult != '1' )
            $errC->logError('Item ownership failed to save while item is created');

    } else $ownershipResult = 'n/a';


    return $itemID;

} //createItem






































/**
 * For updating an existing item
 */
public function updateItem ($datas=array()) {

    $errM = new errorModel();
    $errC = new errorController($errM);

    $ownshpM = new ownershipModel();
    $ownshpC = new ownershipController($ownshpM);

    $dbC = $this->dbC;

    $logC = new logController();
    $logNotes = '';

    $itemID = $datas['item-id'];

    /**
     * Check existence of the item and the validity of
     * passed datas
     */
    $check = $dbC->PDOStatement(array(
        'query' => "SELECT item_id FROM tbl_items WHERE item_id = ? AND is_archived = 0 LIMIT 1"
        ,'values'   => array(
                array('int', $itemID)
            )
        ));
    if ( count($check) < 1 ) {
        $errC->logError('Item update failed, item doesn\'t exist or it is already archived ( '.$itemID.' )');
        return false;
    } else if ( !is_array($datas) ) {
        $errC->logError('Item update failed, data sent were not valid ( <a href="'.URL_BASE.'items/view/'.$itemID.'/"><input type="button" value="View Item" /></a> )');
        return false;
    }


    //Single item informations
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
    $itemPackageID = $itemPackageID != null && $itemPackageID != '' ?
        $itemPackageID : '0';


    //Component information
    $hasComponent = isset($datas['has-component']) ? '1' : '0';
    $componentOf = isset($datas['is-component']) ? $datas['item-search-id'] : '0';



    //Ownership information
    $ownerTypeID = $datas['owner-type'];
    $employeeID = $datas['person-search-id'];
    $departmentID = $datas['department-search-id'];
    $guestID = $datas['guest-search-id'];
    $ownerDateOfPossession = $datas['owner-date-of-possession'];

    $ownerTypeM = new ownerTypeModel();
    $ownerTypeC = new ownerTypeController($ownerTypeM);
    $ownerType = strtolower($ownerTypeC->decodeID($ownerTypeID));

    if ( $ownerType == 'employee' ) $ownerID = $employeeID;
    else if ( $ownerType == 'department' ) $ownerID = $departmentID;
    else if ( $ownerType == 'guest' ) $ownerID = $guestID;
    else $ownerID = null;



    /**
     * Update item information
     */
    $infoResult = $dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items
            SET item_serial_no = ?
                ,item_model_no = ?
                ,item_name = ?
                ,item_type = ?
                ,item_state = ?
                ,item_description = ?
                ,quantity = ?
                ,quantity_unit = ?
                ,date_of_purchase = ?
                ,package_id = ?
                ,has_components = ?
                ,component_of = ?
            WHERE item_id = ?"
        ,'values'   => array(
                $itemSerialNo
                ,$itemModelNo
                ,$itemName
                ,array('int', $itemType)
                ,array('int', $itemState)
                ,$itemDescription
                ,$itemQuantity
                ,$itemQuantityUnit
                ,$itemDatePurchase
                ,array('int', $itemPackageID)
                ,array('int', $hasComponent)
                ,array('int', $componentOf)
                ,array('int', $itemID)
            )
        ));




    /**
     * Get the active ownership of the item then
     * proceed with the update of the ownership
     * if the owner is new
     */

    $result = $dbC->PDOStatement(array(
        'query' => "SELECT
                ownership_id
                ,owner_type
                ,owner_id
                ,date_of_possession
            FROM tbl_ownerships
            WHERE item_id = ? AND date_of_release = '0000-00-00'"
        ,'values'   => array(array('int', $itemID))
        ));
    $row = count($result) > 0 ? $result[0] : array();
    if ( (count($row) > 0 && (
                $ownerTypeID != $row['owner_type']
                || $ownerID != $row['owner_id']
            ))
            || count($row) < 1 ) {



        if ( count($row) > 0 && $ownerDateOfPossession < $row['date_of_possession'] ) {
            $possessionDate = date('Y-m-d');
        } else {
            $possessionDate = $ownerDateOfPossession;
        }


        /**
         * Proceed with the update of the ownership
         */

        if ( count($row) > 0 ) {
            $ownshpID = $row['ownership_id'];
            $result = $dbC->PDOStatement(array(
                'query' => "UPDATE tbl_ownerships
                    SET date_of_release = ?
                    WHERE ownership_id = ?"
                ,'values'   => array(
                        $possessionDate
                        ,$ownshpID
                    )
                ));
            $logNotes = PHP_EOL.'The ownership was released from '.$ownshpC->getOwnerName($ownshpID);
        }

        if ( $ownerType != 'none' ) {

            $ownshpID = $ownshpC->newOwnershipID();
            $ownershipResult = $dbC->PDOStatement(array(
                'query' => "INSERT INTO tbl_ownerships(
                        ownership_id
                        ,owner_id
                        ,owner_type
                        ,item_id
                        ,item_status
                        ,date_of_possession
                        ,date_of_release
                    ) VALUES(
                        ?,?,?,?,?,?,?
                    )"
                ,'values' => array(
                        $ownshpID
                        ,array('int', $ownerID)
                        ,array('int', $ownerTypeID)
                        ,array('int', $itemID)
                        ,array('int', $itemState)
                        ,$possessionDate
                        ,'0000-00-00'
                    )
                ));


            if ( !$ownershipResult ) {
                $errC->logError('Ownership update failed. Item ID : ('.$itemID.', '.$itemName.')');
            }

            $logNotes .= PHP_EOL.'The ownership was transferred to '.$ownshpC->getOwnerName($ownshpID);

        } else
            $logNotes .= PHP_EOL.'There is no current owner of the item';

    }


    $logC->logItem($itemID, 'update', $logNotes);

    return $itemID;

} //updateItem



































/**
 * For archiving an item which is
 * equivalent for delete but the
 * data will still exist on the database
 */
public function archiveItem ($itemID) {
    $errM = new errorModel();
    $errC = new errorController($errM);

    $logC = new logController();

    $result = $this->dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items
            SET is_archived = ?
            WHERE item_id = ?"
        ,'values'   => array(
                array('int', 1)
                ,array('int', $itemID)
            )
        ));

    if ( !$result ) {
        echo 'Error: Item deletion failed.';
        $errC->logError('Archiving of the item failed ( '.$itemID.' )');
        return false;
    }

    $logC->logItem($itemID, 'archive');

    header('location: '.URL_BASE.'items/view_all/');
} //archiveItem


















/**
 * Read item
 */
public function readItem ($itemID) {

    $result = $this->dbC->PDOStatement(array(
        'query' => "SELECT
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

                ,packages.package_id
                ,packages.package_name
                ,packages.package_serial_no
                ,packages.package_description
                ,packages.date_of_purchase AS packageDOP
            FROM tbl_items AS items
            LEFT JOIN
                lst_item_type AS itemType ON items.item_type = itemType.id
            LEFT JOIN
                tbl_packages AS packages ON items.package_id = packages.package_id
            LEFT JOIN
                tbl_ownerships AS ownership ON items.item_id = ownership.item_id
            WHERE
                items.item_id = ?"
        ,'values'   => array(array('int', $itemID))
        ));

    /**
     * Check the existence of the item
     */
    if ( count($result) < 1 ) {
        $this->model->data('itemExists', false);
        return false;
    }

    $row = $result[0];

    $itemID = $row['item_id'];

    /**
     * Get the ownership history of the item
     */
    $ownershipM = new ownershipModel();
    $ownershipC = new ownershipController($ownershipM);
    $ownershipResults = $this->dbC->PDOStatement(array(
        'query' => "SELECT
                ownership_id
                ,owner_type
                ,item_status
                ,date_of_possession
                ,date_of_release
            FROM tbl_ownerships
            WHERE
                item_id = ?
            ORDER BY date_of_possession DESC, date_of_release ASC, ownership_id DESC"
        ,'values'   => array(array('int', $itemID))
        ));


    $ownershipList = array();
    $currentOwner = 'None';
    foreach ( $ownershipResults as $ownershipRow ) {

        $ownerName = $ownershipC->getOwnerName($ownershipRow['ownership_id']);

        array_push($ownershipList, array(
                'ID'            => $ownershipRow['ownership_id']
                ,'ownerType'    => $ownershipRow['owner_type']
                ,'itemOwner'    => $ownerName
                ,'itemStatus'   => $ownershipRow['item_status']
                ,'dateOfPossession' => $ownershipRow['date_of_possession']
                ,'dateOfRelease'    => $ownershipRow['date_of_release']
                ,'ownershipStatus'  => $ownershipRow['date_of_release'] == '0000-00-00' ? 'active' : 'inactive'
            ));

        if ( $ownershipRow['date_of_release'] == '0000-00-00' )
            $currentOwner = $ownerName;

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


    $this->model->data('packageID', $row['package_id']);
    $this->model->data('packageName', $row['package_name']);
    $this->model->data('packageSerialNo', $row['package_serial_no']);
    $this->model->data('packageDescription', $row['package_description']);
    $this->model->data('packageDateOfPurchase', $row['packageDOP']);

    $this->model->data('ownershipList', $ownershipList);
    $this->model->data('currentOwner', $currentOwner);
} //readItem




















/**
 * Read all items
 */
public function readAll () {

    $loginM = new loginModel();

    $accessLevel = $loginM->data('accessLevel');

    if ( $accessLevel != 'Administrator' ) {
        $whereClause = "WHERE items.is_archived = ?";
        $value = array(array('int', 0));
    } else {
        $whereClause = '';
        $value = array();
    }

    $results = $this->dbC->PDOStatement(array(
        'query' => "SELECT
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
            $whereClause
            ORDER BY
                items.is_archived ASC
                ,items.item_type ASC
                ,items.date_of_purchase DESC"
        ,'values'   => $value
        ));

    $itemList = array();
    foreach ( $results as $row ) {

        $itemID = $row['item_id'];
        $ownershipResult = $this->dbC->PDOStatement(array(
            'query' => "SELECT
                    ownership_id
                    ,owner_type
                FROM tbl_ownerships
                WHERE
                    item_id = ?
                    AND date_of_release = ?
                LIMIT 1"
            ,'values'   => array(
                    array('int', $itemID)
                    ,'0000-00-00'
                )
            ));

        if ( count($ownershipResult) > 0 ) {

            $ownershipRow = $ownershipResult[0];
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






















public function searchPackageItem ($searchQuery) {
    $dbC = new databaseController();

    $itemList = array();

    $results = $dbC->PDOStatement(array(
        'query' => "SELECT
                item_id
                ,item_serial_no
                ,item_model_no
                ,item_name
                ,date_of_purchase
            FROM tbl_items
            WHERE
                has_components = ?
                AND is_archived = ?
                AND (
                    item_name LIKE ?
                    OR item_serial_no LIKE ?
                    OR item_model_no LIKE ?
                    )"
        ,'values'   => array(
                array('int', 1)
                ,array('int', 0)
                ,"%$searchQuery%"
                ,"%$searchQuery%"
                ,"%$searchQuery%"
            )
        ));

    if ( count($results) < 1 ) {
        $this->model->data('itemSearch', false);
        return;
    }

    foreach ( $results as $result ) {
        array_push($itemList, array(
                'itemID' => $result['item_id']
                ,'serialNo' => $result['item_serial_no']
                ,'modelNo' => $result['item_model_no']
                ,'name' => $result['item_name']
                ,'dateOfPurchase' => $result['date_of_purchase']
            ));
    }

    $this->model->data('itemSearch', $itemList);
} //searchPackageItem




}
