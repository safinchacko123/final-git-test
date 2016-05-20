<?php

Class Mapvrvt_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Function to map vendor with venture
     * 
     * @param int $vendor_id
     * @param int $venture_id
     * @return type
     */
    function map_vr_vt($vendor_id, $venture_id) {
        $data = array('id' => false, 'vendor_id' => $vendor_id, 'venture_id' => $venture_id);
        $this->db->insert('map_vr_vt', $data);
    }
    
    function get_vr_vt($vendor_id) {
        $result = $this->db->get_where('map_vr_vt', array('vendor_id' => 4));
        return $result->result();
    }
    
}