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

    $dbC = new databaseController();
    $ownshpC = new ownershipController();
    $ownerTypeC = new ownerTypeController();
    $errorC = new errorController();
    $logC = new logController();

    $itemStatus = $dbC->PDOStatement(array(
        'query'     => "INSERT INTO tbl_items(
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
                ,has_components
                ,component_of
            ) VALUES(
                ?,?,?,?,?,?,?,?,?,?,?,?
            )"
        ,'values'   => array(
                $datas['item-serial-no']
                ,$datas['item-model-no']
                ,$datas['item-name']
                ,intval($datas['item-type'])
                ,intval($datas['item-state'])
                ,$datas['item-description']
                ,$datas['item-quantity']
                ,$datas['item-quantity-unit']
                ,$datas['item-date-purchase']
                ,intval($datas['package-id'])
                ,isset($datas['has-component']) ? 1 : 0
                ,intval($datas['host-item-id'])
            )
        ));

    if ( $itemStatus ) {

        $itemID = $dbC->PDOLastInsertID();

        switch ( $ownerTypeC->idToLabel($datas['owner-type']) ) {
            case 'Employee':
                $ownerID = $datas['employee-id'];
                break;

            case 'Department':
                $ownerID = $datas['department-id'];
                break;

            case 'Guest':
                $ownerID = $datas['guest-id'];
                break;

            default:
                $ownerID = null;
        }

        if ( $ownerID != null ) {

            $ownshpStatus = $dbC->PDOStatement(array(
                'query'     => "INSERT INTO tbl_ownerships(
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
                ,'values'   => array(
                        $ownshpC->newOwnershipID()
                        ,intval($ownerID)
                        ,intval($datas['owner-type'])
                        ,intval($itemID)
                        ,intval($datas['item-state'])
                        ,$datas['date-of-possession']
                        ,'0000-00-00'
                    )
                ));

        }

        $logC->logItem($itemID, 'create');
        return $itemID;

    }

    return false;

} //createItem






































/**
 * For updating an existing item
 */
public function updateItem ($datas=array()) {

    $dbC = new databaseController();
    $ownshpC = new ownershipController();
    $ownerTypeC = new ownerTypeController();
    $errorC = new errorController();
    $logC = new logController();

    $itemUpdateStatus = $dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items
            SET
                    item_serial_no = ?
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
                $datas['item-serial-no']
                ,$datas['item-model-no']
                ,$datas['item-name']
                ,intval($datas['item-type'])
                ,intval($datas['item-state'])
                ,$datas['item-description']
                ,$datas['item-quantity']
                ,$datas['item-quantity-unit']
                ,$datas['item-date-purchase']
                ,intval($datas['package-id'])
                ,isset($datas['has-component']) ? 1 : 0
                ,intval($datas['host-item-id'])
                ,intval($datas['item-id'])
            )
        ));

    if ( $itemUpdateStatus ) {

        $logC->logItem($datas['item-id'], 'update', 'Some information of the item have been updated / changed');

        $ownerType = $ownerTypeC->idToLabel($datas['owner-type']);
        switch ( $ownerType ) {

            case 'Employee':
                $ownerID = $datas['employee-id'];
                break;

            case 'Department':
                $ownerID = $datas['department-id'];
                break;

            case 'Guest':
                $ownerID = $datas['guest-id'];
                break;

            default:
                $ownerID = null;

        }

        if ( $datas['current-ownership-id'] == ''
            && $ownerType != 'None'
            && $ownerID != null ) {

            //create new ownership
            $newOwnershipID = $ownshpC->newOwnership($datas);
            if ( $newOwnershipID == null )
                $errorC->logError('Failed to create new ownership for the item ( '.$datas['item-id'].' )');
            else {
                $logC->logItem($datas['item-id'], 'update', 'Item is now owned by '
                    .$ownshpC->getOwnerName($newOwnershipID));
            }

        } else if ( $datas['current-ownership-id'] != ''
            && $ownerID != null
            && (
                $datas['current-owner-type'] != $datas['owner-type']
                || $datas['current-owner-id'] != $ownerID
                ) ) {

            //end previous ownership
            $endOwnershipStatus = $ownshpC->endOwnership($datas['current-ownership-id']);

            if ( $endOwnershipStatus ) {

                //create new ownership
                $newOwnershipID = $ownshpC->newOwnership($datas);

                if ( $newOwnershipID == null )
                    $errorC->logError('Failed to create new ownership for the item ( '.$datas['item-id'].' )');
                else {
                    $logC->logItem($datas['item-id'], 'update', 'Ownership changed from '
                        .$ownshpC->getOwnerName($datas['current-ownership-id'])
                        .' to '
                        .$ownshpC->getOwnerName($newOwnershipID));
                }

            } else $errorC->logError('Failed to end ownership ( '.$datas['current-ownership-id'].' ).');

        } else if ( $datas['current-ownership-id'] != ''
            && $ownerType == 'None'
            && $ownerID == null ) {

            //end ownership of the item
            $endStatus = $ownshpC->endOwnership($datas['current-ownership-id']);

            if ( !$endStatus )
                $errorC->logError('Failed to end ownership ( '.$datas['current-ownership-id'].' ).');
            else
                $logC->logItem($datas['item-id'], 'update', 'Ownership changed to none');

        }

    } else $errorC->logError('Failed to update item ( '.$datas['item-id'].' ).');

    return array(
            'itemID'    => $datas['item-id']
            ,'success'  => $itemUpdateStatus ? true : false
        );

} //updateItem






































