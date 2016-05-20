<?php

/**
 * Class for Venture module action reques handling
 *
 * 
 * @package    Default
 * @author     Mujaffar S added on 25 Aug 2015
 */
class Venture extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model'));
        $this->customer = $this->mp_cart->customer();
    }

    function index() {
        
        // Get product listing for provided venture
        $passData = array('order_by' => "name", 'sort_order' => "ASC",
            'rows' => 15, 'page' => 0, 'ventureId' => 29);
        
        $data['products'] = $this->Product_model->products($passData);
        
        $this->view('index/home');
    }

}