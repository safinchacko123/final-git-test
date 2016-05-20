<?php

/**
 * Method to check user role w.r.t. provided roleString
 * return true or false
 * 
 * @author Mujaffar 7 Aug 2015
 * @param type $roleType
 * @return boolean true/false
 */
function checkIfRoleValid($admin, $roleType) {
    
    if ($admin) {
        if ($admin['access'] == $roleType || $admin['access'] == 'Admin'){
            return true;
        }  else {
            return false;
        }
    }else{
        return false;
    }
}

function checkIfRoleValid2($customer, $admin, $roleType) {
    
    if ($customer) {
        if($roleType == 'Customer' && $customer['role_id'] == 0){
            // Check whether default address present
            if(!$customer['default_billing_address']){
                return 'add_not_present';
            }else{
                
            }
        } else {
            return false;
        }
    }
}