/**
 * For archiving an item which is almost
 * equivalent to delete but the
 * data will still exist on the database
 */
public function archiveItem ($itemID) {
    
    $logC = new logController();

    $result = $this->dbC->PDOStatement(array(
        'query' => "UPDATE tbl_items
            SET is_archived = ?
            WHERE item_id = ?"
        ,'values'   => array(
                1
                ,intval($itemID)
            )
        ));

    if ( $result ) {

        $logC->logItem($itemID, 'archive', 'Item has now been archived and can only be seen by an Administrator leveled user');
        header('location: '.URL_BASE.'items/view_all/');

    } else {

        $output = $GLOBALS['pageView']->getHeader()
            .'Error: Item deletion failed.<br />'
            .'<a href="'.URL_BASE.'items/view/'.$itemID.'/"><input type="button" value="Back" /></a>'
            .$GLOBALS['pageView']->getFooter();

    }

} //archiveItem





















public function idToLabel ($itemID) {
    $dbC = new databaseController();
    $results = $dbC->PDOStatement(array(
            'query'=>"SELECT * FROM tbl_items
                WHERE item_id = ?"
            ,'values'=>array(intval($itemID))
        ));
    if ( count($results) > 0 ) {
        $result = $results[0];
        $itemDetails = array(
            'id'            => $result['item_id']
            ,'name'         => $result['item_name']
            ,'serial_no'    => $result['item_serial_no']
            ,'model_no'     => $result['item_model_no']);
    } else $itemDetails = null;
    return $itemDetails;
} //idToLabel



















/**
 * Read item
 */
