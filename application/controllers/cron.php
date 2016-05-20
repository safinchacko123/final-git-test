<?php

class Cron extends Front_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Settings_model');
        $this->load->model('Order_model');
        $this->lang->load('order');
        /*if(!$this->input->is_cli_request()) // Uncomment when going live. Remove limit = 1 in Line #32. Set cron using: wget <website>/index.php/cron/monthly_automated_email_invoice_to_ventures 
        {
           echo "This script can only be accessed via the command line" . PHP_EOL;
           die;
        }*/
    }    
    
    public function monthly_automated_email_invoice_to_ventures()
    {        
        $current_day   = date("d");
        $settingdata   = $this->Settings_model->get_settings('admin_settings');
        if($settingdata['monthly_invoice_date']==$current_day) {
            
            $invoicedate             = date("Y-m-$current_day");
            $data['previous_date']   = date("Y-m-$current_day", strtotime("-1 months"));
            $data['current_date']    = date('Y-m-d', strtotime('-1 day', strtotime($invoicedate)));
            
            $orders       = $this->Order_model->get_uninvoiced_orders($settingdata['monthly_invoice_date']);
            $ordersgroup  = $this->Order_model->get_uninvoiced_orders_group_by($settingdata['monthly_invoice_date']);
            $venture_email = "";
            for($i=0; $i<count($ordersgroup) && $i<1; $i++) {
                    do {
                        $order                  = current($orders);
                        $venture_id             = $order['ventureId'];
                        $data['orders'][]       = $order; 
                        $data['venturename']    = $order['venturefirstname'];
                        $venture_email          = $order['ventureemail'];
                    } while($order = next($orders) && $venture_id==current($orders)['ventureId']);
                        $configEmail = $this->config->item('email_config');
                        $this->load->library('email', $configEmail);
                        $this->email->set_newline("\r\n");
                        $this->email->from($configEmail['smtp_user'], 'Market Place');
                        $this->email->to($venture_email);
                        $this->email->subject("Monthly Invoice");                        
                        $body = $this->load->view('invoice_email_template.php',$data,TRUE);
                        $this->email->message($body);
                        $this->email->send();
                        $data['orders']= array();
            }
            echo 'Monthly Invoice email has been successfully send to the ventures.'/*.$venture_email*/;
        }
    }
    
}