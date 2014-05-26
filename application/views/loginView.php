<?php

class loginView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} //__construct

/**
 * Determine whether to show login form or
 * infomation and options for the currently
 * logged user.
 */
public function showLogin () {
    if ( $this->model->get('isAuthorized') ) {
        echo '<span>',$this->model->get('name'),'</span><br />'
            ,'<small><a href="',URL_BASE,'login/logout/">Logout</a></small>';
    } else {
        $this->displayLoginForm();
    }
} //showLogin

public function displayLoginForm () {
    $lf = new form(array(
            'auto_line_break'   => false
            ,'auto_label'       => true
        ));
    $html = $lf->openForm(array(
            'id'    => 'login-form'
            ,'action'   => URL_BASE.'login/validate/'
            ,'method'   => 'post'
            ,'enctype'  => 'multipart/form-data'
        ))
        .$lf->text(array('id'=>'username'))
        .$lf->password(array('id'=>'password', 'auto_line_break'=>true))
        .$lf->submit(array('value'=>'Login'))
        .$lf->closeForm();
    echo $html;
} //displayLoginForm

}
