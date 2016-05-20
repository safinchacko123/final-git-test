<?php

Class Mapvrpr_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Function to map vendor with partner
     * 
     * @param int $vendor_id
     * @param int $partner_id
     * @return type
     */
    function map_vr_pr($vendor_id, $partner_id) {
        $data = array('id' => false, 'vendor_id' => $vendor_id, 'partner_id' => $partner_id);
        $this->db->insert('map_vr_pr', $data);
    }

    /**
     * Check whether pending actions present for vendor-partner mapping
     * 
     * @param type $vendor_id
     * @param type $customer_id
     */
    function check_mapping($vendor_id, $partner_id) {
        $this->db->select('*');
        $this->db->where('vendor_id', $vendor_id)
                ->where('partner_id', $partner_id);
        $result = $this->db->get('map_vr_pr');
        return $result->result();
    }

    /**
     * Method to create mapping record
     * 
     * @author  Mujaffar s  31 Aug 2015
     * @param array $data
     */
    function create_mapping($data) {
        $this->db->insert('map_vr_pr', $data);
    }

    /**
     * Method to get all mapping records
     * 
     * @author  Mujaffar s  2 Sep 2015
     * @param array $data
     */
    function getall_mappings() {
        $this->db->select('map.*, c.firstname as partner_firstname, c.lastname as partner_lastname, c.email as partner_email, c.company as partner_company, c.access as partner_doc')
                ->from('map_vr_pr as map')
                ->join('customers as c', "c.id = map.partner_id", 'left')
                ->group_by('map.id');
        $result = $this->db->get('map_vr_pr');
        return $result->result();
    }

    /**
     * Method to get Partner uploaded documents details
     * 
     * @author  Mujaffar s  2 Sep 2015
     * @param array $data
     */
    function getpartner_doc($partner_id) {
        $this->db->select('doc.*')
                ->from('partner_documents as doc')
                ->where('partner_id', $partner_id);
        return $this->db->get()->result();
    }

    /**
     * Get partner associated vendors
     * 
     * @author  Mujaffar s  8 Sep 2015
     * @param type $partner_id
     */
    function get_partner_vendors($partner_id) {
        $this->db->select('*');
        $this->db->where('approved', 1)
                ->where('partner_id', $partner_id);
        $result = $this->db->get('map_vr_pr');
        return $result->result();
    }

    function get_partner_vendors_by_partners($partner_id) {
        $this->db->select('c.firstname, c.lastname, c.email, mvp.share_percentage, mvp.id');
        $this->db->from('map_vr_pr mvp');
        $this->db->join('customers c', 'mvp.vendor_id = c.id');
        $this->db->where('mvp.approved', '1')
                ->where('mvp.partner_id', $partner_id);
        $result = $this->db->get();
        return $result->result();
    }

    /**
     * Check valid partner_no present and create record
     * 
     * @param string $partner_no
     * @param int $vendor_id
     */
    function mapVendorPartner($vendor_id, $partner_no, $Partner_model) {
        // get partner id by Partner No
        $partnerRec = $Partner_model->checkUniqueId($partner_no);
        $data = array('vendor_id' => $vendor_id, 'partner_id' => $partnerRec->customer_id);
        $this->create_mapping($data);
    }

    function update_share($id, $share_percentage) {
        $this->db->trans_start();
        $this->db->set('share_percentage', $share_percentage);
        $this->db->where('id', $id);
        $this->db->update('map_vr_pr');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return "FAIL";
        } else {
            return "SUCCESS";
        }
    }

}
