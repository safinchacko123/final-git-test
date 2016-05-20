<?php 

Class Currency_model extends CI_Model
{
	function getCurrencies() {
        $result = $this->db->get('currencies');
        return $result->result();
    }
}