<?php

class Reports extends Admin_Controller {

	//this is used when editing or adding a customer
	var $customer_id	= false;	

	function __construct()
	{		
		parent::__construct();

		$this->auth->check_access('Admin', true);
		
		$this->load->model('Order_model');
		$this->load->model('Search_model');
		$this->load->model('Customer_model');
		$this->load->helper(array('formatting'));
		$this->load->helper('download_helper');
		$this->lang->load('report');
	}
	
	function index()
	{
		$data['page_title']	= lang('reports');
		$data['years']		= $this->Order_model->get_sales_years();
		$data['vendorList']     =  $this->Customer_model->get_vendors();  
                
		$this->view($this->config->item('admin_folder').'/reports', $data);
	}
	
	function best_sellers()
	{
		$start	= $this->input->post('start');
		$end	= $this->input->post('end');
		$data['best_sellers']	= $this->Order_model->get_best_sellers($start, $end);
		
		$this->load->view($this->config->item('admin_folder').'/reports/best_sellers', $data);	
	}
	
	function sales()
	{
		$year			= $this->input->post('year');
		$vendorId		= $this->input->post('vendor');
		$data['orders']	= $this->Order_model->get_gross_monthly_sales($year, $vendorId);
		//print_r($data['orders']); exit;
		$this->load->view($this->config->item('admin_folder').'/reports/sales', $data);	
	}

        function export_sales_xml() {
            $year		= $this->input->post('year');
	    $vendorId		= $this->input->post('vendorDropdown');
            $data['orders']	= $this->Order_model->get_gross_monthly_sales($year, $vendorId);
            $vendor     =  $this->Customer_model->get_vendor($vendorId); 
            if(!empty($vendor)) {
                force_download_content('salesreport-'.$vendor->company.'.xml', $this->load->view($this->config->item('admin_folder') . '/sales_report_xml', $data));
            } else {
                force_download_content('salesreport.xml', $this->load->view($this->config->item('admin_folder') . '/sales_report_xml', $data));
}
        }

}