public function readItem ($itemID) {

    $dbC = new databaseController();

    $result = $dbC->PDOStatement(array(
        'query' => "SELECT
                items.item_id
                ,items.item_serial_no
                ,items.item_model_no
                ,items.item_name
                ,items.item_type
                ,items.item_state
                ,items.item_description
                ,items.date_of_purchase AS itemDOP
                ,items.quantity
                ,items.quantity_unit
                ,items.is_archived
                ,items.has_components
                ,items.component_of
                ,items.log

                ,packages.package_id
                ,packages.package_name
                ,packages.package_serial_no
                ,packages.package_description
                ,packages.date_of_purchase AS packageDOP
            FROM tbl_items AS items
            LEFT JOIN
                tbl_packages AS packages ON items.package_id = packages.package_id
            LEFT JOIN
                tbl_ownerships AS ownership ON items.item_id = ownership.item_id
            WHERE
                items.item_id = ?"
        ,'values'   => array(intval($itemID))
        ));

    /**
     * Check the existence of the item
     */
    if ( count($result) < 1 )
        $this->model->data('itemExists', false);
    else $this->model->data('itemExists', true);



    $row = count($result) > 0 ? $result[0] : null;
    $itemID = $row != null ? $row['item_id'] : '';

    $ownershipHistory = $this->getOwnershipHistory($itemID);
    foreach ( $ownershipHistory as $oh ) {
        if ( $oh['owner_DOR'] == '0000-00-00' ) {
            $coInfo = $oh;
        }
    }
    $componentList = $this->getComponents($itemID);

    $itemInformation = array(
            //item informations
            'item_id'               => $itemID
            ,'item_serial_no'       => isset($row)
                ? $row['item_serial_no']
                : ''
            ,'item_model_no'        => isset($row)
                ? $row['item_model_no']
                : ''
            ,'item_name'            => isset($row)
                ? $row['item_name']
                : ''
            ,'item_type'            => isset($row)
                ? $row['item_type']
                : ''
            ,'item_state'           => isset($row)
                ? $row['item_state']
                : ''
            ,'item_description'     => isset($row)
                ? $row['item_description']
                : ''
            ,'item_quantity'        => isset($row)
                ? $row['quantity']
                : '1'
            ,'item_quantity_unit'   => isset($row)
                ? $row['quantity_unit']
                : 'pc'
            ,'item_DOP'             => isset($row)
                ? $row['itemDOP']
                : date('Y-m-d')

            //package informations
            ,'package_id'           => isset($row)
                ? $row['package_id']
                : ''
            ,'package_name'         => isset($row)
                ? $row['package_name']
                : ''
            ,'package_serial_no'    => isset($row)
                ? $row['package_serial_no']
                : ''
            ,'package_description'  => isset($row)
                ? $row['package_description']
                : ''
            ,'package_DOP'          => isset($row)
                ? $row['packageDOP']
                : ''

            //ownership informations
            ,'ownership_history'    => isset($row)
                ? $ownershipHistory
                : ''
            ,'co_ownership_id'      => isset($coInfo)
                ? $coInfo['ownership_id']
                : ''
            ,'co_id'                => isset($coInfo)
                ? $coInfo['owner_id']
                : ''
            ,'co_name'              => isset($coInfo)
                ? $coInfo['owner_name']
                : ''
            ,'co_type'              => isset($coInfo)
                ? $coInfo['owner_type']
                : ''
            ,'co_DOP'               => isset($coInfo)
                ? $coInfo['owner_DOP']
                : date('Y-m-d')

            //additional informations
            ,'is_archived'          => isset($row)
                ? $row['is_archived']
                : ''
            ,'has_components'       => isset($row)
                ? $row['has_components']
                : 0
            ,'component_of'         => isset($row)
                ? $row['component_of']
                : ''
            ,'component_of_name'    => isset($row)
                ? $this->idToLabel($row['component_of'])
                : null
            ,'log'                  => isset($row)
                ? $row['log']
                : ''

            ,'component_list'       => isset($row)
                ? $componentList
                : ''
        );
    $this->model->data('itemInformation', $itemInformation);
    return $itemInformation;
} //readItem



























public function readAllItem () {

    $dbC = new databaseController();

    $items = $dbC->PDOStatement(array(
        'query' => "SELECT item_id FROM tbl_items"
        ));

    if ( is_array($items) && count($items) > 0 ) {

        $itemList = array();

        foreach ( $items as $item ) {

            $itemInfo = $this->readItem($item['item_id']);
            array_push($itemList, $itemInfo);

        }

    } else $itemList = null;

    return $itemList;

} //readAllItem






























