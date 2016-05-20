<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Search extends Cs_Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        //$this->load->model(array('location_model','page_model','business_model'));
	
    }
    
    function index($pageNo=0)
    {
		$this->load->library('pagination');
		//echo $queryString = !empty($_SERVER["QUERY_STRING"])?$_SERVER["QUERY_STRING"]:'';
		//echo '<pre>'; print_r($_GET); echo '</pre>';
		if(!isset($_GET['page']))
		{
			$this->session->set_userdata('searchedCategoty', $_GET['category_list'][0]);
		} 
		$sessionData =  $this->session->userdata('locationDetail');
		
		$filterChecksHTML =  array();
		$latitude = $sessionData['seller_hid_lat'];
		$longitude = $sessionData['seller_hid_lng'];
		
		$ventureDetail =  $this->business_model->get_all_ventures($latitude,$longitude,$sessionData['category_id']);
		//echo "<pre>"; print_r($ventureDetail);  echo "</pre>";
		$ventureIds =  array();
		$ventureNames =  array();
		if(!empty($ventureDetail))
		{
			foreach($ventureDetail as $vanture)
			{
				$vanture_id = $vanture['user_id'];
				$ventureIds[] = $vanture_id;
				$ventureNames[$vanture_id] = $vanture['company'];
			}
		}
	
		
		$offSet = $this->uri->segment(2);
		$limit = 6;	
		$offSet = !empty($offSet)?$offSet:'0';
		
		$result =  $this->business_model->getSearchResult($_GET,$ventureIds,$ventureNames,$limit,$offSet,0);
		// die();
	
	
		
		$producResult = $result['producResult'];
		$filterChecksHTML = $result['filterChecksHTML'];
		//
		//echo $this->db->last_query();
		$data = array();
		$data['producResult'] = $producResult;
		
		if(isset($_GET['selected_type']) && $_GET['selected_type']=="product")
		{
			$data['venture_id'] = $producResult[0]['added_by_cust'];
		}			
		if(isset($_GET['selected_type']) && $_GET['selected_type'] =="venture")
		{
			$data['venture_id'] = $_GET['selected_id'];
		}	
		
		$data['paginationLink'] = '';
		if(!empty($producResult))
		{
		
			$config =  array();
			$config['base_url'] = site_url('search');
			//$config['use_page_numbers'] = TRUE;
			//$config['page_query_string'] = TRUE;
			$config['full_tag_open'] = '<div id="pagination">';
			$config['full_tag_close'] = '</div>';
			$config['uri_segment'] = 2;
			
			$config['total_rows'] =  $this->business_model->getSearchResult($_GET,$ventureIds,$ventureNames,$limit,$offSet,1);
			
			$config['per_page'] = $limit;
			$this->pagination->initialize($config);
			
			$data['paginationLink'] = $this->pagination->create_links();
		}
		
		//~ echo "<pre>d1"; print_r($_GET); echo "</pre>";
		//~ die();		
		 // echo $lastQury = $this->db->last_query();
		$data['index_vanture_list'] = $result['vanture_list'];
		$data['ventureIds'] = $ventureIds;
		$data['ventureNames'] = $ventureNames;
		$data['filterChecksHTML'] = !empty($filterChecksHTML)?implode("",$filterChecksHTML):'';
		$this->view('search/v_search',$data);
			
	}
	
	function callbackSearchPageFilterLink($label,$action,$value='0')
	{
		$html = '<a href="javascript:void(0)" onclick="clearSearchPageFilter('."'".$action."','".$value."'".')" >'.$label.' <img alt="x" src="'.site_url().'assets/front/images/remove.png"></a>';
		return $html;
	}	
	
}
