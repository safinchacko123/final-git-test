<?php

class Secure extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();

        $this->load->model(array('location_model'));
        $this->load->model('Role_model');
        $this->load->model('Mapvrpr_model');
        $this->load->model('Mapvrvt_model');
        $this->load->model('Store_model');
        //Edited
        $this->load->model('Currency_model');
        $this->load->model('Country_model');
        $this->customer = $this->mp_cart->customer();
        //print_r($this->customer); exit;
        $this->load->helper('rand_no');
    }

    function index() {
        show_404();
    }

    function login($ajax = false, $as = false) {
        // Check whether Already Loged in with Admin 
        $CI = & get_instance();
        $admin = $CI->session->userdata('admin');
        if ($admin) {
            if ($admin['access'] == 'Admin') {
                $this->session->set_flashdata('message', 'You are already loged in with Admin account');
                redirect('/admin/orders');
            }
        }
        //find out if they're already logged in, if they are redirect them to the my account page
        $redirect = $this->Customer_model->is_logged_in(false, false);
        //if they are logged in, we send them back to the my_account by default, if they are not logging in
        if ($redirect) {
            redirect('secure/my_account/');
        }

        $data['page_title'] = 'Login';
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $data['loginAs'] = 'lnkCust';
        if ($this->input->post('loginType')) {
            $data['loginAs'] = $this->input->post('loginType');
        }
        if ($as == 'vendor') {
            $data['loginAs'] = 'lnkVendor';
        }
        $this->load->helper('form');
        $data['redirect'] = $this->session->flashdata('redirect');
        $submitted = $this->input->post('submitted');
        if ($submitted) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');
            $redirect = $this->input->post('redirect');
            $login = $this->Customer_model->login($email, $password, $remember);

            if (count($login) == 2) {
                $this->session->set_flashdata('redirect', $redirect);
                $this->session->set_flashdata('error', 'Email is not verified');
                if ($this->input->post('loginType') == 'lnkVendor') {
                    redirect('secure/login/as/vendor');
                } else {
                    redirect('secure/login');
                }
            } else {
                if ($login) {
                    // $login = $this->auth->login_admin($email, $password, $remember);
                    // Force login as Admin
                    $login = $this->auth->force_login_admin();

                    if ($redirect == '') {
                        //if there is not a redirect link, send them to the my account page
                        $redirect = 'secure/my_account';
                    }
                    //to login via ajax
                    if ($ajax) {
                        die(json_encode(array('result' => true)));
                    } else {
                        redirect($redirect);
                    }
                } else {
                    //this adds the redirect back to flash data if they provide an incorrect credentials
                    //to login via ajax
                    if ($ajax) {
                        die(json_encode(array('result' => false)));
                    } else {
                        $data = array('email' => $email);
                        $this->session->set_flashdata('redirect', $redirect);
                        $this->session->set_flashdata('error', lang('login_failed'));
                        $this->session->set_flashdata('loginDtls', $data);

                        if ($this->input->post('loginType') == 'lnkVendor') {
                            redirect('secure/login/as/vendor');
                        } else {
                            redirect('secure/login', $data);
                        }
                    }
                }
            }
        }

        // load other page content 
        //$this->load->model('banner_model');
        $this->load->helper('directory');

        //if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
        //$data['banners']	= $this->banner_model->get_banners();
        //$data['ads']		= $this->banner_model->get_banners(true);
        $data['categories'] = $this->Category_model->get_categories_tiered(0);
        $data['email'] = '';
        $data['password'] = '';
        if ($this->session->flashdata('loginDtls')) {
            $data['email'] = $this->session->flashdata('loginDtls')['email'];
        }

        $this->view('login', $data);
    }

    function logout() {
        $this->Customer_model->logout();
        $this->session->unset_userdata('admin');
        redirect('secure/login');
    }

    function register() {

        $redirect = $this->Customer_model->is_logged_in(false, false);
        //if they are logged in, we send them back to the my_account by default
        if ($redirect) {
            redirect('secure/my_account');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div>', '</div>');

        //$data['registerAs'] = 'lnkCust';
        $data['registerAs'] = 'lnkVendor';
        if ($this->input->post('registerType')) {
            $data['registerAs'] = $this->input->post('registerType');
        } else if (isset($_GET['as'])) {
            $data['registerAs'] = $_GET['as'];
        }
        $registerType = $this->input->post('registerType');

        if (isset($_GET['as'])) {
            $registerType = $_GET['as'];
        }

        /*
          we're going to set this up early.
          we can set a redirect on this, if a customer is checking out, they need an account.
          this will allow them to register and then complete their checkout, or it will allow them
          to register at anytime and by default, redirect them to the homepage.
         */
        $data['redirect'] = $this->session->flashdata('redirect');

        $data['page_title'] = lang('account_registration');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        //default values are empty if the customer is new

        $data['company'] = '';
        $data['firstname'] = '';
        $data['lastname'] = '';
        $data['email'] = '';
        $data['phone'] = '';
        $data['address1'] = '';
        $data['address2'] = '';
        $data['city'] = '';
        $data['state'] = '';
        $data['zip'] = '';

        //Edited
        $businesscategorydata  = $this->input->post('categories_id');
        if(empty($businesscategorydata)){
            $data['businesscategoryids']='';
        } else {
            $data['businesscategoryids']= $businesscategorydata;
        }

        // Check if loggin in with vendor account
        if ($registerType != 'lnkCust') {
            $this->form_validation->set_rules('company', 'lang:address_company', 'trim|required|max_length[128]');
        }

        $this->form_validation->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]|callback_check_email');
        $this->form_validation->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[10]|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
        $this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
        $this->form_validation->set_rules('chkTerms', 'Please Accept Terms & conditions', 'trim|required|numeric|max_length[1]');
        //Edited
        if ($registerType == 'lnkVendor')
        {
            $this->form_validation->set_rules('addressline1', 'lang:address_line1', 'trim|required');
            $this->form_validation->set_rules('addressline2', 'lang:address_line2', 'trim');
            $this->form_validation->set_rules('city', 'lang:city', 'trim|required');
            $this->form_validation->set_rules('state', 'lang:state', 'trim|required');
            $this->form_validation->set_rules('vendor_country', 'lang:country', 'trim|required');
            $this->form_validation->set_rules('vendor_zipcode', 'lang:zipcode', 'trim|required');
            //$this->form_validation->set_rules('business_currency', 'lang:business_currency', 'required');
            if(empty($businesscategorydata)){
                $this->form_validation->set_rules('categories_id[]', 'lang:business_category', 'required');
            }
        } elseif($registerType == 'lnkPartner') {
            $this->form_validation->set_rules('partneraddressline1', 'lang:address_line1', 'trim|required');
            $this->form_validation->set_rules('partneraddressline2', 'lang:address_line2', 'trim');
            $this->form_validation->set_rules('partnercity', 'lang:city', 'trim|required');
            $this->form_validation->set_rules('partnerstate', 'lang:state', 'trim|required');
            $this->form_validation->set_rules('partnercountry', 'lang:country', 'trim|required');
            $this->form_validation->set_rules('partnerzipcode', 'lang:zipcode', 'trim|required');
        }

        $data['businesscategories']  = $this->Category_model->get_categories();
        $data['businesscurriencies'] = $this->Currency_model->getCurrencies();
        $data['countries']           = $this->Country_model->getCountries();

        //$this->form_validation->set_rules('country', 'lang:country', 'trim|required|max_length[25]');
        //$this->form_validation->set_rules('zipcode', 'lang:zipcode', 'trim|required|max_length[11]');

        if ($registerType == 'lnkPartner') {
            if (empty($_FILES['passport']['name'])) {
                $this->form_validation->set_rules('passport', 'passport document', 'required');
            }
            if (empty($_FILES['id_proof']['name'])) {
                $this->form_validation->set_rules('id_proof', 'Id proof docuent', 'required');
            }
        }
        $partnerInvalid = '';
        if ($registerType == 'lnkVendor' && $this->input->post('partner_no') != '') {
            $partnerNo = $this->input->post('partner_no');
            // Check whether partner_no is valid            
            $this->load->model('Partner_model');
            $partnerRec = $this->Partner_model->checkUniqueId($partnerNo);
            if ($partnerRec) {
                // Partner no is valid lets proceed
            } else {
                $partnerInvalid = 'Provided partner no is invalid please recheck';
            }
        }

        if ($this->form_validation->run() == FALSE) {
            //if they have submitted the form already and it has returned with errors, reset the redirect
            if ($this->input->post('submitted')) {
                $data['redirect'] = $this->input->post('redirect');
            }

            // load other page content 
            //$this->load->model('banner_model');
            $this->load->helper('directory');

            $data['categories'] = $this->Category_model->get_categories_tiered(0);

            $data['error'] = validation_errors();
            $data['error'] .= $partnerInvalid;

            $this->view('register', $data);
        } else {

            $save['id'] = false;
            $save['firstname'] = $this->input->post('firstname');
            $save['lastname'] = $this->input->post('lastname');
            $save['email'] = $this->input->post('email');
            $save['phone'] = $this->input->post('phone');
            $save['company'] = $this->input->post('company');
            $save['active'] = $this->config->item('new_customer_status');
            $save['created_on'] = date('Y-m-d G:i:s');
            //$save['email_subscribe'] = intval((bool) $this->input->post('email_subscribe'));

            //Edited
            if ($registerType == 'lnkVendor')
            {
                $save['address_l1'] = $this->input->post('addressline1');
                $save['address_l2'] = $this->input->post('addressline2');
                $save['city'] = $this->input->post('city');
                $save['state'] = $this->input->post('state');
                $save['country'] = $this->input->post('vendor_country');
                $save['zipcode'] = $this->input->post('vendor_zipcode'); 
                //$save['business_currency_id'] = $this->input->post('business_currency');                
            } elseif($registerType == 'lnkPartner'){
                $save['address_l1'] = $this->input->post('partneraddressline1');
                $save['address_l2'] = $this->input->post('partneraddressline2');
                $save['city'] = $this->input->post('partnercity');
                $save['state'] = $this->input->post('partnerstate');
                $save['country'] = $this->input->post('partnercountry');
                $save['zipcode'] = $this->input->post('partnerzipcode');
            }

            //print_r($businessdata); exit();

            $save['password'] = $this->input->post('password');

            $redirect = $this->input->post('redirect');

            //if we don't have a value for redirect
            if ($redirect == '') {
                $redirect = 'secure/login';
            }

            $this->load->helper('rand_no');
            $verify_key = generateRandomString('100');
            $save['verify_email_key'] = $verify_key;
            if ($registerType == 'lnkCust') {
                // save the customer info and get their new id
                $id = $this->Customer_model->save($save);
            } else if ($registerType == 'lnkVendor' || $registerType == 'lnkPartner') {
                $saveData = array();
                // save the Vendor info into admin table and get their new id
                $saveData['firstname'] = $this->input->post('firstname');
                $saveData['lastname'] = $this->input->post('lastname');
                $saveData['email'] = $this->input->post('email');
                $saveData['username'] = $this->input->post('email');
                $saveData['verify_email_key'] = $save['verify_email_key'] = $verify_key;

                $userRole = 'Vendor';
                if ($registerType == 'lnkPartner') {
                    $userRole = 'Partner';
                }

                $this->CI = & get_instance();
                $this->CI->db->select('id');
                $this->CI->db->where('role_name', $userRole);
                $this->CI->db->limit(1);
                $result = $this->CI->db->get('roles');
                $result = $result->row_array();

                $saveData['access'] = $save['access'] = $userRole;
                $saveData['role_id'] = $save['role_id'] = $result['id'];

                if ($this->input->post('password') != '') {
                    $saveData['password'] = $this->input->post('password');
                }
                // $adminId = $this->auth->save($saveData);
                // $save['admin_id'] = $adminId;
                // save the customer info and get their new id
                $id = $this->Customer_model->save($save);

                //Edited
                if ($registerType == 'lnkVendor')
                {
                    if(!empty($businesscategorydata))
                    {
                        foreach ($businesscategorydata as $categorydata) {
                            $saveCat['customers_id']  = $id;
                            $saveCat['categories_id'] = $categorydata;
                            $cat_id = $this->Customer_model->saveBusinessCategories($saveCat);
                        }
                    }
                }

                if ($userRole == 'Vendor' && $this->input->post('partner_no') != '') {
                    // Create mapping record of Vendor => Partner
                    $this->load->model('Mapvrpr_model');
                    $this->load->model('Partner_model');
                    $this->Mapvrpr_model->mapVendorPartner($id, $this->input->post('partner_no'), $this->Partner_model);
                }

                // If role is partner then create unique identification id
                if ($userRole == 'Partner') {
                    $this->load->helper('rand_no');
                    $this->load->model('Partner_model');
                    //$unique_id = genUniqueId($this->Partner_model, 8);
                    // Add mapping record in Partners table
                    $data = array('unique_id' => '', 'customer_id' => $id);
                    $this->Partner_model->create($data);

                    // Upload documents 
                    $config['allowed_types'] = 'gif|jpg|png';
                    //$config['max_size'] = $this->config->item('size_limit');
                    $config['upload_path'] = 'uploads/docs';
                    $config['encrypt_name'] = true;
                    $config['remove_spaces'] = true;
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('passport')) {
                        $upload_data = $this->upload->data();
                        $this->Partner_model->createDocument($id, 'passport', $upload_data['file_name']);
                    }
                    if ($this->upload->do_upload('id_proof')) {
                        $upload_data = $this->upload->data();
                        $this->Partner_model->createDocument($id, 'idproof', $upload_data['file_name']);
                    }
                }
            }

            /* send an email */
            // get the email template
            $res = $this->db->where('id', '9')->get('canned_messages');
            $row = $res->row_array();

            // set replacement values for subject & body
            // {customer_name}
            $row['subject'] = str_replace('{customer_name}', $this->input->post('firstname') . ' ' . $this->input->post('lastname'), $row['subject']);
            $row['content'] = str_replace('{customer_name}', $this->input->post('firstname') . ' ' . $this->input->post('lastname'), $row['content']);

            // {url}
            $row['subject'] = str_replace('{url}', $this->config->item('base_url'), $row['subject']);
            $row['content'] = str_replace('{url}', $this->config->item('base_url'), $row['content']);

            // {site_name}
            $row['subject'] = str_replace('{site_name}', $this->config->item('company_name'), $row['subject']);
            $row['content'] = str_replace('{site_name}', $this->config->item('company_name'), $row['content']);

            $row['content'] = str_replace('{verify_email}', $this->config->item('base_url') . 'secure/verify_email/' . $verify_key, $row['content']);

            // Send mail to confirm email with SMTP
            /* $config = Array(
              'protocol' => 'smtp',
              'smtp_host' => 'ssl://smtp.gmail.com',
              'smtp_port' => 465,
              'smtp_user' => 'pm.opensrc@gmail.com',
              'smtp_pass' => 'livelikeking',
              'mailtype' => 'html'
              ); */
            $CI = & get_instance();
            $configEmail = $CI->config->item('email_config');
            $this->load->library('email', $configEmail);
            $this->email->set_newline("\r\n");


            $this->email->from($configEmail['smtp_user'], 'Market Place');
            $this->email->to($save['email']);
            $this->email->subject($row['subject']);
            $this->email->message(html_entity_decode($row['content']));

            $this->session->set_flashdata('message', sprintf(lang('registration_success'), $this->input->post('firstname')));

            if ($this->email->send()) {
                redirect($redirect);
            } else {
                //echo $this->email->print_debugger();
            }

            $data['logginAs'] = 'lnkCust';
            if ($this->input->post('registerType')) {
                $data['logginAs'] = $this->input->post('registerType');
            }

            /**
             * COMMENTING SIMPLE MAIL SENDING FUNCTIONALITY
             * INSTEAD USING SMTP
             */
            /*
              $this->load->library('email');

              $config['mailtype'] = 'html';

              $this->email->initialize($config);

              $this->email->from($this->config->item('email'), $this->config->item('company_name'));
              $this->email->to($save['email']);
              $this->email->bcc($this->config->item('email'));
              $this->email->subject($row['subject']);
              $this->email->message(html_entity_decode($row['content']));

              $this->email->send();

              $this->session->set_flashdata('message', sprintf(lang('registration_thanks'), $this->input->post('firstname')));
             */

            //lets automatically log them in
            //$this->Customer_model->login($save['email'], $this->input->post('confirm'));
            //we're just going to make this secure regardless, because we don't know if they are
            //wanting to redirect to an insecure location, if it needs to be secured then we can use the secure redirect in the controller
            //to redirect them, if there is no redirect, the it should redirect to the homepage.
            redirect($redirect);
        }
    }

    function regcomplete() {
        $this->load->view('reg_complete');
    }

    /**
     * Method for verifying registered email address
     *
     * 
     * @package    Default
     * @param string $key
     * @author     Mujaffar S added on 11 July 2015
     */
    function verify_email($key) {
        // Check records against verify key in admin table to confirm registration
        $this->CI = & get_instance();
        $this->CI->db->select('id');
        $this->CI->db->where('verify_email_key', $key);
        $this->CI->db->where('email_verified', '0');
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('customers');
        $result = $result->row_array();

        if ($result) {
            // First of all update admin table with email verified 1
            $admin['email_verified'] = '1';
            $this->CI->db->where('id', $result['id']);
            $this->CI->db->update('admin', $admin);

            // Now update customers table with email verified 1
            $customer['email_verified'] = '1';
            $this->CI->db->where('id', $result['id']);
            $this->CI->db->update('customers', $admin);
            $this->session->set_flashdata('message', 'Email verified successfully');
            redirect('secure/login');
        } else {
            $this->session->set_flashdata('message', 'Verification link expired');
            redirect('secure/login');
        }
    }

    function check_email($str) {
        if (!empty($this->customer['id'])) {
            $email = $this->Customer_model->check_email($str, $this->customer['id']);
        } else {
            $email = $this->Customer_model->check_email($str);
        }

        if ($email) {
            $this->form_validation->set_message('check_email', lang('error_email'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function forgot_password() {
        $data['page_title'] = lang('forgot_password');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;
        $submitted = $this->input->post('submitted');
        if ($submitted) {
            $this->load->helper('string');
            $email = $this->input->post('email');

            $reset = $this->Customer_model->reset_password($email);

            if ($reset) {
                $this->session->set_flashdata('message', 'Password reset instructions sent to email address');
            } else {
                $this->session->set_flashdata('error', lang('error_no_account_record'));
            }
            redirect('secure/forgot_password');
        }

        // load other page content 
        //$this->load->model('banner_model');
        $this->load->helper('directory');

        //if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
        //$data['banners']	= $this->banner_model->get_banners();
        //$data['ads']		= $this->banner_model->get_banners(true);
        $data['categories'] = $this->Category_model->get_categories_tiered();


        $this->view('forgot_password', $data);
    }

    /**
     * Function to reset password by verifying reset token
     * 
     * @author Mujaffar 25-7-15
     * @param string $token
     */
    function reset_password($token) {
        $this->load->model('Resetreq_model');
        // Check records against token key in customer table to confirm reset password request
        $resultset = $this->Resetreq_model->check_token($token);

        if ($this->input->post('submit')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'New password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'Confirm password', 'required|matches[password]');
            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $errMsg = '';
                foreach ($errors As $err) {
                    $errMsg .= $err . '<br/>';
                }
                $this->session->set_flashdata('error', $errMsg);
                redirect('secure/reset_password/' . $token);
            } else {
                $customer = array();
                $customer['id'] = $resultset['cust_id'];
                $customer['password'] = $this->input->post('password');
                $this->Customer_model->save($customer);

                $this->session->set_flashdata('message', 'Your account password changed successfully');
                redirect('secure/login');
            }
        } else if ($resultset) {
            $this->view('partials/reset_password');
        } else {
            $this->session->set_flashdata('error', 'Invalid URL');
            $this->view('partials/reset_password');
        }
    }

    function redirector($token) {
        redirect('secure/reset_password' . $token);
    }

    function my_account($offset = 0) {
        $data['role'] = array('id' => '', 'name' => '');
        // Check whether Role present (vendor/partner)
        if (count($this->customer)) {
            if ($this->customer['role_id']) {
                // This means customer have role of Vendor/Partner
                $data['role'] = array('id' => $this->customer['role']['role_id'], 'name' => $this->customer['role']['role_name']);
            }
        }
        //make sure they're logged in
        $this->Customer_model->is_logged_in('secure/my_account/');

        //$data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $data['customer'] = (array) $this->Customer_model->get_customer($this->customer['id']);
        $data['partners'] = array();
        if ($this->customer['role_id']) {
            if ($this->customer['role']['role_name'] == 'Vendor') {
                // Associate vendor partners, Get vendor related partners
                $data['partners'] = $this->Customer_model->get_vendor_partner($this->customer['id']);
            }
        }

        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);

        $data['page_title'] = 'Welcome ' . $data['customer']['firstname'] . ' ' . $data['customer']['lastname'];
        $data['customer_addresses'] = $this->Customer_model->get_address_list($data['customer']['id']);

        // load other page content 
        //$this->load->model('banner_model');
        $this->load->model('order_model');
        $this->load->helper('directory');
        $this->load->helper('date');

        //if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
        //	$data['banners']	= $this->banner_model->get_banners();
        //	$data['ads']		= $this->banner_model->get_banners(true);
        $data['categories'] = $this->Category_model->get_categories_tiered(0);


        // paginate the orders
        $this->load->library('pagination');

        $config['base_url'] = site_url('secure/my_account');
        $config['total_rows'] = $this->order_model->count_customer_orders($this->customer['id']);
        $config['per_page'] = '15';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['orders_pagination'] = $this->pagination->create_links();

        $data['orders'] = $this->order_model->get_customer_orders($this->customer['id'], $offset);


        //if they're logged in, then we have all their acct. info in the cookie.

        /*
          This is for the customers to be able to edit their account information
         */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'lang:address_company', 'trim|max_length[128]');
        $this->form_validation->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
        //$this->form_validation->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]|callback_check_email');
        $this->form_validation->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[32]');
        //$this->form_validation->set_rules('email_subscribe', 'lang:account_newsletter_subscribe', 'trim|numeric|max_length[1]');

        if ($this->input->post('oldPassword') != '' || $this->input->post('password') != '' || $this->input->post('confirm') != '') {
            $this->form_validation->set_rules('oldPassword', 'Old password', 'required|sha1');
            $this->form_validation->set_rules('password', 'New password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'Confirm password', 'required|matches[password]');
        } else {
            $this->form_validation->set_rules('password', 'Password');
            $this->form_validation->set_rules('confirm', 'Confirm Password');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->view('my_account', $data);
        } else {
            $formValid = true;
            // Check whether request for change password, Then validate old password
            if ($this->input->post('oldPassword') != '') {
                $custRec = $this->Customer_model->validate_pass($this->customer['id'], $this->input->post('oldPassword'));
                if (!count($custRec)) {
                    $formValid = false;
                    $this->session->set_flashdata('error', 'Entered old password is incorrect');
                }
            }

            if ($formValid) {
                $customer = array();
                $customer['id'] = $this->customer['id'];
                $customer['company'] = $this->input->post('company');
                $customer['firstname'] = $this->input->post('firstname');
                $customer['lastname'] = $this->input->post('lastname');
                //$customer['email'] = $this->input->post('email');
                $customer['phone'] = $this->input->post('phone');
                //$customer['email_subscribe'] = intval((bool) $this->input->post('email_subscribe'));
                if ($this->input->post('password') != '') {
                    $customer['password'] = $this->input->post('password');
                }

                $this->mp_cart->save_customer($this->customer);
                $this->Customer_model->save($customer);

                $this->session->set_flashdata('message', lang('message_account_updated'));
            }
            redirect('secure/my_account');
        }
    }

    function my_downloads($code = false) {

        if ($code !== false) {
            $data['downloads'] = $this->Digital_Product_model->get_downloads_by_code($code);
        } else {
            $this->Customer_model->is_logged_in();

            $customer = $this->mp_cart->customer();

            $data['downloads'] = $this->Digital_Product_model->get_user_downloads($customer['id']);
        }

        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $data['page_title'] = lang('my_downloads');

        $this->view('my_downloads', $data);
    }

    function download($link) {
        $filedata = $this->Digital_Product_model->get_file_info_by_link($link);

        // missing file (bad link)
        if (!$filedata) {
            show_404();
        }

        // validate download counter
        if ($filedata->max_downloads > 0) {
            if (intval($filedata->downloads) >= intval($filedata->max_downloads)) {
                show_404();
            }
        }


        // increment downloads counter
        $this->Digital_Product_model->touch_download($link);

        // Deliver file
        $this->load->helper('download');
        force_download('uploads/digital_uploads/', $filedata->filename);
    }

    function set_default_address() {
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $customer = $this->mp_cart->customer();
        $save['id'] = $customer['id'];

        if ($type == 'bill') {
            $save['default_billing_address'] = $id;

            $customer['bill_address'] = $this->Customer_model->get_address($id);
            $customer['default_billing_address'] = $id;
        } else {

            $save['default_shipping_address'] = $id;

            $customer['ship_address'] = $this->Customer_model->get_address($id);
            $customer['default_shipping_address'] = $id;
        }

        //update customer db record
        $this->Customer_model->save($save);

        //update customer session info
        $this->mp_cart->save_customer($customer);

        echo "1";
    }

    function address_form($id = 0) {

        $customer = $this->mp_cart->customer();

        //grab the address if it's available
        $data['id'] = false;
        $data['company'] = $customer['company'];
        $data['firstname'] = $customer['firstname'];
        $data['lastname'] = $customer['lastname'];
        $data['email'] = $customer['email'];
        $data['phone'] = $customer['phone'];
        $data['address1'] = $data['address2'] = $data['city'] = $data['country_id'] = '';
        $data['zone_id'] = $data['zip'] = '';
//        $data['latitude'] = $data['lat'];
//        $data['longitude'] = $data['long'];

        if ($id != 0) {
            $a = $this->Customer_model->get_address($id);
            if ($a['customer_id'] == $this->customer['id']) {
                //notice that this is replacing all of the data array
                //if anything beyond this form data needs to be added to
                //the data array, do so after this portion of code
                $data = $a['field_data'];
                $data['id'] = $id;
            } else {
                redirect('/'); // don't allow cross-customer editing
            }

            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        //get the countries list for the dropdown
        $data['countries_menu'] = $this->location_model->get_countries_menu();

        if ($id == 0) {
            //if there is no set ID, the get the zones of the first country in the countries menu
            $data['zones_menu'] = $this->location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
        } else {
            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        $this->load->library('form_validation');
        $this->load->helper('form_validator');
        $form = validate_address($this->form_validation);

        if ($form->run() == FALSE) {
            if (validation_errors() != '') {
                echo validation_errors();
            } else {
                $this->partial('address_form', $data);
            }
        } else {
            $a = array();
            $a['id'] = ($id == 0) ? '' : $id;
            $a['customer_id'] = $this->customer['id'];
            $a['field_data']['company'] = $this->input->post('company');
            $a['field_data']['firstname'] = $this->input->post('firstname');
            $a['field_data']['lastname'] = $this->input->post('lastname');
            $a['field_data']['email'] = $this->input->post('email');
            $a['field_data']['phone'] = $this->input->post('phone');
            $a['field_data']['address1'] = $this->input->post('address1');
            $a['field_data']['address2'] = $this->input->post('address2');
            $a['field_data']['city'] = $this->input->post('city');
            $a['field_data']['zip'] = $this->input->post('zip');
            $a['field_data']['latitude'] = $this->input->post('lat');
            $a['field_data']['longitude'] = $this->input->post('long');

            // get zone / country data using the zone id submitted as state
            $country = $this->location_model->get_country(set_value('country_id'));
            $zone = $this->location_model->get_zone(set_value('zone_id'));
            if (!empty($country)) {
                //$a['field_data']['zone'] = $zone->code;  // save the state for output formatted addresses
                $a['field_data']['country'] = $country->name; // some shipping libraries require country name
                $a['field_data']['country_code'] = $country->iso_code_2; // some shipping libraries require the code 
                $a['field_data']['country_id'] = $this->input->post('country_id');
                $a['field_data']['zone_id'] = $this->input->post('zone_id');
            }

            $this->Customer_model->save_address($a);
            $this->session->set_flashdata('message', lang('message_address_saved'));
            echo 1;
        }
    }

    function delete_address() {
        $id = $this->input->post('id');
        // use the customer id with the addr id to prevent a random number from being sent in and deleting an address
        $customer = $this->mp_cart->customer();
        $this->Customer_model->delete_address($id, $customer['id']);
        echo $id;
    }

    /**
     * Function to delete venture account
     * 
     * @author     Mujaffar S added on 9 Aug 2015
     */
    function delete_venture() {
        $id = $this->input->post('id');
        // use the customer id with the addr id to prevent a random number from being sent in and deleting an address
        $this->Customer_model->delete($id);
        echo $id;
        die();
    }

    /**
     * Method to add new partners associating to vendors
     * 
     * @author Mujaffar S      Created on 14 July 15
     * @param type $id
     */
    function partner_form($id = 0) {

        $customer = $this->mp_cart->customer();

        //grab the address if it's available
        $data['id'] = false;
        $data['company'] = '';
        $data['firstname'] = '';
        $data['lastname'] = '';
        $data['email'] = '';
        $data['phone'] = '';
        $data['address1'] = '';
        $data['address2'] = '';
        $data['city'] = '';
        $data['country_id'] = '';
        $data['zone_id'] = '';
        $data['zip'] = '';

        if ($id != 0) {
            $a = $this->Customer_model->get_address($id);
            if ($a['customer_id'] == $this->customer['id']) {
                //notice that this is replacing all of the data array
                //if anything beyond this form data needs to be added to
                //the data array, do so after this portion of code
                $data = $a['field_data'];
                $data['id'] = $id;
            } else {
                redirect('/'); // don't allow cross-customer editing
            }

            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        //get the countries list for the dropdown
        $data['countries_menu'] = $this->location_model->get_countries_menu();

        if ($id == 0) {
            //if there is no set ID, the get the zones of the first country in the countries menu
            $data['zones_menu'] = $this->location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
        } else {
            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'lang:address_company', 'trim|max_length[128]');
        $this->form_validation->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('address1', 'lang:address:address', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('address2', 'lang:address:address', 'trim|max_length[128]');
        $this->form_validation->set_rules('city', 'lang:address:city', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('country_id', 'lang:address:country', 'trim|required|numeric');
        //$this->form_validation->set_rules('zone_id', 'lang:address:state', 'trim|required|numeric');
        $this->form_validation->set_rules('zip', 'lang:address:zip', 'trim|required|max_length[32]');


        if ($this->form_validation->run() == FALSE) {
            if (validation_errors() != '') {
                echo validation_errors();
            } else {
                $this->partial('partials/partner_form', $data);
            }
        } else {

            $a = array();
            $a['id'] = ($id == 0) ? '' : $id;
            $a['customer_id'] = $this->customer['id'];
            $a['field_data']['company'] = $this->input->post('company');
            $a['field_data']['firstname'] = $this->input->post('firstname');
            $a['field_data']['lastname'] = $this->input->post('lastname');
            $a['field_data']['email'] = $this->input->post('email');
            $a['field_data']['phone'] = $this->input->post('phone');
            $a['field_data']['address1'] = $this->input->post('address1');
            $a['field_data']['address2'] = $this->input->post('address2');
            $a['field_data']['city'] = $this->input->post('city');
            $a['field_data']['zip'] = $this->input->post('zip');

            // get zone / country data using the zone id submitted as state
            $country = $this->location_model->get_country(set_value('country_id'));
            $zone = $this->location_model->get_zone(set_value('zone_id'));
            if (!empty($country)) {
                //$a['field_data']['zone'] = $zone->code;  // save the state for output formatted addresses
                $a['field_data']['country'] = $country->name; // some shipping libraries require country name
                $a['field_data']['country_code'] = $country->iso_code_2; // some shipping libraries require the code 
                $a['field_data']['country_id'] = $this->input->post('country_id');
                $a['field_data']['zone_id'] = $this->input->post('zone_id');
            }

            // Create users account for Partner role
            $this->add_partner($a);

//            $this->Customer_model->save_address($a);
//            $this->session->set_flashdata('message', lang('message_address_saved'));
            echo 1;
        }
    }

    function showrecords() {
        $this->view('partials/showrecords');
    }

    /**
     * Function to add vendor related partners to database
     */
    function add_partner($data) {

        // Create users account for Partner role
        $data['id'] = false;
        $data['firstname'] = $this->input->post('firstname');
        $data['lastname'] = $this->input->post('lastname');
        $data['email'] = $this->input->post('email');
        $data['phone'] = $this->input->post('phone');
        $data['company'] = $this->input->post('company');
        $data['active'] = $this->config->item('new_customer_status');
        $data['email_subscribe'] = intval((bool) $this->input->post('email_subscribe'));
        $data['password'] = 'tempPassword';

        $this->load->helper('rand_no');
        $verify_key = generateRandomString(100);
        $data['verify_email_key'] = $verify_key;

        // Get Partner related record
        $partnerRec = $this->Role_model->get_byname('Partner');
        $data['role_id'] = $partnerRec['id'];

        $data['access'] = 'partner';

        if ($this->input->post('password') != '') {
            $data['password'] = $this->input->post('password');
        }
        $customer_id = $data['customer_id'];
        unset($data['field_data']);
        unset($data['customer_id']);

        // save the customer info and get their new id
        $id = $this->Customer_model->save($data);

        // Add vendor / partner mapping records
        $this->Mapvrpr_model->map_vr_pr($customer_id, $id);
    }

    /**
     * Method to delete partner
     * 
     * @author Mujaffar 22 July 15
     */
    function delete_partner() {
        $id = $this->input->post('id');
        // use the customer id with the addr id to prevent a random number from being sent in and deleting an address
        //$customer = $this->mp_cart->customer();
        $this->Customer_model->delete_partner($id);
        echo $id;
    }

    function location() {
        $this->view('customer/location');
    }

    function manage_address() {
        $data['customer'] = $this->customer;
        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);
        $this->view('customer/manage_address', $data);
    }

    function manage_ventures() {
        $data['venture_css_js']=1;
        if ($this->customer['access'] == 'Vendor') {
            // Get vendor related ventures details
            $data['ventures'] = $this->Customer_model->get_vendor_venture($this->customer['id']);
            $this->view('vendor/manage_ventures', $data);
        } else {
            redirect('secure/my_account');
        }
    }

    function venture_address() {
        if ($this->customer['access'] == 'Venture') {
            $this->load->model('Ventureaddress_model');
            $data['customer'] = $this->customer;
            $data['address'] = $this->Ventureaddress_model->getByVenture($this->customer['id']);

            $this->view('venture/manage_address', $data);
        } else {
            redirect('secure/my_account');
        }
    }

    function venture_store() {
        $data['customer'] = $this->customer;
        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);

        // Get venture store list
        $data['stores'] = $this->Store_model->get_stores($this->customer['id']);
        $this->view('venture/manage_store', $data);
    }

    /**
     * Method to add new ventures associating to vendors
     * 
     * @author Mujaffar S      Created on 26 July 15
     * @param type $id
     */
    function venture_form($id = 0) {
        $customer = $this->mp_cart->customer();

        //grab the address if it's available
        $data['id'] = false;
        $data['company'] = '';
        $data['firstname'] = '';
        $data['lastname'] = '';
        $data['email'] = '';
        $data['phone'] = '';
        $data['address1'] = '';
        $data['address2'] = '';
        $data['city'] = '';
        $data['country_id'] = '';
        $data['zone_id'] = '';
        $data['zip'] = '';

        if ($id != 0) {
            $a = $this->Customer_model->get_address($id);
//            excerpt
            if ($this->customer['id']) {
                //notice that this is replacing all of the data array
                //if anything beyond this form data needs to be added to
                //the data array, do so after this portion of code
                $data = $a['field_data'];
                $data['id'] = $id;
            } else {
                redirect('/'); // don't allow cross-customer editing
            }

            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        //get the countries list for the dropdown
        $data['countries_menu'] = $this->location_model->get_countries_menu();

        if ($id == 0) {
            //if there is no set ID, the get the zones of the first country in the countries menu
            $data['zones_menu'] = $this->location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
        } else {
            $data['zones_menu'] = $this->location_model->get_zones_menu($data['country_id']);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'lang:address_company', 'trim|max_length[128]');
        $this->form_validation->set_rules('firstname', 'lang:address_firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:address_lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:address_email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('phone', 'lang:address_phone', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('address1', 'lang:address:address', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('address2', 'lang:address:address', 'trim|max_length[128]');
        $this->form_validation->set_rules('city', 'lang:address:city', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('country_id', 'lang:address:country', 'trim|required|numeric');
        //$this->form_validation->set_rules('zone_id', 'lang:address:state', 'trim|required|numeric');
        $this->form_validation->set_rules('zip', 'lang:address:zip', 'trim|required|max_length[32]');


        if ($this->form_validation->run() == FALSE) {
            if (validation_errors() != '') {
                echo validation_errors();
            } else {
                $this->partial('venture/venture_form', $data);
            }
        } else {
            $a = array();
            $a['id'] = ($id == 0) ? '' : $id;
            $a['customer_id'] = $this->customer['id'];
            $a['field_data']['company'] = $this->input->post('company');
            $a['field_data']['firstname'] = $this->input->post('firstname');
            $a['field_data']['lastname'] = $this->input->post('lastname');
            $a['field_data']['email'] = $this->input->post('email');
            $a['field_data']['phone'] = $this->input->post('phone');
            $a['field_data']['address1'] = $this->input->post('address1');
            $a['field_data']['address2'] = $this->input->post('address2');
            $a['field_data']['city'] = $this->input->post('city');
            $a['field_data']['zip'] = $this->input->post('zip');

            // get zone / country data using the zone id submitted as state
            $country = $this->location_model->get_country(set_value('country_id'));
            $zone = $this->location_model->get_zone(set_value('zone_id'));
            if (!empty($country)) {
                //$a['field_data']['zone'] = $zone->code;  // save the state for output formatted addresses
                $a['field_data']['country'] = $country->name; // some shipping libraries require country name
                $a['field_data']['country_code'] = $country->iso_code_2; // some shipping libraries require the code 
                $a['field_data']['country_id'] = $this->input->post('country_id');
                $a['field_data']['zone_id'] = $this->input->post('zone_id');
            }

            // Create users account for Venture role
            if ($this->input->post('updateCase') == 'addVenture') {
                $this->add_venture($a);
            }

            $a['coverage_area'] = $this->input->post('coverage_area');
            $a['latitude'] = $this->input->post('lat');
            $a['longitude'] = $this->input->post('long');
            $this->Customer_model->save_address($a);
//            $this->session->set_flashdata('message', lang('message_address_saved'));
            echo 1;
        }
    }

    /**
     * Function to add vendor related partners to database
     */
    function add_venture($data) {
        // Create users account for Partner role
        $data['id'] = false;
        $data['firstname'] = $this->input->post('firstname');
        $data['lastname'] = $this->input->post('lastname');
        $data['email'] = $this->input->post('email');
        $data['phone'] = $this->input->post('phone');
        $data['company'] = $this->input->post('company');
        $data['active'] = $this->config->item('new_customer_status');

        $this->load->helper('rand_no');
        $random_pass = generateRandomString(6);

        $this->load->model('Canned_msges_model');
        $rowMsg = $this->Canned_msges_model->getMsg(11);

        $rowMsg->content = str_replace('{customer_name}', $data['firstname'] . " " . $data['lastname'], $rowMsg->content);

        $rowMsg->content = str_replace('{email}', $data['email'], $rowMsg->content);
        $rowMsg->content = str_replace('{password}', $random_pass, $rowMsg->content);
        $rowMsg->content = str_replace('{site_name}', $this->config->item('company_name'), $rowMsg->content);

        // Send mail to confirm email with SMTP
        $CI = & get_instance();
        $configEmail = $CI->config->item('email_config');
        $this->load->library('email', $configEmail);
        $this->email->set_newline("\r\n");
        $this->email->from($configEmail['smtp_user'], $this->config->item('site_name'));
        $this->email->to($data['email']);
        $this->email->subject($this->config->item('site_name') . ': Password Reset');

        $this->email->message($rowMsg->content);
        $this->email->send();

        //$data['email_subscribe'] = intval((bool) $this->input->post('email_subscribe'));
        $data['password'] = sha1($random_pass);

        // Get Partner related record
        $partnerRec = $this->Role_model->get_byname('Venture');
        $data['role_id'] = $partnerRec['id'];

        $data['access'] = 'Venture';
        $data['email_verified'] = '1';

        if ($this->input->post('password') != '') {
            $data['password'] = $this->input->post('password');
        }
        $customer_id = $data['customer_id'];
        unset($data['field_data']);
        unset($data['customer_id']);

        // save the customer info and get their new id
        $id = $this->Customer_model->save($data);

        // Add vendor / venture mapping records
        $this->Mapvrvt_model->map_vr_vt($customer_id, $id);
    }

    function favorites() {
        $data['title'] = 'My Favorites';

        $authDtl = $this->auth->get_auth();


        $data['customer_id'] = $authDtl['id'];
        //total number of reviews
        $data['total'] = $this->Product_model->getTotalWishlist($authDtl['id']);

        $data['wishlist'] = $this->Product_model->getWishlist($authDtl['id']);

        $this->load->library('pagination');

        $config['base_url'] = site_url($this->config->item('admin_folder') . '/products/wishlist/');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = 15;
        $config['uri_segment'] = 4;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        // $this->pagination->initialize($config);

        $this->view('wishlist', $data);
    }

    function removeFromWishlist($wishlistId) {
        $result = $this->Product_model->deleteFromWishList($wishlistId);

        if ($result) {
            $this->session->set_flashdata('message', 'Product has been removed sucessfully');
        } else {
            $this->session->set_flashdata('error', 'Product has been not been removed');
        }
        redirect('secure/favorites');
    }
    
    /* By Lynn 17 May Start */
    function venture_delivery_address() {
		
        if ($this->customer['access'] == 'Venture') {
			
			$this->load->model('Ventureaddress_model');
			$post  = $this->input->post();
			if(!empty($post))
			{
				$this->Ventureaddress_model->delete_delivery_address($this->customer['id']);
				$polygon_latlong_array = explode("|",$post['polygon_latlong']);
				foreach($polygon_latlong_array as $latlong)
				{
					$latlong_array = explode(",",$latlong);
					$lat = $latlong_array[0];
					$lng = $latlong_array[1];
					$geoResult = $this->Ventureaddress_model->getGeocodeByLatlng($lat,$lng);
					$insertData = array();
					$insertData['venture_id'] = $this->customer['id'];
					$insertData['lat'] = $lat;
					$insertData['lng'] = $lng;
					
					$insertData['sublocality'] 	= isset($geoResult['sublocality'])?mysql_real_escape_string($geoResult['sublocality']):'';
					$insertData['locality'] 	= isset($geoResult['locality'])?mysql_real_escape_string($geoResult['locality']):'';
					$insertData['admin_2'] 		= isset($geoResult['admin_2'])?$geoResult['admin_2']:'';
					$insertData['admin_1'] 		= isset($geoResult['admin_1'])?$geoResult['admin_1']:'';
					$insertData['country']		= isset($geoResult['country'])?$geoResult['country']:'';
					$insertData['country_code']	= isset($geoResult['country_code'])?$geoResult['country_code']:'';
					$insertData['postal_code'] 	= isset($geoResult['postal_code'])?$geoResult['postal_code']:'';
					
					$this->Ventureaddress_model->create_delivery_address($insertData);
					
				}
				redirect('secure/venture_delivery_address');
				
			}
			else
			{
				$deliveryAddress = $this->Ventureaddress_model->getDeliveryAddressByVenture($this->customer['id']);
				$data = array();
				
				$data['deliveryAddress'] =  $deliveryAddress;
				$data['customer'] = $this->customer;
				$det = $this->Ventureaddress_model->getByVenture($this->customer['id']);
				$data['ventureAddress'] = $this->Ventureaddress_model->getByVenture($this->customer['id']);
			
				$this->view('venture/manage_delivery_address', $data);					
				
			}
			
        } else {
            redirect('secure/my_account');
        }
    }
    /* By Lynn 17 May end */

}
