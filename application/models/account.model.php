<?php

class account_model () {

    public function __construct () {

    } //End function __construct

    public function generateSalt () {
        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $raw_salt = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        return hash('sha256', $raw_salt);
    } //End function generateSalt

} //End class account_model
