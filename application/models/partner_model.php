<?php

Class Partner_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Method to create new partner
     * 
     * @author  Mujaffar S
     * @param type $data
     * @return type
     */
    function create($data) {
        try {
            $this->db->insert('partners', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            return 'dbError';
        }
    }

    /**
     * Function to check Partner Unique id
     * @param type $uniqueId
     * @return type
     */
    function checkUniqueId($uniqueId) {
        return $this->db->where('unique_id', $uniqueId)->get('partners')->row();
    }

    /**
     * Function to check Partner Unique id
     * @param type $uniqueId
     * @return type
     */
    function getUniqueId($partnerId) {
        return $this->db->where('customer_id', $partnerId)
                        ->get('partners')->row();
    }

    /**
     * Function to create new unique id for partner
     * 
     * @author  Mujaffar s  2 Sep 2015
     * @param type $uniqueId
     * @return type
     */
    function setUniqueId($data) {
        $this->db->where('customer_id', $data['customer_id']);
        $this->db->update('partners', array('unique_id' => $data['unique_id']));
    }

    /**
     * Function to add partner document related record in documents table
     * 
     * @author  Mujaffar s  5 Sep 2015
     * @param int $partnerId
     * @return type
     */
    function createDocRec($partnerId, $doc_type, $file_name) {
        $this->db->where('customer_id', $data['customer_id']);
        $this->db->update('partners', array('unique_id' => $data['unique_id']));
        $data = array('partner_id' => $partnerId, 'document_type' => $doc_type, 'path' => '/uploads/docs', 'name' => $file_name);
        try {
            $this->db->insert('partner_documents', $data);
        } catch (Exception $e) {
            return 'dbError';
        }
    }
    
    function createDocument($partnerId, $doc_type, $file_name) {
        $data = array('partner_id' => $partnerId, 'document_type' => $doc_type, 'path' => '/uploads/docs', 'name' => $file_name);
        try {
            $this->db->insert('partner_documents', $data);
        } catch (Exception $e) {
            return 'dbError';
        }
    }
    
}
