<?php

Class Role_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    function get_role($role_id) {
        $result = $this->db->get_where('roles', array('id' => $role_id));
        return $result->row_array();
    }

    function get_byname($role_text) {
        $result = $this->db->get_where('roles', array('role_name' => $role_text));
        return $result->row_array();
    }

}