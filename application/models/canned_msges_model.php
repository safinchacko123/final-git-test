<?php

Class Canned_msges_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Function to get canned message record details depending upon id
     * 
     * @param int $id
     * @return type
     */
    function getMsg($id) {
        $result = $this->db->get_where('canned_messages', array('id' => $id));
        return $result->row();
    }
    
}