public function searchItem ($query) {

    if ( $query == null ) {
        $this->model->data('itemList', null);
        return;
    }

    $dbC = new databaseController();

    $itemList = array();

    $results = $dbC->PDOStatement(array(
            'query'     => "SELECT * FROM tbl_items
                WHERE
                    (
                        item_serial_no LIKE ?
                        OR item_model_no LIKE ?
                        OR item_name LIKE ?
                    )
                    AND is_archived = 0
                    AND has_components = 1"
            ,'values'   => array(
                    "%$query%"
                    ,"%$query%"
                    ,"%$query%"
                )
        ));

    if ( count($results) > 0 ) {
        foreach ( $results as $result ) {
            $itemList[$result['item_id']] = array(
                    'serial_no'         => $result['item_serial_no']
                    ,'model_no'         => $result['item_model_no']
                    ,'name'             => $result['item_name']
                    ,'type'             => $result['item_type']
                    ,'state'            => $result['item_state']
                    ,'description'      => $result['item_description']
                    ,'quantity'         => $result['quantity']
                    ,'quantity_unit'    => $result['quantity_unit']
                    ,'dop'              => $result['date_of_purchase']
                    ,'component_of'     => $result['component_of']
                );
        }
    } else $itemList = null;

    $this->model->data('itemList', $itemList);

} //searchItem





































public function getOwnershipHistory ($itemID) {

    $ownshpM = new ownershipModel();
    $ownshpC = new ownershipController($ownshpM);

    $dbC = new databaseController();

    $ownshpResults = $dbC->PDOStatement(array(
        'query' => "SELECT
                ownership_id
                ,owner_id
                ,owner_type
                ,item_status
                ,date_of_possession
                ,date_of_release
            FROM tbl_ownerships
            WHERE
                item_id = ?
            ORDER BY date_of_possession DESC, date_of_release ASC, ownership_id DESC"
        ,'values'   => array(intval($itemID))
        ));

    $ownershipList = array();
    foreach ( $ownshpResults as $ownshpR ) {

        $ownerName = $ownshpC->getOwnerName($ownshpR['ownership_id']);
        array_push($ownershipList, array(
                'ownership_id'  => $ownshpR['ownership_id']
                ,'owner_id'     => $ownshpR['owner_id']
                ,'owner_type'   => $ownshpR['owner_type']
                ,'item_status'  => $ownshpR['item_status']
                ,'owner_DOP'    => $ownshpR['date_of_possession']
                ,'owner_DOR'    => $ownshpR['date_of_release']
                ,'owner_name'   => $ownerName
            ));

    }
    return $ownershipList;
} //getOwnershipHistory



























public function getComponents ($itemID) {

    $dbC = new databaseController();

    $componentList = array();
    $results = $dbC->PDOStatement(array(
        'query' => "SELECT
                item_id
                ,item_serial_no
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
            FROM tbl_items
            WHERE component_of = ?"
        ,'values'   => array(intval($itemID))
        ));

    if ( count($results) > 0 ) {

        foreach ( $results as $result ) {

            $packageDetails = $dbC->PDOStatement(array(
                'query' => "SELECT *
                    FROM tbl_packages
                    WHERE package_id = ?"
                ,'values'   => array(intval($result['package_id']))
                ));
            $package = count($packageDetails) > 0
                ? $packageDetails[0]
                : array(
                        'package_name'          => ''
                        ,'package_serial_no'    => ''
                        ,'package_description'  => ''
                        ,'date_of_purchase'     => ''
                    );

            array_push($componentList, array(
                    'item_id'           => $result['item_id']
                    ,'item_serial_no'   => $result['item_serial_no']
                    ,'item_model_no'    => $result['item_model_no']
                    ,'item_name'        => $result['item_name']
                    ,'item_type'        => $result['item_type']
                    ,'item_state'       => $result['item_state']
                    ,'item_description' => $result['item_description']
                    ,'quantity'         => $result['quantity']
                    ,'quantity_unit'    => $result['quantity_unit']
                    ,'item_dop'         => $result['date_of_purchase']

                    ,'package_id'       => $result['package_id']
                    ,'package_name'     => $package['package_name']
                    ,'package_serial_no'    => $package['package_serial_no']
                    ,'package_description'  => $package['package_description']
                    ,'package_dop'      => $package['date_of_purchase']

                    ,'is_archived'      => $result['is_archived']
                ));

        }

    } else $componentList = null;

    return $componentList;

} //getComponents



























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
                1
                ,0
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
