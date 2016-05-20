<?php

/**
 * Method to check user role w.r.t. provided roleString
 * return true or false
 * 
 * @author Mujaffar 8 Aug    2015
 * @param type $roleType
 * @return boolean true/false
 */
function check_rechable_ven($customer, $Customer_model, $address_model) {
    $default_billing_address = 0;
    if ($customer['role_id'] == 0) {
        $default_billing_address = $customer['default_billing_address'];
        
        // Get address details
        $addresses = $Customer_model->get_address($default_billing_address);
        
        return $address_model->getNearByVentures($customer, $addresses['latitude'], $addresses['longitude']);
    }
    
}