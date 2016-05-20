<?php

class Ventureaddress_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //zone areas
    function create_address($data) {
        $this->db->insert('venture_address', $data);
        return $this->db->insert_id();
    }

    /**
     * Method to update address by Venture_id
     * 
     * @param type $data
     * @return type
     */
    function update_address($venture_id = false, $data = false, $address_id = false) {
        //$this->db->where('id', $data['adr_id']);
        if ($address_id) {
            $this->db->where('id', $address_id);
        }
        if ($venture_id) {
            $this->db->where('venture_id', $venture_id);
        }
        $this->db->update('venture_address', $data);
        return $this->db->insert_id();
    }

    function delete_address($address_id) {
        $this->db->where(array('id' => $address_id))->delete('venture_address');
    }

    function get($add_id) {
        $result = $this->db->get_where('venture_address', array('id' => $add_id));
        return $result->row();
    }

    function getByVenture($ven_id) {
        $result = $this->db->get_where('venture_address', array('venture_id' => $ven_id));
        return $result->row();
    }
    
    /* By Lynn 16 may */
    function create_delivery_address($data) {
        $this->db->insert('venture_delivery_address', $data);
        return $this->db->insert_id();
    }
    
    function delete_delivery_address($venture_id) {
        $this->db->where(array('venture_id' => $venture_id))->delete('venture_delivery_address');
    }
    
    function getDeliveryAddressByVenture($venture_id) {
        $result = $this->db->get_where('venture_delivery_address', array('venture_id' => $venture_id));
        return $result->result();
    }
    
    function getGeocodeByLatlng($lat,$lng)
    {
		// url encode the address
		//$address = urlencode($address);
     
		// google map geocode api url
		//$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
    
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng";
		// get the json response
		$resp_json = file_get_contents($url);
     
		// decode the json
		$resp = json_decode($resp_json, true);
 
		// response status will be 'OK', if able to geocode given address 
		$location = array();
		if($resp['status']=='OK'){
			// echo '<pre>'; print_r($resp['results'][0]['address_components'][2]['long_name']); echo '</pre>';
			// echo '<pre>'; print_r($resp['results'][0]); echo '</pre>';
			

			foreach ($resp['results'][0]['address_components'] as $component) 
			{

				switch ($component['types']) {
					case in_array('street_number', $component['types']):
						$location['street_number'] = $component['long_name'];
					break;
					case in_array('route', $component['types']):
						$location['street'] = $component['long_name'];
					break;
					case in_array('sublocality', $component['types']):
						$location['sublocality'] = $component['long_name'];
					break;
					case in_array('locality', $component['types']):
						$location['locality'] = $component['long_name'];
					break;
					case in_array('administrative_area_level_2', $component['types']):
						$location['admin_2'] = $component['long_name'];
					break;
					case in_array('administrative_area_level_1', $component['types']):
						$location['admin_1'] = $component['long_name'];
					break;
					case in_array('postal_code', $component['types']):
						$location['postal_code'] = $component['long_name'];
					break;
					case in_array('country', $component['types']):
						$location['country'] = $component['long_name'];
						$location['country_code'] = $component['short_name'];
					break;
				}

			}
			
		}
		return $location;
		//echo '<pre>'; print_r($location); echo '</pre>'; 
	}

}
