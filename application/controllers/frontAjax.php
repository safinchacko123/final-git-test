<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class FrontAjax extends Cs_Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        //$this->load->model(array('location_model','page_model','business_model'));
	
    }
    function index()
    {
			
	}
	
	function ajax_add_to_cart()
	{
		$post = $this->input->post();
		$where1 = array();
		$where1['venture_id'] = $post['venture_id'];
		$ventureDetail = $this->business_model->select_data_where('gc_venture_option',$where1);				
		$min_order_amount = !empty($ventureDetail['min_delivery_amount'])?$ventureDetail['min_delivery_amount']:'0';
		$where1 = array();
		$where1['id'] = $post['product_id'];
		$product = $this->business_model->select_data_where('gc_products',$where1);	
		$images = (array) json_decode($product['images']);
		$productImageArray = array_values($images);			
		// echo '<pre>'; print_r($product['images']); echo '</pre>';
		
		$price = $post['product_price'] * $post['popupProduct_qty']; 
		$variable = $post['product_name'];
		$convertedVariable = str_replace('™','',$post['product_name']);
		$option['addOns'] = isset($post['addOnPrice'])?$post['addOnPrice']:'';
		$option['product_id'] = $post['product_id'];
		$option['product_image'] = site_url().'uploads/images/small/'.$productImageArray[0]->filename;
		$data = array(
					   'id'      => 'sku_'.rand(),
					   'qty'     => $post['popupProduct_qty'],
					   'price'   => $post['product_price'],			   
					   'name'    => $convertedVariable,
					   'venture_id'    =>  $post['venture_id'],
					   'options' =>$option
		);		
				
		$this->cart->product_name_rules = '[:print:]';
		$rowID = $this->cart->insert($data); 	
		if(!empty($rowID))
		{
			$data =array();
			$data['min_order_amount'] = $min_order_amount;
			$cartBoxHtml =  $this->load->view('front/index/product_cart',$data,true);
			$json = array();
			$json['cartBoxHtml'] = $cartBoxHtml;
			echo json_encode($json);
		}
		else
		{
			$json = array();
			$json['cartBoxHtml'] = '<h1>Server Error</h1>';
			echo json_encode($json);
		}
	}
	
	function ajax_update_cart_item()
	{
		$post = $this->input->post();	
		$data = array(
		   'rowid' => $post['rowid'],
		   'qty'   => $post['qty']
		);
		$this->cart->update($data); 
		$data =array();
		$cartBoxHtml =  $this->load->view('front/index/product_cart',$data,true);
		$json = array();
		$json['cartBoxHtml'] = $cartBoxHtml;
		echo json_encode($json);			
	}
	
	function ajax_delete_cart_item()
	{
		$post = $this->input->post();			
		$data = array(
		   'rowid' => $post['rowid'],
		   'qty'   =>0
		);

		$this->cart->update($data); 

		$data =array();
		$cartBoxHtml =  $this->load->view('front/index/product_cart',$data,true);
		$json = array();
		$json['cartBoxHtml'] = $cartBoxHtml;
		echo json_encode($json);	
	}	
	
	function ajax_clear_cart_item()
	{
		$this->cart->destroy();
		$data =array();
		$cartBoxHtml =  $this->load->view('front/index/product_cart',$data,true);
		$json = array();
		$json['cartBoxHtml'] = $cartBoxHtml;
		echo json_encode($json);
	}
	
	function ajax_tab_product_listing()
	{	
		$post = $this->input->post();
		$venture_id = $post['venture_id'];
		$this->db->select("*");
		$this->db->from('gc_products');
		$this->db->join('gc_products_addons', 'gc_products_addons.product_id = gc_products.id', 'left');			
		$this->db->where('gc_products.added_by_cust ', $post['venture_id']);
		if($post['selected_type']=='most_selling')
		{
			$this->db->where("gc_products.id  IN (select distinct(product_id) as id from gc_order_items where venture_id=".$venture_id." )");		
		}
		if($post['selected_type']=='promotions')
		{
			$this->db->where('product_promotion_status','1');
			
		}		
		$query1 = $this->db->get();
        $productResult =  $query1->result_array();
        $data['productResult'] = $productResult;
        $data['heading'] = $post['headingName'];
		$productBoxHtml =  $this->load->view('front/index/tab_product_listing',$data,true);
		$json = array();
		$json['productBoxHtml'] = $productBoxHtml;
		echo json_encode($json);                
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
	
	function ajax_insertNewNote()
	{
		$userDetail = $this->session->userdata('userDetail');
		$post = $this->input->post();		
		$insertData['note_text'] = $post['note'];
		$insertData['user_id'] = $userDetail['user_id'];		
		$id= $this->business_model->insert_entry('gc_order_notes',$insertData);
		$json['status'] = 'Y';
		$json['id'] = $id;
		echo json_encode($json);		
	}
	
	
	function ajax_accountUpdate()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		$updateData = array();
		if(isset($post['password']))
		{
			$updateData['password'] = sha1($post['password']);
		}
		$updateData['emailNotification'] = $post['emailnotification'];
		//echo "<pre>"; print_r($post); echo "</pre>";
		$res = $this->business_model->update_entry('gc_customers','id',$user_id,$updateData);	
		$json = array();
		if($res)
		{
			$json['updateStatus'] = 'Y';
		}
		else
		{
			$json['updateStatus'] = 'N';
		}
		//echo $this->db->last_query();
		echo json_encode($json);
		
	}
	
	function ajax_personalInfoUpdate()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		$updateData = array();
		$updateData['firstname'] = $post['firstname'];
		$updateData['lastname'] = $post['lastname'];
		$updateData['phone'] = $post['phone'];
		//$updateData['city'] = $post['city'];
		$res = $this->business_model->update_entry('gc_customers','id',$user_id,$updateData);	
		$json = array();
		if($res)
		{
			$json['updateStatus'] = 'Y';
		}
		else
		{
			$json['updateStatus'] = 'N';
		}
		//echo $this->db->last_query();
		echo json_encode($json);
	}

	function ajax_accountAddressUpdate()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		$updateData = array();
		$updateData['address_l1'] = $post['address_l1'];
		$updateData['address_l2'] = $post['address_l2'];
		$updateData['city'] = $post['city'];
		$updateData['state'] = $post['state'];
		$updateData['zipcode'] = $post['zipcode'];
		$updateData['country'] = $post['country'];	
		
		$where1 = array();
		$where1['user_id'] = $user_id;
		$userResult = $this->business_model->select_data_where('gc_shipping_address',$where1);		
		
		$json = array();
		if($post['submitType']=='insert')
		{
			$updateData['user_id'] = $user_id;	
			$res = $this->business_model->insert_entry('gc_shipping_address',$updateData);		
			
		}
		else
		{
			$res = $this->business_model->update_entry('gc_shipping_address','id',$post['id'],$updateData);		
			//$json['updateStatus'] = 'N';
		}
		
		if(!empty($res))
		{
			$json['updateStatus'] = 'Y';
			
		}
		else
		{
			$json['updateStatus'] = 'N';
		}
		//echo"res = ". $json['updateStatus'];
		$data = array();
		$json['html'] = $this->load->view('front/index/myAccount/shippingAdress',$data,true); 
		//print_r($json);
		echo json_encode($json);		
	}
	
	function ajax_billingAdressUpdate()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		// echo '<pre>'; print_r($post); echo '</pre>';
		$updateData = array();
		$updateData['user_id'] = $user_id;
		$updateData['firstname'] = $post['firstname'];
		$updateData['lastname'] = $post['lastname'];
		$updateData['address_l1'] = $post['address_l1'];
		$updateData['address_l1'] = $post['address_l1'];
		$updateData['address_l2'] = $post['address_l2'];
		$updateData['city'] = $post['city'];
		$updateData['state'] = $post['state'];
		$updateData['zipcode'] = $post['zipcode'];
		$updateData['country'] = $post['country'];	
		if($post['submitType']=='insert')
		{
			$res = $this->business_model->insert_entry('gc_billing_address',$updateData);
			$id= $res;		
		}
		else
		{
			$res = $this->business_model->update_entry('gc_billing_address','id', $post['id'],$updateData);	
			$id= $post['id'];		
		}
		
		$json = array();
		if(!empty($res))
		{
			$json['updateStatus'] = 'Y';
			$json['id'] = $id;
		}
		else
		{
			$json['updateStatus'] = 'N';
		}
		$json['html'] = $this->load->view('front/index/myAccount/billingAdress',$data,true); 
		echo json_encode($json);		
	}
	
	function ajax_cardDetailUpdate()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		// echo '<pre>'; print_r($post); echo '</pre>';
		$updateData = array();		
		$updateData['firstname'] = $post['firstname'];
		$updateData['lastname'] = $post['lastname'];	
		$updateData['day'] = $post['card_date'];	
		$updateData['year'] = $post['card_year'];	
		$YecardLastDigit = substr($post['cardLastDigit'], -4);
		$updateData['cardLastDigit'] = $cardLastDigit;	
		
		
		$res = $this->business_model->update_entry('gc_cardDetail','user_id',$user_id,$updateData);	
		$json['updateStatus'] = 'Y';
		echo json_encode($json);	
	}	
	
	function ajax_returnKeywordResult()
	{
		
		
		$sessionData =  $this->session->userdata('locationDetail');
		
		$latitude = $sessionData['seller_hid_lat'];
		$longitude = $sessionData['seller_hid_lng'];
		
		$post = $this->input->post();
		$ventureDeliveryAddrQry = "SELECT venture_id as id FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' ";
		if(isset($sessionData['category_id']) && !empty($sessionData['category_id']))
		{
			$qry1 = "select gc_products.id,gc_products.name from gc_products,gc_category_products where
					gc_category_products.product_id = gc_products.id and gc_category_products.category_id=".$sessionData['category_id']."
					and gc_products.name like '%".$post['headerText']."%'
					and gc_products.added_by_cust 	in(SELECT venture_id as added_by_cust FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and gc_venture_delivery_address.venture_id = gc_products.added_by_cust )
					";
			$productResult =  $this->business_model->run_result_query($qry1);

			$qry2 = "select gc_customers.id,gc_customers.company as name from gc_customers,gc_business_categories,gc_map_vr_vt where
			 gc_venture_address.venture_id = gc_customers.id 
			 and gc_map_vr_vt.venture_id = gc_customers.id
			 and gc_business_categories.customers_id =gc_map_vr_vt.vendor_id and gc_business_categories.categories_id = ".$sessionData['category_id']."
			 
			 and  gc_customers.company like '%".$post['headerText']."%'
			 and gc_customers.id 	in(SELECT venture_id as id FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and gc_venture_delivery_address.venture_id = gc_customers.id )
			 ";
			
			$ventureResult =  $this->business_model->run_result_query($qry2);		
			
		}
		else
		{
			$qry1 = "select gc_products.id,gc_products.name from gc_products where
			  gc_products.name like '%".$post['headerText']."%'
			 and gc_products.added_by_cust 	in(SELECT venture_id as added_by_cust FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and gc_venture_delivery_address.venture_id = gc_products.added_by_cust )
			 ";
			$productResult =  $this->business_model->run_result_query($qry1);

			$qry2 = "select gc_customers.id,gc_customers.company as name from gc_customers where
			 
			  
			   gc_customers.company like '%".$post['headerText']."%'
			  and gc_customers.id 	in(SELECT venture_id as id FROM gc_venture_delivery_address  WHERE  gc_venture_delivery_address.locality = '".$sessionData['seller_hid_locality']."' and gc_venture_delivery_address.venture_id = gc_customers.id )
			 ";
			
			$ventureResult =  $this->business_model->run_result_query($qry2);		
		}
		$json = array();
		$ulHTML = array();
		if(!empty($ventureResult))
		{
			
			foreach($ventureResult as $venture)
			{
				$ulHTML[] =  '<li data-type="venture" data-id="'.$venture['id'].'" data-name="'.$venture['name'].'" ><strong>Seller  :</strong>'.$venture['name'].'</li>';
			}
			// $json['ulHTML'] = implode("",$ulHTML);
		}		
		
		if(!empty($productResult))
		{
			foreach($productResult as $product)
			{
				$ulHTML[] =  '<li data-type="product" data-id="'.$product['id'].'" data-name="'.$product['name'].'" ><strong>Product :</strong>'.$product['name'].'</li>';
			}
		}
		
		if(!empty($ulHTML))
		{
			$json['hasRecord'] = 'Y';
			$json['ulHTML'] = implode("",$ulHTML);
		}
		else
		{
			$json['hasRecord'] = 'Y';
		}
		echo json_encode($json);
		//echo "<pre>ss"; print_r($result); echo "</pre>";
	}
	
	function ajax_fetchVantureList()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 
		//echo '<pre>xxxxxx'; print_r($post); echo '</pre>';
		$latitude = $post['lat'];
		$longitude = $post['lng'];	
		
		$areaSelectResult =  $this->business_model->getArea_byLocation($latitude,$longitude);
		//echo "<pre>ghg"; print_r($areaSelectResult); echo "</pre>"; die();
		$ulHTML =  array();
		$json = array();
		if(!empty($areaSelectResult)) 
		{		
			$ulHTML[] = '<option value="" >Select your area</option>';
			foreach($areaSelectResult as $area)
			{
				$ulHTML[] ='<option data-lat="'.$area['lat'].'" data-locality="'.$area['locality'].'" data-lng="'.$area['lng'].'" value="'.$area['venture_id'].'" >'.$area['sublocality'].'</option>';
			}
			$json['hasRecord'] = 'Y';
			$json['ulHTML'] = implode("",$ulHTML);
		}
		else
		{
			$json['hasRecord'] = 'N';
		}
		echo json_encode($json);
	}	
	
	function ajax_saveProductRating()
	{
		$post = $this->input->post();
		$user_id = $this->session->userdata('userDetail')['user_id']; 		
		$insertItem = array();
		$insertItem['product_id'] = $post['pid'];
		$insertItem['user_id'] = $user_id;
		$insertItem['rating'] = $post['score'];
		$where1 = array();
		$where1['product_id'] = $post['pid'];
		$where1['user_id'] = $user_id;
		$ratingDetail = $this->business_model->select_data_where('gc_product_rating',$where1);	
		if(empty($ratingDetail))
		{
			$this->business_model->insert_entry('gc_product_rating',$insertItem);
			
			$ratingDetail = $this->business_model->select_data_where('gc_product_rating',$where1);	
			$ratingCount = $this->business_model->countRows('gc_product_rating','product_id',$post['pid']);
			$ratingSum = $this->business_model->sumOfCoulmn('gc_product_rating','rating','product_id',$post['pid']);
			if($ratingCount != 0 && $ratingSum !=0)
			{
				$rating_avg = $ratingSum/$ratingCount;
				$updateData =  array();
				$updateData['rating_avg'] = $rating_avg;
				$this->business_model->update_entry('gc_products','id',$post['pid'],$updateData);
			}
			//rating_avg
		}
		$json['isInsert'] = 'Y';
		echo json_encode($json);		
	}
	
	function ajax_cancelOrder()
	{
		$post = $this->input->post();
		$updateData =  array();
		$updateData['status'] = 'order_canceled';
		$updateData['cancellation_contant'] = $post['cancellation_contant'];
		$this->business_model->update_entry('gc_orders','id',$post['id'],$updateData);
		$json['hadDone'] = 'Y';
		echo json_encode($json);
		
	}
	
	function ajax_topCartBox()
	{
		$cartBoxHtml =  $this->load->view('front/index/area_cartBox',$data,true);
		$json = array();
		$json['cartBoxHtml'] = $cartBoxHtml;
		echo json_encode($json);
	}
}
