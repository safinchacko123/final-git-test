<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Index extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model'));
        $this->customer = $this->mp_cart->customer();
    }

    function index() {	
        $this->view('index/home');
    }

    /**
     * Home action to handle home page request
     * @author     Mujaffar S added on 11 July 2015
     */
    function home() {
        $this->view('index/home');
    }

    /**
     * Home action to handle home page request
     * @author     Mujaffar S added on 11 July 2015
     */
    function aboutus() {
        $this->view('index/aboutus');
    }

    /**
     * tc action to display terms and conditions
     * @author     Mujaffar S added on 11 July 2015
     */
    function tc() {
        ini_set('display_errors', '1');
        $this->view('index/tc');
        //echo 'here';exit;
    }

    /**
     * Policy action to display Privacy policy details
     * @author     Mujaffar S added on 11 July 2015
     */
    function policy() {
        $this->view('index/policy');
        //echo 'here';exit;
    }

    /**
     * Returnpolicy action to display Return policy details
     * @author     Mujaffar S added on 11 July 2015
     */
    function returnpolicy() {
        $this->view('index/returnpolicy');
    }

    /**
     * faq action to display Frequently asked questions
     * @author     Mujaffar S added on 11 July 2015
     */
    function faq() {
        $this->view('index/faq');
    }

    /**
     * faq action to display Frequently asked questions
     * @author     Mujaffar S added on 11 July 2015
     */
    function contactus() {
        $this->view('index/contactus');
    }

    function get_captcha() {
        $string = '';
        for ($i = 0; $i < 5; $i++) {
            $string .= chr(rand(97, 122));
        }
        $this->session->set_userdata('random_number', $string);
        $dir = FCPATH . '/assets/fonts/';

        $image = imagecreatetruecolor(165, 50);

        // random number 1 or 2
        $num = rand(1, 2);
        if ($num == 1) {
            $font = "Capture it 2.ttf"; // font style
        } else {
            $font = "Molot.otf"; // font style
        }

        // random number 1 or 2
        $num2 = rand(1, 2);
        if ($num2 == 1) {
            $color = imagecolorallocate($image, 113, 193, 217); // color
        } else {
            $color = imagecolorallocate($image, 163, 197, 82); // color
        }

        $white = imagecolorallocate($image, 255, 255, 255); // background color white
        imagefilledrectangle($image, 0, 0, 399, 99, $white);

        imagettftext($image, 30, 0, 10, 40, $color, $dir . $font, $this->session->userdata('random_number'));

        header("Content-type: image/png");
        imagepng($image);
    }

    function verify_contact_form() {

        if ($this->input->post('code') && strtolower($this->input->post('code')) == strtolower($this->session->userdata('random_number'))) {
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from($this->config->item('email'), $this->config->item('company_name'));
            $this->email->to($this->input->post('email'));
            $this->email->subject('Contact us form submiited!');

            $message = 'Dear Admin,<br />Contact form has been submitted, details are as below.<br />'
                    . '<table>'
                    . '<tr><td>Name</td><td>:</td><td>' . $this->input->post('name') . '</td></tr>'
                    . '<tr><td>Email</td><td>:</td><td>' . $this->input->post('email') . '</td></tr>'
                    . '<tr><td>Message</td><td>:</td><td>' . $this->input->post('message') . '</td></tr>'
                    . '</table>'
                    . '<br />Thanks.';

            $this->email->message(html_entity_decode($message));

            $this->email->send();

            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

}