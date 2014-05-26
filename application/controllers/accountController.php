<?php

class accountController {

private $model;


public function __construct ($model) {
    $this->model = $model;
} //__construct



public function generateSalt () {
    $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    $salt = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
    return hash('sha256', $salt);
} //generateSalt

}
