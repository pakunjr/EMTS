<?php

$file = dirname(dirname(dirname(__FILE__))). DIRECTORY_SEPARATOR. 'library'. DIRECTORY_SEPARATOR. 'bootstrap.php';
if ( file_exists($file) ) require_once($file);
else {
    echo '<div style="font-size: 11pt; color: #f00;">Error: Bootstrap file not found.</div>';
    exit();
}

if ( isset($_GET['search'])
        && isset($_GET['query']) ) {

    $dbModel = new database_model();
    $dbController = new database_controller($dbModel);

    $query = $_GET['query'];

    switch ( $_GET['search'] ) {
        case 'item':
            $result = $dbController->query("
                    SELECT
                        item.item_id
                        , item.item_name
                        , item.item_serial_no
                        , item.item_model_no
                        , item.item_description
                        , item.date_of_purchase
                        , itemType.label
                    FROM tbl_items AS item
                    LEFT JOIN lst_item_type AS itemType
                        ON item.item_type = itemType.id
                    WHERE
                        item.item_name LIKE '%$query%'
                        OR item.item_serial_no LIKE '%$query%'
                        OR item.item_model_no LIKE '%$query%'
                        OR itemType.label LIKE '%$query%'
                ");

            $resultOutput = '<tr>'
                . '<th>Item Name</th>'
                . '<th>Serial No.</th>'
                . '<th>Model No.</th>'
                . '<th>Item Type</th>'
                . '<th>Item Description</th>'
                . '<th>Date of Purchase</th>'
                . '</tr>';

            while ( $row = $result->fetch_assoc() ) {
                $resultOutput .= '<tr class="search-data">'
                    . '<td>'
                    . '<input type="hidden" class="valuable-data" value="'. $row['item_id']. '" />'
                    . '<span class="search-result-item-name">'
                    . $row['item_name']
                    . '</span>'
                    . '</td>'
                    . '<td class="search-result-item-serialno">'. $row['item_serial_no']. '</td>'
                    . '<td class="search-result-item-modelno">'. $row['item_model_no']. '</td>'
                    . '<td>'. $row['label']. '</td>'
                    . '<td>'. $row['item_description']. '</td>'
                    . '<td>'. $row['date_of_purchase']. '</td>'
                    . '</tr>';
            }
            break;
        case 'package':
            $result = $dbController->query("
                    SELECT package_name
                        , package_serial_no
                        , package_description
                        , date_of_purchase
                    FROM tbl_packages
                    WHERE package_name LIKE '%$query%'
                        OR package_serial_no LIKE '%$query%'
                        OR package_description lIKE '%$query%'
                        OR date_of_purchase LIKE '%$query%'
                ");

            $resultOutput = '<tr>'
                . '<th>Package Name</th>'
                . '<th>Serial No.</th>'
                . '<th>Description</th>'
                . '<th>Date of Purchase</th>'
                . '</tr>';

            while ( $row = $result->fetch_assoc() ) {
                $resultOutput .= '<tr>'
                    . '<td>'. $row['package_name']. '</td>'
                    . '<td>'. $row['package_serial_no']. '</td>'
                    . '<td>'. $row['package_description']. '</td>'
                    . '<td>'. $row['date_of_purchase']. '</td>'
                    . '</tr>';
            }

            break;

        case 'person':
            $result = $dbController->query("
                    SELECT *
                    FROM tbl_persons
                ");

            $resultOutput = '';

            break;

        default:
            echo '<div style="font-size: 11pt; color: #f00;">Search Error: Invalid search entry.</div>';
    }

    /**
     * Display HTML Output.
     */
    $output = '<div class="your-query-is">Your query is <b>"'
        . $_GET['query']
        . '"</b></div>'
        . '<table id="tmp-search-results">'
        . $resultOutput
        . '</table>';
    echo $output;
}
