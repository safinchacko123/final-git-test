<?php

function setRuleMyAcc($form, $password, $confim)
{
    $form->set_rules('company', 'lang:address_company', 'trim|max_length[128]');
    $form->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
    $form->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
    $form->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]|callback_check_email');
    $form->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[32]');
    $form->set_rules('email_subscribe', 'lang:account_newsletter_subscribe', 'trim|numeric|max_length[1]');

    if ($password != '' || $confim != '') {
        $form->set_rules('password', 'Password', 'required|min_length[6]|sha1');
        $form->set_rules('confirm', 'Confirm Password', 'required|matches[password]');
    } else {
        $form->set_rules('password', 'Password');
        $form->set_rules('confirm', 'Confirm Password');
    }
    return $form;
}
