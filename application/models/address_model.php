<?php

Class Address_model extends CI_Model {

    var $CI;

    function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

    /**
     * Function to get nearby venture details
     * 
     * @param string $latitude
     * @param string $longitude
     */
    function getNearByVentures($customer, $startlat, $startlng) {  
        $validAddr = array();
//        $query = $this->db->query("SELECT *, SQRT(POW(69.1 * (latitude - $startlat), 2) + POW(69.1 * ($startlng - longitude) * COS(latitude / 57.3), 2)) AS distance"
//                . " From gc_customers_address_bank as adr Left join gc_customers As cust on adr.customer_id = cust.id HAVING distance < 25 and cust.id != '".$customer['id']."' 
//                    and cust.access != '' and cust.access != 'vendor' ORDER BY distance" );
        
        $query = $this->db->query("SELECT *, SQRT(POW(69.1 * (latitude - $startlat), 2) + POW(69.1 * ($startlng - longitude) * COS(latitude / 57.3), 2)) AS distance"
                . " From gc_venture_address as adr Left join gc_customers As cust on adr.venture_id = cust.id HAVING distance < 25 and cust.id != '".$customer['id']."' 
                    and cust.access != '' and cust.access != 'vendor' ORDER BY distance" );
        
        $count = $query->row(); // returns an object of the first row
        
        // OR
        $results = $query->result_array(); // returns an asociative array of the result
        
        if ($count) {
            foreach ($results As $rowRes) {
                $didOccur = $this->occureInRange($rowRes, $startlat, $startlng);
                if($didOccur){
                    $validAddr[] = $rowRes;
                }else{
                    
                }
            }
        }
        return $validAddr;
    }

    /**
     * Function to check whether customer occure in delivery area of venture
     * 
     * @author Mujaffar s 16 Aug 2015
     * @param array $rowRes
     */
    function occureInRange($rowVenture, $startlat, $startlng) {
        $dist = $this->getDistance($rowVenture, $startlat, $startlng);
        if($dist <= $rowVenture['coverage_area']){
            return true;
        }else{
            return false;
        }
    }

    function getDistance($rowVenture, $latitude2, $longitude2) {
        
        $latitude1 = $rowVenture['latitude'];
        $longitude1 = $rowVenture['longitude'];
        //echo $latitude1." ".$longitude1." ".$latitude2." ".$longitude2;
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        //echo " || ".$d;
        return $d;
    }
    
}
