<?php

class login_view {

    private $model;

    public function __construct ($model) {
        $this->model = $model;
    } //End function __construct

    public function renderOutput () {
        if ( $this->model->getData('login_status') ) {
            echo '<div id="login-form">Welcome '
                , '<span id="user">'
                , $this->model->getData('firstname'), ' '
                , $this->model->getData('lastname'), ' '
                , '<br /><small class="small">'
                , $this->model->getData('occupation')
                , '</small>'
                , '<div id="user-options">'
                , '<a href="', URL_BASE, 'account/logout/">Logout</a>'
                , '</div>'
                , '</span>'
                , '</div>';
        } else $this->loginForm();
    } //End function renderOutput

    public function loginForm () {
        $formModel = new form_model(array(
                'auto_label'      => true
                , 'auto_line_break' => false
            ));
        $formView = new form_view($formModel);
        $formOutput = $formView->openForm(array(
                'id'    => 'login-form'
                , 'method'  => 'post'
                , 'action'  => URL_BASE. 'account/login/validate/'
                , 'enctype' => 'multipart/form-data'
            ))
            . '<span class="field-column">'
            . $formView->text(array('id'=>'username'))
            . $formView->password(array('id'=>'password'))
            . '</span>'
            . $formView->submit(array('value'=>'Login'))
            . $formView->closeForm();
        echo $formOutput;
    } //End function loginForm

} //End class login_view
