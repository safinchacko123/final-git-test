<?php

function validate_address($form) {
    $form->set_rules('company', 'lang:address_company', 'trim|max_length[128]');
    $form->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
    $form->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
    $form->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]');
    $form->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[32]');
    $form->set_rules('address1', 'lang:address:address', 'trim|required|max_length[128]');
    $form->set_rules('address2', 'lang:address:address', 'trim|max_length[128]');
    $form->set_rules('city', 'lang:address:city', 'trim|required|max_length[32]');
    $form->set_rules('country_id', 'lang:address:country', 'trim|required|numeric');
    //$form->set_rules('zone_id', 'lang:address:state', 'trim|required|numeric');
    $form->set_rules('zip', 'lang:address:zip', 'trim|required|max_length[32]');
    return $form;
}
