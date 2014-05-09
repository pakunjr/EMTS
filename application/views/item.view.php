<?php

class item_view {

    private $model;

    private $formModel;
    private $formView;

    public function __construct ($model) {
        $this->model = $model;

        $this->formModel = new form_model();
        $this->formView = new form_view($this->formModel);
    } //End function __construct

    public function itemForm ($action) {
        switch ( $action ) {
            case 'search_item':
                $this->getForm('item_search.php');
                break; //End case search_item

            case 'new_item':
                $this->getForm('item_new.php');
                break; //End case new_item

            case 'view_item':
                $this->getForm('item_view.php');
                break; //End case view_item

            case 'update_item':
                $this->getForm('item_update.php');
                break; //End case update_item

            case 'archive_item':
                //Archive the item, do not delete it.
                break; //End case archive_item

            default:
                echo 'You are accessing the item form but there is no form to display.';
        }
    } //End function itemForm

    public function getForm ($filename) {
        $file = FORMS_DIR. DS. $filename;
        if ( file_exists($file) ) require_once($file);
        else echo '<div style="color: #f00;">Error: The file for this form is missing. - ', $filename, '</div>';
    } //End function getForm

} //End class item_view
