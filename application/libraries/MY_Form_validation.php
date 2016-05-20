<?php

class MY_Form_validation extends CI_Form_validation {

    function __construct($config = array()) {
        parent::__construct($config);
    }

    function error_array() {
        if (count($this->_error_array) === 0)
            return FALSE;
        else
            return $this->_error_array;
    }

    public function validate_time_12hr($str) {
        $this->CI->form_validation->set_message('validate_time_12hr', 'The %s field must be a valid time.');

        return (!preg_match("/^(?:0[1-9]|1[0-2]):[0-5][0-9] (am|pm|AM|PM)$/", $str)) ? FALSE : TRUE;
    }

}
