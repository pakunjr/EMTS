<?php

class personView {


private $model;
private $controller;


public function __construct ($model) {
    $this->model = $model;
    $this->controller = new personController($this->model);
} //__construct



public function renderPage ($controller, $action=null, $extra=null) {
    $lm = new loginModel();
    if ( !$lm->data('isAuthorized') ) {
        $GLOBALS['pageView']->pageError('403');
        return false;
    }

    switch ( $controller ) {
        case null:
            $this->renderFrontpage(true);
            break;



        case 'search_employee':
            $this->controller->searchPerson($action, 'employee');
            $this->renderSearch(true);
            break;


        case 'search_guest':
            $this->controller->searchPerson($action, 'guest');
            $this->renderSearch(true);
            break;



        case 'registration':
            $GLOBALS['pageView']->getHeader();
            $this->registrationForm($action, true);
            $GLOBALS['pageView']->getFooter();
            break;


        default:
            $GLOBALS['pageView']->pageError('404');
    }
} //renderPage




public function renderFrontpage ($echo=false) {
    $frontpage = '- Underconstruction';

    if ( !$echo ) return $frontpage;
    echo $frontpage;
} //renderFrontpage





/**
 * Render search results
 */
public function renderSearch ($echo=false) {

    $output = '<table>'
        .'<tr>'
            .'<th>Name</th>'
            .'<th>Gender</th>'
            .'<th>Birthdate</th>'
            .'<th>Email Address</th>'
        .'</tr>';

    $searchList = $this->model->data('searchList');

    if ( !is_array($searchList) ) return false;

    foreach ( $searchList as $info ) {
        $output .= '<tr class="search-data">'
            .'<td>'
                .'<input class="prime-data" type="hidden" value="'.$info['id'].'" />'
                .'<span class="prime-label">'.$info['name'].'</span>'
            .'</td>'
            .'<td>'.$info['gender'].'</td>'
            .'<td>'.$info['birthdate'].'</td>'
            .'<td>'.$info['emailAddress'].'</td>'
            .'</tr>';
    }

    $output .= '</table>';

    if ( !$echo ) return $output;
    echo $output;

} //renderSearch







/**
 * Registration form
 */
public function registrationForm ($type='employee', $echo=false) {
    $f = new form(array(
            'auto_line_break'   => true
            ,'auto_label'       => true
        ));

    if ( $type != 'employee' && $type != 'guest' ) $type = 'employee';


    if ( $type == 'employee' ) {
        $additionalInfo = $f->openFieldset(array('legend'=>'Employee Information'))
            .'<div class="row">'
                .'<span class="column">'
                .$f->text(array('id'=>$type.'-occupation','label'=>'Occupation'))
                .$f->select(array('id'=>$type.'-employment-status','label'=>'Employment Status','select_options'=>array(
                        'Permanent'     => 'permanent'
                        ,'Probationary' => 'probationary'
                        ,'Contractual'  => 'contractual'
                        ,'Part - Time'  => 'part-time'
                    )))
                .'</span>'
            .'</div>'
            .$f->closeFieldset();
    } else if ( $type == 'guest' ) {
        $additionalInfo = $f->openFieldset(array('legend'=>'Guest Information'))
            .'<div class="row">'
                .'<span class="column">'
                .$f->text(array('id'=>$type.'-occupation'))
                .$f->text(array('id'=>$type.'-company'))
                .$f->textarea(array('id'=>$type.'-company-details'))
                .'</span>'
            .'</div>'
            .$f->closeFieldset();
    } else $additionalInfo = '';


    $regForm = $f->openForm(array(
            'id'    => $type.'-registration-form'
            ,'method'   => 'post'
            ,'action'   => ''
            ,'enctype'  => 'multipart/form-data'
        ))
        .$f->openFieldset(array('legend'=>'Personal Identification Information'))
        .'<div class="row">'
            .'<span class="column">'
            .$f->text(array('id'=>$type.'-firstname','label'=>'Firstname'))
            .$f->text(array('id'=>$type.'-middlename','label'=>'Middlename'))
            .$f->text(array('id'=>$type.'-lastname','label'=>'Lastname'))
            .$f->text(array('id'=>$type.'-suffix','label'=>'Suffix'))
            .$f->select(array('id'=>$type.'-gender','label'=>'Gender','select_options'=>array(
                    'Male'      => 'm'
                    ,'Female'   => 'f'
                )))
            .$f->text(array('id'=>$type.'-birthdate','label'=>'Birthdate','class'=>'datepicker'))
            .'</span>'


            .'<span class="column">'
            .$f->textarea(array('id'=>$type.'-home-address','label'=>'Home Address'))
            .$f->textarea(array('id'=>$type.'-current-address','label'=>'Current Address'))
            .$f->textarea(array('id'=>$type.'-contact-address','label'=>'Contact Address'))
            .'</span>'


            .'<span class="column">'
            .$f->text(array('id'=>$type.'-email-address','label'=>'Email Address'))
            .$f->text(array('id'=>$type.'-mobile-no-a','label'=>'Mobile No. A'))
            .$f->text(array('id'=>$type.'-mobile-no-b','label'=>'Mobile No. B'))
            .$f->text(array('id'=>$type.'-tel-no','label'=>'Telephone No.'))
            .'</span>'
        .'</div>'
        .$f->closeFieldset()
        .$additionalInfo
        .$f->submit(array('value'=>'Register'))
        .$f->closeForm();

    if ( !$echo ) return $regForm;
    echo $regForm;
} //registrationForm



}
