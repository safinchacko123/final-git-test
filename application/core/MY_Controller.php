<?php

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        //kill any references to the following methods
        $mthd = $this->router->method;
        if ($mthd == 'view' || $mthd == 'partial' || $mthd == 'set_template') {
            show_404();
        }

        //load base libraries, helpers and models
        $this->load->database();       
        $this->load->model('Settings_model');      

        //load in config items from the database
        $settings = $this->Settings_model->get_settings('mpcart');
        foreach ($settings as $key => $setting) {
            //special for the order status settings
            if ($key == 'order_statuses') {
                $setting = json_decode($setting, true);
            }
            $this->config->set_item($key, $setting);
        }

        //load the default libraries
        $this->load->library(array('session', 'auth', 'mp_cart'));
        $this->load->model(array('Customer_model', 'Category_model', 'Location_model'));
        $this->load->helper(array('url', 'file', 'string', 'html', 'language'));

        //if SSL is enabled in config force it here.
        if (config_item('ssl_support') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
            $CI = & get_instance();
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            redirect($CI->uri->uri_string());
        }
    }

}

//end Base_Controller

class Front_Controller extends Base_Controller
{

    //we collect the categories automatically with each load rather than for each function
    //this just cuts the codebase down a bit
    var $categories = '';
    //load all the pages into this variable so we can call it from all the methods
    var $pages = '';
    // determine whether to display gift card link on all cart pages
    //  This is Not the place to enable gift cards. It is a setting that is loaded during instantiation.
    var $gift_cards_enabled;

    function __construct()
    {
        parent::__construct();

        //load the theme package
        $this->load->add_package_path(APPPATH . 'themes/' . config_item('theme') . '/');

        //load library
        $this->load->library('Banners');

        //load needed models
        $this->load->model(array('Page_model', 'Product_model', 'Digital_Product_model', 'Gift_card_model', 'Option_model', 'Order_model', 'Settings_model'));

        //load helpers
        $this->load->helper(array('form_helper', 'formatting_helper'));

        //load common language
        $this->lang->load('common');

        //fill in our variables
        $this->categories = $this->Category_model->get_categories_tiered(0);
        $this->pages = $this->Page_model->get_pages_tiered();

        // check if giftcards are enabled
        $gc_setting = $this->Settings_model->get_settings('gift_cards');
        if (!empty($gc_setting['enabled']) && $gc_setting['enabled'] == 1) {
            $this->gift_cards_enabled = true;
        } else {
            $this->gift_cards_enabled = false;
        }
        $CI = & get_instance();
//        echo $CI->router->fetch_class();
//        echo " " . $CI->router->fetch_method();
//        $this->load->library('controllerlist');
//        echo '<pre>';
//        print_r($this->controllerlist->getControllers());
//        echo '</pre>';
//        exit;
        //print_r($this->controllerlist->getControllers());
//        exit;
    }

    /*
      This works exactly like the regular $this->load->view()
      The difference is it automatically pulls in a header and footer.
     */

    function view($view, $vars = array(), $string = false)
    {
        if ($string) {
            $result = $this->load->view('header', $vars, true);
            $result .= $this->load->view($view, $vars, true);
            $result .= $this->load->view('footer', $vars, true);

            return $result;
        } else {
            $this->load->view('header', $vars);
            $this->load->view($view, $vars);
            $this->load->view('footer', $vars);
        }
    }

    /*
      This function simply calls $this->load->view()
     */

    function partial($view, $vars = array(), $string = false)
    {
        if ($string) {
            return $this->load->view($view, $vars, true);
        } else {
            $this->load->view($view, $vars);
        }
    }

}

class Admin_Controller extends Base_Controller
{

    private $template;

    function __construct()
    {
        parent::__construct();

        $this->auth->is_logged_in(uri_string());

        //load the base language file
        $this->lang->load('admin_common');
        $this->lang->load('media');
    }

    function view($view, $vars = array(), $string = false)
    {
        //if there is a template, use it.
        $template = '';
        if ($this->template) {
            $template = $this->template . '_';
        }

        if ($string) {
            $result = $this->load->view('admin/' . $template . 'header', $vars, true);
            $result .= $this->load->view($view, $vars, true);
            $result .= $this->load->view('admin/' . $template . 'footer', $vars, true);

            return $result;
        } else {
            $this->load->view('admin/' . $template . 'header', $vars);
            $this->load->view($view, $vars);
            $this->load->view('admin/' . $template . 'footer', $vars);
        }

        //reset $this->template to blank
        $this->template = false;
    }

