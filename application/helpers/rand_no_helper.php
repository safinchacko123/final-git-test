<?php

/**
 * Method to generate random string for password reset
 * 
 * @author Mujaffar 25-7-2015
 * @param type $length
 * @return string
 */
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function genUniqueId($partnerModel, $length = 10)
{
    $uniqueId = generateRandomString($length);
    // Check for unique id present in database
    $recordResultset = $partnerModel->checkUniqueId($uniqueId);
    if($recordResultset){
        $this->genUniqueId($partnerModel, $length);
    }
    return $uniqueId;
}
