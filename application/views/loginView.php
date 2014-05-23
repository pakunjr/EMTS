<?php

class loginView {

private $model;

public function __construct ($model) {
    $this->model = $model;
} // __construct

/**
 * Determine whether to show login form or
 * infomation and options for the currently
 * logged user.
 */
public function showLogin () {
    if ( $this->model->getData('isAuthorized') ) {

    } else {
        $this->displayLoginForm();
    }
} // showLogin

public function displayLoginForm () {
    $lf = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));
    $html = $lf->openForm(array(
            'id'    => 'login-form'
            ,'enctype'  => 'text/plain'
            ,'action'   => ''
            ,'method'   => 'post'
        ))
        .$lf->text(array('id'=>'username'))
        .$lf->password(array('id'=>'password'))
        .$lf->submit(array('value'=>'Login'))
        .$lf->closeForm();
    echo $html;
} // displayLoginForm

}
