<?php

Class Resetreq_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Function to add password reset token record to Table for further checking
     * 
     * @param int $vendor_id
     * @param int $partner_id
     * @return type
     */
    function add_reset_request($custId, $token) {
        $data = array('id' => false, 'cust_id' => $custId, 'token' => $token, 'req_date' => date('Y-m-d'), 'req_time' => date('h:i:s'));
        $this->db->insert('change_p_requests', $data);
    }

    /**
     * Function to check password reset token in database
     * 
     * @author Mujaffar 25-7
     * @param type $token
     */
    function check_token($token) {
        $result = $this->db->get_where('change_p_requests', array('token' => $token));
        return $result->row_array();
    }

}