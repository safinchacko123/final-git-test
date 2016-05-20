<?php 

Class Country_model extends CI_Model
{
	function getCountries() {
        $result = $this->db->get('countries');
        return $result->result();
    }
}