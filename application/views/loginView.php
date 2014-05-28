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
    ob_start();

    echo '<span id="login-module">';

    if ( $this->model->get('isAuthorized') ) {
        echo '<span >Hello <b>',$this->model->get('firstname'),'</b></span><br />'
            ,'<small style="display: inline-block; margin: 3px 0px 0px 0px; padding: 0px;"><a href="',URL_BASE,'login/logout/"><input type="button" value="Logout" /></a></small>';
    } else {
        $this->displayLoginForm();
    }

    echo '</span>';
    $htmlLoginContents = ob_get_contents();
    ob_end_flush();
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
        .$lf->password(array('id'=>'password','auto_line_break'=>true))
        .$lf->submit(array('value'=>'Login'))
        .$lf->closeForm();
    echo $html;
} //displayLoginForm

}
