<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Partner extends Front_Controller
{

    var $customer;

    function __construct()
    {
        parent::__construct();
        
        $this->load->model(array('customer_model'));
        $this->customer = $this->mp_cart->customer();
    }

    function index()
    {
        $vendors = $this->customer_model->get_active_vendors($this->customer['id']);
        $passData['vendors'] = $vendors;
        $this->view('partner/becomepartner', $passData);
    }

}
