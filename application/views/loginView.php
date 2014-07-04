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
public function renderForm ($echo=false) {
    if ( $this->model->data('isAuthorized') ) {
        $formContent = '<span >Hello <b>'.$this->model->data('firstname').'</b></span><br />'
            .'<small style="display: inline-block; margin: 3px 0px 0px 0px; padding: 0px;"><a href="'.URL_BASE.'login/logout/"><input type="button" value="Logout" /></a></small>';
    } else {
        $lf = new form(array(
                'auto_line_break'   => false
                ,'auto_label'       => true
            ));

        $formContent = $lf->openForm(array(
                'id'    => 'login-form'
                ,'action'   => URL_BASE.'login/validate/'
                ,'method'   => 'post'
                ,'enctype'  => 'multipart/form-data'
            ))
            .$lf->text(array('id'=>'username','label'=>'username / email address','placeholder'=>'username or email address'))
            .$lf->password(array('id'=>'password','auto_line_break'=>true))
            .$lf->submit(array('value'=>'Login'))
            .$lf->closeForm();
    }

    $form = '<span id="login-module">'
        .$formContent
        .'</span>';

    if ( !$echo ) return $form;
    echo $form;
} //renderForm




}
