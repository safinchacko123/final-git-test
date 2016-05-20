<?php

Class Store_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function create_store($data) {
        // Create new store and return store id
        $this->db->insert('venture_stores', $data);
        return $this->db->insert_id();
    }

    function get_stores($venture_id) {
        // Create new store and return store id
        return $this->db->select('s.name as store_name, adr.*')
                        ->from('venture_stores as s')
                        ->join('customers_address_bank as adr', 'adr.store_id = s.id', 'left')
                        ->where('s.venture_id', $venture_id)
                        ->get()
                        ->result();
    }

}