    /* Template is a temporary prefix that lasts only for the next call to view */

    function set_template($template)
    {
        $this->template = $template;
    }

}

class Cs_Front_Controller extends Base_Controller
{

    //we collect the categories automatically with each load rather than for each function
    //this just cuts the codebase down a bit
    var $categories = '';
    //load all the pages into this variable so we can call it from all the methods
    var $pages = '';
    // determine whether to display gift card link on all cart pages
    //  This is Not the place to enable gift cards. It is a setting that is loaded during instantiation.
    var $gift_cards_enabled;

    function __construct()
    {
        parent::__construct();

        //load the theme package
       // $this->load->add_package_path(APPPATH . 'themes/' . config_item('theme') . '/');
		// load database
		$this->load->database();
        //load library
        $this->load->library('Banners');
        $this->load->library('cart');
        $this->load->library('session');
        $this->load->library('customemail');
        //load needed models
        $this->load->model(array('location_model','Page_model', 'Product_model', 'Digital_Product_model', 'Gift_card_model', 'Option_model', 'Order_model', 'Settings_model','business_model'));

        //load helpers
        $this->load->helper(array('form_helper', 'formatting_helper','cookie'));

        //load common language
        $this->lang->load('common');

        //fill in our variables
        $this->categories = $this->Category_model->get_categories_tiered(0);
        $this->pages = $this->Page_model->get_pages_tiered();
        

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

    	// IP Based redirection
		$cur_site_url = $this->cur_site_url();
        if($cur_site_url !='http://in.qa.dropneed.com')
        {
			$allowCountries = array('IN');
			$IPdetails = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
		
		 
			$hour = time() + 3600*60*60;
		
			if(in_array($IPdetails['geoplugin_countryCode'],$allowCountries) && $_REQUEST['country'] !='else')
			{
				setcookie('allowCountryStatus','Y', $hour);
				$this->config->set_item('allowCountryStatus','Y');
				if($IPdetails['geoplugin_countryCode']=="IN")
				{
					$url = 'http://in.qa.dropneed.com';	
					setcookie('country_code', $IPdetails['geoplugin_countryCode'], $hour);
					setcookie('country_id', '99', $hour);

				}
				else
				{
					$url = site_url();
					setcookie('country_code', $IPdetails['geoplugin_countryCode'], $hour);				
				}
				$this->config->set_item('base_url', $url);	
			
				if($IPdetails['geoplugin_countryCode']=="IN" && $cur_site_url !='http://in.qa.dropneed.com')
				{
					redirect($url);
				}
			}
			else
			{
				$url = 'http://qa.dropneed.com/';
				setcookie('allowCountryStatus','N', $hour);
				$this->config->set_item('base_url', $url);
				$this->config->set_item('allowCountryStatus','N');
			}
		}
		else
		{
			setcookie('allowCountryStatus','Y', $hour);
			$this->config->set_item('allowCountryStatus','Y');
		}	
		//http://qa.dropneed.com 
		//echo config_item('allowCountryStatus');
    }

    /*
      This works exactly like the regular $this->load->view()
      The difference is it automatically pulls in a header and footer.
     */
	function cur_site_url(){
		  return sprintf(
			"%s://%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME']
		  );
		}

    function view($view, $vars = array(), $string = false)
    {
        if ($string) {
            $result = $this->load->view('front/header', $vars, true);
            $result .= $this->load->view('front/'.$view, $vars, true);
            $result .= $this->load->view('front/footer', $vars, true);

            return $result;
        } else {
            $this->load->view('front/header', $vars);
            $this->load->view('front/'.$view, $vars);
            $this->load->view('front/footer', $vars);
        }
    }

    /*
      This function simply calls $this->load->view()
     */

    function partial($view, $vars = array(), $string = false)
    {
        if ($string) {
            return $this->load->view('front/'.$view, $vars, true);
        } else {
            $this->load->view('front/'.$view, $vars);
        }
    }

}

