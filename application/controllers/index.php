<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Index extends Cs_Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model','page_model','business_model','customer_model'));
        $this->load->database();
        $this->load->library('cart');
        $this->load->library('session');
        $this->load->library('customemail');
        $this->customer = $this->mp_cart->customer();
		$currentClass =  $this->router->fetch_class();
		$currentMethod =  $this->router->fetch_method();
		
		$sessionData =  $this->session->userdata('locationDetail');
		if(isset($sessionData['location_hid_country_id']))
		{
			$currencyName = $this->business_model->get_country_currency($sessionData['location_hid_country_id'],'currencySymbol');
			$this->config->set_item('myCurrency', $currencyName);
			$this->config->set_item('myCountryId', $currencyName);
			//echo 'test'.  $this->config->item('myCurrency');	
		}
		$userDetail = $this->session->userdata('userDetail');
		if(isset($userDetail) && !empty($userDetail))
			$this->config->set_item('isLogin', 'YES');	
		else
			$this->config->set_item('isLogin', 'NO');	
			
		//echo "<pre>"; print_r($_SESSION); echo "</pre>";	
			
    }

	public function index() {			
		$locSessionData =  $this->session->userdata('locationDetail');	
		if(isset($_REQUEST['type']) && $_REQUEST['type']=='location')
		{
			setcookie("location_city", "", time()-3600);
			redirect(site_url('/'));
		
		}
		if(!isset($_COOKIE['location_city']) || empty($_COOKIE['location_city']) || empty($locSessionData))
		{
			$this->cart->destroy();
			$country_id  = $_COOKIE['country_id'];
			$data['countryDetail'] = $this->location_model->get_country($country_id);
			$data['country_id'] = $country_id;
			if(config_item('allowCountryStatus')=='Y' && $_REQUEST['country'] !='else')
			{
				$this->partial('in_indexHeader');
				$this->partial('index/in_index',$data);
				$this->partial('in_indexFooter');		
			}
			else
			{
				// for  live
				 $this->partial('indexHeader');
				 $this->partial('index/index',$data);
				 $this->partial('indexFooter');	
				
				// for  local
				//~ $this->partial('in_indexHeader');
				//~ $this->partial('index/in_index',$data);
				//~ $this->partial('in_indexFooter');		
			}
		}
		else
		{
			redirect(site_url('category'));
		}
    }

    /**
     * Home action to handle home page request
     * @author    Lynn added on 09 March 2016
     */
    function home() {
	
        $this->view('index/home');
    }

    /**
     * Home action to handle Static pages
     * @author     Lynn added on 09 March 2016
     */
    function aboutus() {
        $this->view('index/aboutus');
    }

    function press() {
		$result = $this->page_model->get_page(9);
		$data['pageTitle'] =  'Press';
        $this->callbackStaticPage($result);
    }
    function privacy() {
		$result = $this->page_model->get_page(6);
		$data['pageTitle'] =  'Press';
        $this->callbackStaticPage($result);
    }
    
    function help() {
        	$result = $this->page_model->get_page(8);
		$data['pageTitle'] =  'Press';
        $this->callbackStaticPage($result);
    }
    
    function term() {
		$result = $this->page_model->get_page(4);
		$data['pageTitle'] =  'Press';
        $this->callbackStaticPage($result);
    }

	function how_it_work()
	{
        $result = $this->page_model->get_page(10);
		$data['pageTitle'] =  'How It Works';
        $this->callbackStaticPage($result);		
	}
	
	
    function tc() {
        ini_set('display_errors', '1');
        $this->view('index/tc');
        //echo 'here';exit;
    }

   
    function policy() {
        $this->view('index/policy');
        //echo 'here';exit;
    }
    

    function returnpolicy() {
        $this->view('index/returnpolicy');
    }

    /**
     * faq action to display Frequently asked questions
     * @author     Mujaffar S added on 11 July 2015
     */
    function faq() {
		$this->view('index/faq');
        //~ $result = $this->page_model->get_page(5);
		//~ $data['pageTitle'] =  'FAQ';
        //~ $this->callbackStaticPage($result);
    }

    /**
     * faq action to display Frequently asked questions
     * @author     Mujaffar S added on 11 July 2015
     */

    /**
     * faq action to display Frequently asked questions
     * @author     Mujaffar S added on 11 July 2015
     */
    function contactus() {
        	$result = $this->page_model->get_page(11);
		$data['pageTitle'] =  'Contact Us';
        $this->callbackStaticPage($result);
    }
	
	function callbackStaticPage($data)
	{
		$temp  = array();
		$temp['result'] =  $data;
		$this->view('index/staticPages',$temp);
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
            $this->email->subject('Contact us form submiited');

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

	public function location($country_id)
	{
		if(!isset($_COOKIE['location_city']))
		{		
			$this->session->unset_userdata('locationDetail');
			$data['countryDetail'] = $this->location_model->get_country($country_id);
			$data['country_id'] = $country_id;
			$data['locationDetail'] =  $this->session->userdata('locationDetail');
			$this->view('index/location',$data);
		}else
		{
			redirect(site_url('category'));
		}				
	}
	
	function category()
	{
		
		$post =  $this->input->post();
			//echo "<pre>"; print_r($post); echo "</pre>"; die();
		if(!empty($post))
		{		
			$hour = time() + 3600*60*60;
			setcookie('location_city', $post['location_city'], $hour);
			foreach($post as $key=>$value)
			{
				setcookie($key, $value, $hour);
			}
			//~ $sessionArr = array();
			//~ $sessionArr['area_id'] = $post['seller-areaName'];
			//~ $sessionArr['area_name'] = $post['seller_hid_locationName'];
			//~ $sessionArr['seller_hid_lat'] = $post['seller_hid_lat'];
			//~ $sessionArr['seller_hid_lng'] = $post['seller_hid_lng'];
			//~ 
			//~ $sessionArr['locationDetail'] = $post;		
			$this->session->set_userdata('locationDetail', $post); 
			redirect('category');			
		}
		else
		{
			$sessionData =  $this->session->userdata('locationDetail');	
			unset($sessionData['category_id']);
			$this->session->set_userdata('locationDetail', $sessionData);
			$sessionData =  $this->session->userdata('locationDetail');
			
			$this->checkIssetSession();				
			$this->view('index/category',$data);
		}
	}
	

	function area($category_id)
	{
		
		if(isset($category_id) && !empty($category_id))
		{
			
			$where1 = array();
			$where1['id'] = $category_id;
			$categoriesResult = $this->business_model->select_data_where('gc_categories',$where1);
			/* R e s e t - s e s s i o n */	
			$sessionData =  $this->session->userdata('locationDetail');
			$sessionData['category_name'] = $categoriesResult['name'];
			$sessionData['category_id'] = $category_id;			
			$this->session->set_userdata('locationDetail', $sessionData); 	
		}
		
		$post = $this->input->post();
		$sessionData =  $this->session->userdata('locationDetail');
		//echo "<pre>"; print_r($sessionData); echo "</pre>"; die();
		
		if(isset($post) && !empty($post))
		{
			//~ $temp =  array();
			//~ $temp  = $sessionData;
			//~ $temp['area_id'] = $post['seller-areaName'];
			//~ $temp['area_name'] = $post['seller_hid_locationName'];
			//~ $temp['seller_hid_lat'] = $post['seller_hid_lat'];
			//~ $temp['seller_hid_lng'] = $post['seller_hid_lng'];
			//~ $this->session->set_userdata('locationDetail', $temp);
			$area_id = $post['seller-areaName'];
			$latitude = $post['seller_hid_lat'];
			$longitude = $post['seller_hid_lng'];
			redirect(site_url('area'));			
		}
		else if(isset($sessionData['area_id']))
		{
			
			$this->checkIssetSession();					
			$area_id = $sessionData['area_id'];
			$latitude = $sessionData['seller_hid_lat'];
			$longitude = $sessionData['seller_hid_lng'];
		}
		else
		{
			redirect(site_url('/'));
		}
		
		
		// echo "<pre>"; print_r($sessionData['category_id']); echo "</pre>";
		// seller_hid_locality
		
		//~ $latitude = $sessionData['location_hid_lat'];
		//~ $longitude = $sessionData['location_hid_lng'];
		$country_id = $sessionData['country_id'];
		$category_id = $sessionData['category_id'];
		$distance = 1;
			
		
		$this->db->select("gc_customers.id as venture_id,gc_customers.id as user_id");
		$this->db->select('gc_venture_option.min_delivery_amount');
		$this->db->select('gc_venture_option.avg_delivery_time');
		$this->db->select('gc_venture_option.delivery_fee');
		$this->db->select('gc_venture_option.payment_type');
		$this->db->select('gc_venture_option.with_promotion');
       	$this->db->select('gc_customers.company');
       	$this->db->select('gc_customers.customer_logo');
       	
       	$this->db->from('gc_customers');
       	
		$this->db->join('gc_map_vr_vt', 'gc_map_vr_vt.venture_id = gc_customers.id');
		$this->db->join('gc_business_categories', 'gc_business_categories.customers_id = gc_map_vr_vt.vendor_id and  gc_business_categories.categories_id='.$category_id);
        
        $this->db->join('gc_venture_option', 'gc_venture_option.venture_id = gc_customers.id', 'left');	       	
       	
       	$this->db->where('gc_customers.access','Venture');
		$listing_delivery_area = $sessionData['seller_hid_locationName'];
       	$ventureDeliveryAddrQry = "SELECT venture_id as id FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and  gc_venture_delivery_address.sublocality='".$listing_delivery_area."' ";
		$this->db->where("gc_customers.id  IN (".$ventureDeliveryAddrQry.")", NULL, FALSE);	
       	
		$hid_limit = 3;
		$hid_ofset = 0;			
			
		$this->db->order_by("gc_customers.company",'asc'); 
		$query = $this->db->get();
        $finalResult =  $query->result_array();		
		//echo $this->db->last_query();
		//echo "<pre>"; print_r($finalResult); echo "</pre>";
		$data = array();
		$data['detail'] =  $this->session->userdata('locationDetail');
		$data['finalResult'] = $finalResult;
		
		
		$data['vanture_id'] = $finalResult['id'];
		
		$data['areaSelectResult'] =  $this->business_model->getArea_byLocation($latitude,$longitude,$sessionData['category_id'],$sessionData['seller_hid_locality']);
		
		$this->view('index/area',$data);
	}
	
	function detail($venture_id)
	{
		
		$sessionData =  $this->session->userdata('locationDetail');
		$this->checkIssetSession();			
		if(empty($venture_id) && isset($sessionData['venture_id']))
		{
			$venture_id = $sessionData['venture_id'];
		}
		
		$data = array();
		$this->db->select('gc_customers.customer_logo');
		$this->db->select("gc_customers.company");
		$this->db->select("gc_venture_option.venture_id");
		$this->db->select("gc_venture_option.min_delivery_amount");
		$this->db->select("gc_venture_option.avg_delivery_time");
		$this->db->select("gc_venture_option.delivery_fee");
		$this->db->select("gc_venture_option.payment_type");
		
		$this->db->from('gc_customers');
		$this->db->join('gc_venture_address', 'gc_venture_address.venture_id = gc_customers.id');	
		$this->db->join('gc_venture_option', 'gc_venture_option.venture_id = gc_customers.id');	
		$this->db->where('gc_customers.id ', $venture_id);
		$query = $this->db->get();
        $ventureResult =  $query->row_array();			
		
		 
		$this->db->select("*");
		$this->db->from('gc_products');
		$this->db->where('gc_products.added_by_cust ', $venture_id);
		$this->db->join('gc_products_addons', 'gc_products_addons.product_id = gc_products.id', 'left');	
		//$this->db->where("gc_products.id  IN (select distinct(product_id) as id from gc_order_items where venture_id=".$venture_id." )");		
		$query1 = $this->db->get();
        $productResult =  $query1->result_array();			
			
		/* R e s e t - s e s s i o n */	
		$sessionData['venture_id'] = $venture_id;
		$sessionData['venture_name'] = $ventureResult['company'];
		$this->session->set_userdata('locationDetail', $sessionData);		
		
		
		$data['ventureResult'] = $ventureResult;
		$data['productResult'] = $productResult;
		//echo '<pre>'; print_r($data); echo '</pre>';
		$this->view('index/detail',$data);
	}
	
	/* list page functionality */
	function restaurantList_searchBy()
	{
		$sessionData =  $this->session->userdata('locationDetail');
		$post  = $this->input->post();
		$filterChecksHTML = array();
		
		
		
		$latitude = $sessionData['seller_hid_lat'];
		$longitude = $sessionData['seller_hid_lng'];		
		
		$country_id = $sessionData['country_id'];
		$category_id = $sessionData['category_id'];
		$distance = 1;
		
		//~ $this->db->select("p.venture_id,gc_customers.id as user_id,p.address");
		//~ $this->db->select('gc_venture_option.min_delivery_amount');
		//~ $this->db->select('gc_venture_option.avg_delivery_time');
		//~ $this->db->select('gc_venture_option.delivery_fee');
		//~ $this->db->select('gc_venture_option.payment_type');
		//~ $this->db->select('gc_venture_option.with_promotion');
       	//~ $this->db->select('gc_customers.company');
       	//~ $this->db->select('gc_customers.customer_logo');
       			//~ 
        //~ $this->db->from('gc_venture_address p');
        //~ 
		//~ $this->db->join('gc_map_vr_vt', 'gc_map_vr_vt.venture_id = p.venture_id');
		//~ $this->db->join('gc_business_categories', 'gc_business_categories.customers_id = gc_map_vr_vt.vendor_id and  gc_business_categories.categories_id='.$category_id);        
        //~ $this->db->join('gc_customers', 'gc_customers.id = p.venture_id', 'left');
        //~ $this->db->join('gc_venture_option', 'gc_venture_option.venture_id = p.venture_id', 'left');
        //~ $this->db->where("(p.latitude = '".$latitude."' and p.longitude = '".$longitude."')");
        
		$this->db->select("gc_customers.id as venture_id,gc_customers.id as user_id");
		$this->db->select('gc_venture_option.min_delivery_amount');
		$this->db->select('gc_venture_option.avg_delivery_time');
		$this->db->select('gc_venture_option.delivery_fee');
		$this->db->select('gc_venture_option.payment_type');
		$this->db->select('gc_venture_option.with_promotion');
       	$this->db->select('gc_customers.company');
       	$this->db->select('gc_customers.customer_logo');
       	
       	$this->db->from('gc_customers');
       	
		$this->db->join('gc_map_vr_vt', 'gc_map_vr_vt.venture_id = gc_customers.id');
		$this->db->join('gc_business_categories', 'gc_business_categories.customers_id = gc_map_vr_vt.vendor_id and  gc_business_categories.categories_id='.$category_id);
        
        $this->db->join('gc_venture_option', 'gc_venture_option.venture_id = gc_customers.id', 'left');	       	
        $this->db->join('gc_venture_address', 'gc_venture_address.venture_id = gc_customers.id', 'left');	       	
        
       	
       	$this->db->where('gc_customers.access','Venture');
       	if($post['listing_delivery_area'] != '')
		{
			$listing_delivery_area = $post['listing_delivery_area'];
			
			$filterChecksHTML[] = $this->callbackFilterLink('Delivery Area :'.$post['listing_delivery_area'],'listing_delivery_area');
		}
		else
		{
			$listing_delivery_area = $sessionData['seller_hid_locationName'];
		}
       	
       	$ventureDeliveryAddrQry = "SELECT venture_id as id FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and  gc_venture_delivery_address.sublocality='".$listing_delivery_area."' ";
		$this->db->where("gc_customers.id  IN (".$ventureDeliveryAddrQry.")", NULL, FALSE);	        
	     
        
		if(isset($post['listing_ul_restaurantType']) && in_array('now_open',$post['listing_ul_restaurantType'])) 
		{        
			$this->db->join('gc_venture_timing', 'gc_venture_timing.venture_id = gc_customers.id and gc_venture_timing.weekday=dayofweek(curdate()) ');
		}
       
		if($post['restaurant_restaurantName'] != '')
		{
			$this->db->like('company', $post['restaurant_restaurantName']);
			$filterChecksHTML[] = $this->callbackFilterLink($post['restaurant_restaurantName'],'restaurant-restaurantName');
		}
		       
		if($post['minimumDeliveryAmount'] != '' && $post['minimumDeliveryAmount'] != '$0')
		{
			
			$listing_minimumDeliveryAmount = substr($post['minimumDeliveryAmount'],1);
			$this->db->where('min_delivery_amount <=', $listing_minimumDeliveryAmount);
			$filterChecksHTML[] = $this->callbackFilterLink('Delivery amount :'.$post['minimumDeliveryAmount'],'amount');
		}
		if($post['hid_avg_deliveryTime'] != '')
		{
			$hid_avg_deliveryTime = $post['hid_avg_deliveryTime'];
			$this->db->where('avg_delivery_time <=', $hid_avg_deliveryTime);
			$filterChecksHTML[] = $this->callbackFilterLink('Avg delivery time :'.$post['hid_avg_deliveryTime'],'hid_avg_deliveryTime');
			
		}		
	
		if($post['listing_payment_method'] != '')
		{
			$this->db->where('payment_type ', $post['listing_payment_method']);
			$filterChecksHTML[] = $this->callbackFilterLink('Payment method :'.$post['listing_payment_method'],'listing_payment_method');
		}
			
		if($post['delivery_fee'] != '')
		{
			if($post['delivery_fee'] == 'free' )
				$this->db->where('delivery_fee ', 0);
			else
				$this->db->where('delivery_fee > ', 0);
				
			$filterChecksHTML[] = $this->callbackFilterLink('Delivery type :'.$post['delivery_fee'],'delivery_fee');
		}	
		
		
		if(isset($post['selected_rating']) && !empty($post['selected_rating']))
		{
			$this->db->where('rating ', $post['selected_rating']);
			$filterChecksHTML[] = $this->callbackFilterLink('Rating :'.$post['selected_rating'],'selected_rating');
		}
		
		if(isset($post['listing_ul_restaurantType']) && in_array('now_open',$post['listing_ul_restaurantType'])) 
		{		
			$filterChecksHTML[] = $this->callbackFilterLink('Now open','now_open');
		}	
		if(isset($post['listing_ul_restaurantType']) && in_array('with_promotion',$post['listing_ul_restaurantType']))
		{			
			$this->db->where('with_promotion', '1');
			$filterChecksHTML[] = $this->callbackFilterLink('With promotion','with_promotion');
		}	
		
		if(isset($post['cuisine_fav']) && !empty($post['cuisine_fav']))
		{
			$coisineCount = 0;
			$ids = implode(",",$post['cuisine_fav']);
			$cosineQry = "SELECT DISTINCT(p.added_by_cust) as venture_id FROM gc_product_cuisine_map pc, gc_products p WHERE p.id = pc.product_id AND pc.cuisine_id in(".$ids.")"; 
			$this->db->where("gc_customers.id  IN (".$cosineQry.")", NULL, FALSE);
		}		
				 
		if($post['hid_sort_type'] == 'alphabet')
		{
			$hid_sort_order = $post['hid_sort_order'];
			$this->db->order_by("company", $hid_sort_order); 
		}     
		if($post['load_more']==1)
		{
			$hid_limit = $post['hid_limit'];
			$hid_ofset = $post['hid_ofset'];
		}
		else
		{
			$hid_limit = 3;
			$hid_ofset = 0;			
		}
		//	$this->db->limit($hid_limit,$hid_ofset);
		$query = $this->db->get();
        $finalResult =  $query->result_array();
       $lastQury = $this->db->last_query();
        //echo "<pre>"; print_r($finalResult); echo "</pre>";
		if(!empty($finalResult))
		{		
			$data = array();
			$data['detail'] =  $this->session->userdata('locationDetail');
			$data['finalResult'] = $finalResult;
			$html = $this->load->view('front/index/restaurant_list',$data,true); 
			$json = array();
			$json['hasRecordStatus'] = 'Y';
			$json['qry'] =  $lastQury;
			$json['html'] = $html;
			$json['limit'] = $hid_limit;
			$json['ofset'] = $hid_limit+$hid_ofset+2;
			$json['filterChecksHTML'] = implode('',$filterChecksHTML);
		}
		else
		{
			$json = array();
			$json['qry'] = $this->db->last_query(); 
			$json['hasRecordStatus'] = 'N';
			$json['filterChecksHTML'] = implode('',$filterChecksHTML);
		}	
		echo json_encode($json);	
	}
	
	function callbackFilterLink($label,$value)
	{
		$html = '<a href="javascript:void(0)" onclick="clearThisFilter('."'".$value."'".')" >'.$label.' <img alt="x" src="'.site_url().'assets/front/images/remove.png"></a>';
		return $html;
	}
	
	function ajax_checkValideEmail()
	{
		$post = $this->input->post();	
		$where1 = array();
		$where1['email'] = $post['email'];
		$areaResult = $this->business_model->select_data_where('gc_customers',$where1);
		if(empty($areaResult))
		{
			$json['emailValidStatus'] = 'Y';
		}
		else
		{
			$json['emailValidStatus'] = 'N';
		}
		echo json_encode($json);
	}	

	function checkout()
	{
		
		$this->checkIssetSession();	
		$userDetail = $this->session->userdata('userDetail');
		if(empty($userDetail))
		{
			$this->session->set_userdata('returnController','checkout'); 
			redirect('login/checkout');
		}		
		$data = array();
		$where =  array(); 
		$where['user_id'] = $userDetail['user_id'];
		$data['notesData'] = $this->business_model->select_data_where_result('gc_order_notes',$where);
		$this->view('index/checkout',$data);
	}
	
	function order()
	{
		$post = $this->input->post();	
		//echo "<pre>"; print_r($post); echo "</pre>"; die();
		
		
		$userDetail = $this->session->userdata('userDetail');
		if(empty($userDetail))
		{
			$this->session->set_userdata('returnController','order'); 
			redirect('login/order');
		}
		else
		{	

			$sessionData =  $this->session->userdata('locationDetail');
			$user_id =  $this->session->userdata('userDetail')['user_id'];
			$where1 = array();
			$where1['id'] = $user_id;
			$customer = $this->business_model->select_data_where('gc_customers',$where1);
			
			$cartData =  $this->cart->contents(); 
						
			if(isset($cartData) && !empty($cartData)) {
				$cartData = $this->business_model->cart_order_by_vanture($cartData,'venture_id');
				if(isset($cartData[0])) { unset($cartData[0]); }
				
				$j=0;
				$delivery_fee_array =  array();
				foreach($cartData as $venture_id=>$cart)
				{
					$shipAddress = 'shipAddress_'.$j;
					$where4 = array();
					$where4['id'] = $post[$shipAddress];
					$shippingResult = $this->business_model->select_data_where('gc_shipping_address',$where4);
					
					

					$billingAddress = 'billingAddress_'.$j;
					$where4 = array();
					$where4['id'] = $post[$billingAddress];
					$billingResult = $this->business_model->select_data_where('gc_billing_address',$where4);
					
					$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
					$insertData = array();	
					$insertData['customer_id'] = $user_id;
					$insertData['status'] = 'order_placed';
					$insertData['venture_id'] = $venture_id;
					$insertData['ordered_on'] = date('Y-m-d H:i:s');
					$insertData['company'] = $customer['company'];
					$insertData['firstname'] = $customer['firstname'];
					$insertData['lastname'] = $customer['lastname'];
					$insertData['phone'] = $customer['phone'];
					$insertData['email'] = $customer['email'];	
					$insertData['company']	= $venture_name;
					$insertData['ship_address1']	= $shippingResult['address_l1'];
					$insertData['ship_address2']	= $shippingResult['address_l2'];
					$insertData['ship_city']	= $shippingResult['city'];
					$insertData['ship_zip']	= $shippingResult['zipcode'];
					$insertData['shipping_method']	= $post['payment_methood'];
					
					
					$insertData['bill_firstname']	= $billingResult['firstname'];
					$insertData['bill_lastname']	= $billingResult['lastname'];
					$insertData['bill_address1']	= $billingResult['address_l1'];
					$insertData['bill_address2']	= $billingResult['address_l2'];
					$insertData['bill_city']	= $billingResult['city'];
					$insertData['bill_zip']	= $billingResult['zipcode'];					
					$insertData['bill_country']	= $venture_name;					
					$insertData['bill_company']	= $billingResult['zipcode'];					
					
					
					$oder_id = $this->business_model->insert_entry('gc_orders',$insertData);
					if(!empty($cart))
					{
						$ventureSubTot = array();
						$delivery_fee_res = $this->business_model->select_coulmn_single_value('delivery_fee','gc_venture_option','venture_id',$venture_id);
						$delivery_fee_res =  !empty($delivery_fee_res)?$delivery_fee_res:'0';						
						
						foreach($cart as $singleItem) 
						{	
							//echo '<pre>'; print_r($singleItem['qty']); echo '</pre>';
							$ventureSubTot[] = $singleItem['subtotal'];
							$product_id = $singleItem['options']['product_id'];  
							$where1 = array();
							$where1['id'] = $product_id;
							$productDet = $this->business_model->select_data_where('gc_products',$where1);
							$productSerialize  = serialize($productDet); 
							$insertItem = array();
							$insertItem['order_id'] = $oder_id;
							$insertItem['product_id'] = $product_id;
							$insertItem['venture_id'] = $venture_id;
							$insertItem['quantity'] = $singleItem['qty'];
							$insertItem['contents'] = $productSerialize;
							$this->business_model->insert_entry('gc_order_items',$insertItem);
						}
						$updData= array();
						$updData['total'] = array_sum($ventureSubTot)+$delivery_fee_res;
						$updData['subtotal'] = array_sum($ventureSubTot);
						$updData['shipping'] = $delivery_fee_res;
						$order_number = date('U') . $oder_id;
						$updData['order_number'] = $order_number;
						
						//print_r($updData);
						$this->business_model->update_entry('gc_orders','id',$oder_id,$updData);
						
					}	
					$j++;		
				}
				
				/* store default shiiong address start */
				$user_id = $this->session->userdata('userDetail')['user_id']; 
				$updateData = array();
				$updateData['address_l1'] = "#2563 Sector-35 C";
				$updateData['address_l2'] = "First floor";
				$updateData['city'] = "Chandigarh";
				$updateData['state'] = "Punjab";
				$updateData['zipcode'] = "160035";
				$updateData['country'] = "India";					
				$where1 = array();
				$where1['user_id'] = $user_id;
				$shipResult = $this->business_model->select_data_where('gc_shipping_address',$where1);		
				
				$json = array();
				if(empty($shipResult))
				{
					$updateData['user_id'] = $user_id;	
					$res = $this->business_model->insert_entry('gc_shipping_address',$updateData);		
				}
				/* store default shiiong address end */
				/* store default card detail start */
				$cardInsert = array();
				$cardInsert['firstname'] = "Amit";
				$cardInsert['lastname'] = "Kumar";
				$cardInsert['cardLastDigit'] = "6232";
				$cardInsert['day'] = "07";
				$cardInsert['year'] = "2022";
				$cardInsert['cardType'] = "Visa";	
				$cardInsert['user_id'] = $user_id;					
				$where1 = array();
				$where1['user_id'] = $user_id;
				$cardResult = $this->business_model->select_data_where('gc_cardDetail',$where1);	
				$json = array();
				if(empty($cardResult))
				{
					$res = $this->business_model->insert_entry('gc_cardDetail',$cardInsert);		
				}
				/* store default card detail end */
				$this->cart->destroy();				
			}
		
			$msg['message'] = '<strong>Order Number : '.$order_number.'</strong>,<br>
								Your order has been placed successfuly.<br>
			<a href="'.site_url().'">Click to more shopping</a>';
			$this->view('index/notifyMessage',$msg);
		}
	}
	
	function checkIssetSession()
	{
		$sessionData =  $this->session->userdata('locationDetail');
		$currentClass =  $this->router->fetch_class(); 		
		$currentMethod =  $this->router->fetch_method(); 
		if(empty($_COOKIE['location_hid_lat']))
		{
			setcookie("location_city", "", time()-3600);
			redirect('/');			
		}
		//if($currentMethod=='seller' && )
	}	
	
	function checkIsLogin()
	{
		$userDetail = $this->session->userdata('userDetail');
		
		if(!empty($userDetail))
		{
			$returnController = $this->session->userdata('returnController'); 
			$denyRedirect = array('register','login','forgot');
			if(!empty($returnController) && !in_array($returnController,$denyRedirect))
			{
				redirect(site_url($returnController));
			}
			else
			{
				redirect(site_url('/'));
			}
		}
	}	
	
	function login()
	{
		$lastAction = $this->uri->segment(2);
		if(!empty($lastAction))
		{
			if(!empty($_SERVER["QUERY_STRING"]))
			{
				$lastAction = $lastAction.'/?'.$_SERVER["QUERY_STRING"]; 
			}
			
			$this->session->set_userdata('returnController',$lastAction); 
		}
		
		$this->checkIsLogin();
		$post =  $this->input->post();
		if(!empty($post))
		{
			$where1 = array();
			$where1['email'] = $post['loginEmail'];
			$where1['password'] = sha1($post['loginPassword']);	
			$areaResult = $this->business_model->select_data_where('gc_customers',$where1);
			if(!empty($areaResult))
			{
				/******************* Remeber me *************** */				
				if($areaResult['email_verified'] =='1')
				{
					$this->load->helper('cookie');
					if(isset($post['remember_me']))
					{	
						$hour = time() + 3600;
						setcookie('email', $post['loginEmail'], $hour);
						setcookie('password', $post['loginPassword'], $hour);		
					}		
					else
					{
						setcookie("email", "", time()-3600);
						setcookie("password", "", time()-3600);	
					}			
					$sessData['user_id'] = $areaResult['id'];
					$this->session->set_userdata('userDetail', $sessData); 
					$this-> checkIsLogin();
				}
				else
				{
					$data['errorMessage'] = '<div class="error-msg" id="errorMsg">Your email address is not verify.</div>';
					$this->session->set_flashdata('msg', $error );				
					$this->view('index/login',$data);						
				}
			}
			else
			{
				$data['errorMessage'] = '<div class="error-msg" id="errorMsg">Incorrect username or password</div>';
				$this->session->set_flashdata('msg', $error );				
				 $this->view('index/login',$data);	
			}		
		}
		else
		{		
			$this->view('index/login');
		}
	}
	
	function register()
	{
		
		$this-> checkIsLogin();		
		$post =  $this->input->post();
		if(!empty($post))
		{
			$verify_email_key = time().rand();
			$data = array();
			$data['firstname'] = $post['name'];
			$data['lastname'] = $post['surname'];
			$data['email'] = $post['email'];
			$data['password'] = sha1($post['password']);
			$data['mobile_number'] = $post['mobile_number'];
			$data['city'] = $post['cityName'];		
			$data['verify_email_key'] = $verify_email_key;
			$where1 = array();
			$where1['email'] = $post['email'];
			$areaResult = $this->business_model->select_data_where('gc_customers',$where1);
			if(empty($areaResult))
			{						
				$id= $this->business_model->insert_entry('gc_customers',$data);
			}
	
			$msg = 'Hello '.$post['name'].' <br> <br>					
					Thanks for registeting in Market Place. Your Participation is appreciated. 
					To verify your email address <a href="'.site_url('index/verifyAccount/'.$verify_email_key).'">Click here</a>. After successful verification of your email address you can access your account.
					 <br><br>Thanks , <br>Market Place';
			$subject ="Thanks for Participation";			
			$this->customemail->reg_confirmation($post['email'],$subject,$msg); 						
			$data = array();
			$data['emailData'] = $msg;
			$data['message'] = 'Thanks for the registeration,<br>Please check your Inbox or Spam for verification.';
			$this->view('index/notifyMessage',$data);
		}
		else
		{
			$this->view('index/register');
		}
	}
	
	function verifyAccount($verify_email_key='')
	{
		$data = array();		
		$data['email_verified'] = '1';
		$data['verify_email_key'] = '';
		$this->business_model->update_entry('gc_customers','verify_email_key',$verify_email_key,$data);
		$msg['message'] = 'Your account has been verified sucessfully<br>			
			<a href="'.site_url('login').'">Login here to access your account</a>';
		$this->view('index/notifyMessage',$msg);				
	}
	
	function logout()
	{
		//~ $lastAction = $this->uri->segment(2);
		//~ if(!empty($lastAction))
		//~ {
			//~ $this->session->set_userdata('returnController',$lastAction); 
		//~ }
		//echo "ff".$returnController = $this->session->userdata('returnController'); die();
		$this->load->library('googleplus');
		$this->session->unset_userdata('userDetail');
        	//$this->session->sess_destroy();
       		//$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Logout Successfully!</div>');
        	/* Google logout*/
	        unset($_SESSION['token']);
	       // unset($_SESSION['state']);
	        $this->session->unset_userdata('state');
		$this->googleplus->revokeToken();		
        	redirect(site_url());
	}
	function forgot()
	{
		$this-> checkIsLogin();		
		$post =  $this->input->post();		
		if(!empty($post))
		{		
			$where1 = array();
			$where1['email'] = $post['email'];
			$verify_forgot_key = md5($post['email'].time());
			$userResult = $this->business_model->select_data_where('gc_customers',$where1);							
			$updData = array();			
			$updData['verify_forgot_key'] = $verify_forgot_key;
			$this->business_model->update_entry('gc_customers','email',$post['email'],$updData);								
			$data = array();
			 $emailData = "Dear ".$userResult['firstname'].' <br><br>
									<a href="'.site_url('resetPassword/'.$verify_forgot_key).'">Click here to reset your password</a>	<br><br>
									Thanks<br>Market Place
								'; 
			$subject ="New password request";		
			$this->customemail->reg_confirmation($post['email'],$subject,$emailData); 
			$data['emailData'] = $emailData;
			$data['message'] = 'Your forgot password request accepted. The new password link has been sent to you on your email address<br>
								Please check your Inbox or Spam for reset your password';
			
			$this->view('index/notifyMessage',$data);		
		}
		else
		{
			$this->view('index/forgot');	
		}
	}	
	
	function resetPassword()
	{
		$post =  $this->input->post();
		if(!empty($post))
		{
			$updData = array();			
			$updData['verify_forgot_key']  = '';
			$updData['password'] = sha1($post['password']);
			$this->business_model->update_entry('gc_customers','verify_forgot_key',$post['verify_forgot_key'],$updData);					
			$msg['message'] = '<strong>Your password had been updated sucessfully</strong>,<br>			
			<a href="'.site_url('login').'">Click to login</a>';
			$this->view('index/notifyMessage',$msg);			
		}
		else
		{
			$verify_forgot_key =  $this->uri->segment(2);
			$where1 = array();
			$where1['verify_forgot_key'] = $verify_forgot_key;	
			$userResult = $this->business_model->select_data_where('gc_customers',$where1);	
			if(!empty($userResult))
			{
				$data = array();
				$data['verify_forgot_key']  = $verify_forgot_key;
				$data['userResult']  = $userResult;
				$this->view('index/resetPass',$userResult);	
			}
			else
			{
				$msg['message'] = 'Upps ! Your link has been expired';
				$this->view('index/notifyMessage',$msg);
			}
		}
	}
	
	function myAccount()
	{
		$this->view('index/myAccount');
	}
		
	function facebook_login()
	{
		$this-> checkIsLogin();		
		$app_id = "472478382959784";
		$app_secret = "7d849700babccdfc4efcb7e7260519de";
		$my_url = site_url()."index/facebook_login"; // mainly, redirect to this page	
		$perms_str = "email";
		if(isset($_REQUEST["code"]))
		{
			$code = $_REQUEST["code"];
		}
		else
		{
			$code = '';
		}		 
		if(empty($code)) 
		{
		    $auth_url = "http://www.facebook.com/dialog/oauth?client_id="
		    . $app_id . "&redirect_uri=" . urlencode($my_url)
		    . "&scope=" . $perms_str;
		    echo("<script>top.location.href='" . $auth_url . "'</script>");
		}
		 
		$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
		. $app_id . "&redirect_uri=" . $my_url
		. "&client_secret=" . $app_secret
		. "&code=" . $code;
		$params = array();     
		$retResponseJson=$this->check_response($token_url);	 
		$retResponse = json_decode($retResponseJson);		
		if($retResponse->error->code=='100')
		{	
			 echo("<script>top.location.href='" . site_url().'index/facebook_login' . "'</script>");
		}
		else
		{		
			$pieces = explode("&",$retResponseJson); 
			$access_token=substr($pieces[0],13);
			$infourl = "https://graph.facebook.com/me?access_token=$access_token";
			$retResponseJsonRep =$this->check_response($infourl);
			$result = json_decode($retResponseJsonRep);
			$where1 = array();
			$where1['facebook_id'] = $result->id;
			$userResult = $this->business_model->select_data_where('gc_customers',$where1);		
			if(empty($userResult))
			{
				$data = array();
				$data['firstname'] = $result->name;
				$data['facebook_id'] = $result->id;
				$id= $this->business_model->insert_entry('gc_customers',$data);
			}
			else
			{
				$id = $userResult['id'];
			}
			$sessData =  array();
			$sessData['user_id'] = $id;
			$this->session->set_userdata('userDetail', $sessData); 
			$returnController = $this->session->userdata('returnController'); 
			if(!empty($returnController))
			{
				redirect($returnController);
			}
			else
			{
				redirect('/');
			}
		}
	}
	
	function check_response($url='')
	{    
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		$response = curl_exec($ch);
		$decoded = json_decode($response, true);
		if (curl_errno($ch))
		{
			print curl_error($ch);
		}
		else
		{
			curl_close($ch);
		//	print_r($decoded);
		//	print_r($response);
			return $response;
		}
	}	
	
	
	function email_sending_smtp($to,$subject,$content)
	{
	   $CI = & get_instance();
		$configEmail = $CI->config->item('email_config');
		$this->load->library('email', $configEmail);
		$this->email->set_newline("\r\n");


		$this->email->from($configEmail['smtp_user'], 'Market Place');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message(html_entity_decode($content));
		if ($this->email->send()) {
		
		} else {
			echo $this->email->print_debugger();
		}
	}	

	function social_login($provider_name)
	{
		$this->load->library('oauth2/OAuth2');
		$this->load->config('oauth2', TRUE);
		//$provider_name = 'google';
        $provider = $this->oauth2->provider($provider_name, array(
            'id' => $this->config->item($provider_name.'_id', 'oauth2'),
            'secret' => $this->config->item($provider_name.'_secret', 'oauth2'),
        ));		
		//echo "TEST";
        if ( ! $this->input->get('code'))
        {
			//echo "fff";
            // By sending no options it'll come back here
            $provider->authorize();
        }
        else
        {
			try
            {
                //$token = $provider->access($_GET['code']);
                $token = $provider->access($this->input->get('code'));
				//echo "<pre>";  echo $token; echo "</pre>";
                $user = $provider->get_user_info($token);
				//	echo "<pre>";  print_r($user); echo "</pre>";
				// die();
				$where1 = array();
				$where1['googleplus_id'] = $user['uid'];
				$userResult = $this->business_model->select_data_where('gc_customers',$where1);		
				if(empty($userResult))
				{
					$data = array();
					$data['firstname'] = isset($user['first_name'])?$user['first_name']:'';
					$data['lastname'] = isset($user['last_name'])?$user['last_name']:'';
					$data['email'] = isset($user['email'])?$user['email']:'';
					$data['googleplus_id'] = $user['uid'];
					$id= $this->business_model->insert_entry('gc_customers',$data);
				}
				else
				{
					$id = $userResult['id'];
				}						
				$sessData =  array();
				$sessData['user_id'] = $id;
				$this->session->set_userdata('userDetail', $sessData); 
				$returnController = $this->session->userdata('returnController'); 
				if(!empty($returnController))
				{
					redirect($returnController);
				}
				else
				{
					redirect('/');
				}						
               
            }

            catch (OAuth2_Exception $e)
            {
                show_error('That didnt work: '.$e);
            }
		}		
	}
	
	
	function paypal_confirm()
	{
		if(isset($_POST) && !empty($_POST))
		{
			echo '<pre>'; print_r($_POST); echo '</pre>';
		}
		else
		{
				echo "No return";
		}
	}
	function paypal_cancel()
	{
			echo "come back";
	}

	function save_checkoutAddress()
	{
		$post = $this->input->post();
		//echo '<pre>'; print_r($post); echo '</pre>';
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		
		if(isset($post['billing_address_l1']) && !empty($post['billing_address_l1']))
		{
			$updateData = array();
			$updateData['user_id'] = $user_id;
			$updateData['firstname'] = $post['billing_firstname'];
			$updateData['lastname'] = $post['billing_lastname'];
			$updateData['address_l1'] = $post['billing_address_l1'];
			$updateData['address_l1'] = $post['billing_address_l1'];
			$updateData['address_l2'] = $post['billing_address_l2'];
			$updateData['city'] = $post['billing_city'];
			$updateData['state'] = $post['billing_state'];
			$updateData['zipcode'] = $post['billing_zipcode'];
			$updateData['country'] = $post['billing_country'];	
			$res = $this->business_model->insert_entry('gc_billing_address',$updateData);
		}

		if(isset($post['shipping_address_l1']) && !empty($post['shipping_address_l1']))
		{		
			$updateData = array();
			$updateData['user_id'] = $user_id;
			//$updateData['firstname'] = $post['shipping_firstname'];
			//$updateData['lastname'] = $post['shipping_lastname'];
			$updateData['address_l1'] = $post['shipping_address_l1'];
			$updateData['address_l1'] = $post['shipping_address_l1'];
			$updateData['address_l2'] = $post['shipping_address_l2'];
			$updateData['city'] = $post['shipping_city'];
			$updateData['state'] = $post['shipping_state'];
			$updateData['zipcode'] = $post['shipping_zipcode'];
			$updateData['country'] = $post['shipping_country'];	
			$res = $this->business_model->insert_entry('gc_shipping_address',$updateData);				
		}
		redirect(site_url('checkout'));
		
	}
	
	function invoice($order_id,$pdfType="")
	{
		
		$where1 = array();
		$where1['id'] = $order_id;
		$orderResult = $this->business_model->select_data_where('gc_orders',$where1);		
		
		$where2 = array();
		$where2['order_id'] = $order_id;
		$orderItemResult = $this->business_model->select_data_where_result('gc_order_items',$where2);	
			//echo $this->db->last_query();	
		$data = array();
		$data['orderResult'] =  $orderResult;
		$data['orderItemResult'] =  $orderItemResult;
                
                
	
		
		if(!empty($pdfType))
		{
		   
            if ($this->customer_model->is_invoice_user($order_id) === true) {
                    	$this->view('index/invoice',$data);
                    $styleSheet =  array();
			$styleSheet[] = '<link href="'.site_url().'assets/front/css/bootstrap.min.css" rel="stylesheet">';
			$styleSheet[] = '<link href="'.site_url().'assets/front/css/style.css" rel="stylesheet">';
			$styleSheet[] = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
			$styleSheet[] = '<style>  .col-xs-6 {    width: 45%;} .invoice-control a{display: none !important;} </style>';
			
			
			
			$data['pdfStyleSheetLinks'] = implode('',$styleSheet);
			//load the view and saved it into $html variable
			$html=$this->partial('index/invoice',$data,true);	
			
			//this the the PDF filename that user will get to download
			//$pdfFilePath = dirname(dirname(dirname(__FILE__))).'/uploads/invoice/'.$orderResult['order_number'].'.pdf';
			$pdfFilePath = $orderResult['order_number'].'.pdf';
	 
			//load mPDF library
			$this->load->library('m_pdf');
	 
		   //generate the PDF from the given html
			$this->m_pdf->pdf->WriteHTML($html);
	 
			//download it.
			$this->m_pdf->pdf->Output($pdfFilePath, "D");  
			
		}
		
		
	}
        }

}
