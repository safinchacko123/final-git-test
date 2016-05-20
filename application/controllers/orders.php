<?php

/**
 * Class for Order detail module
 *
 * 
 * @package    Default
 * @author     Mujaffar added on 11 July 2015
 */
class Orders extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model'));
        $this->customer = $this->mp_cart->customer();
        $this->load->model('order_model');
    }

    function index() {
        $data['orders'] = $this->Order_model->get_order($_GET['order_id']);
        $data['order_id'] = $_GET['order_id'];
        $this->view('customer/ordersdetails', $data);
    }

    function cancelorder() {
        $save = array();
        $save['id'] = $_POST['order_id'];
        $save['status'] = 'Cancelled';
        $this->Order_model->save_order($save);
        redirect('customer/orders');
    }

}