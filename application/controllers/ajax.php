<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Ajax extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model'));
        $this->load->model(array('product_model'));
        $this->load->model(array('store_model'));
        $this->load->model('Role_model');
        $this->load->model('Mapvrpr_model');
        $this->load->model('Mapvrvt_model');
        //Edited
        $this->load->model('Country_model');
        $this->customer = $this->mp_cart->customer();
    }

    function index() {
        $this->view('index/home');
    }

    /**
     * Home action to handle home page request
     * @author     Mujaffar S added on 11 July 2015
     */
    function getReleventImages() {
        $productName = $this->input->post('productName');
        if ($productName != '') {
            $arrProduct = explode(" ", $productName);
            $results = $this->product_model->getReleventProducts($arrProduct);
            $imagePath = array();
            foreach ($results As $rowResult) {
                if ($rowResult->images != 'false') {
                    $decoded = json_decode($rowResult->images);

                    foreach ($decoded As $key => $val) {
                        $imagePath[] = $val->filename;
                    }
                }
            }
            echo json_encode($imagePath);
        }
    }

    /**
     * Method to calculate lat/long values depending upon provided address
     * @author     Mujaffar S added on 8 Aug 2015
     */
    function getGeoLoc() {
        $postData = $this->input->post();
        // Get default address of Customer
        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);
        $country_id = $this->input->post('country_id');
        // get zone / country data using the zone id submitted as state
        $country = $this->location_model->get_country($country_id);

//        $zone = $this->input->post('zone_id');
        // Generate address to get Lat, Lang values
        $address = '';
        if (!empty($country)) {
            if ($this->input->post('address1') != '') {
                $address = $this->input->post('address1') . ",";
            }
            if ($postData['city'] != '') {
                $address .= $postData['city'] . ",";
            }
            if ($country->iso_code_2 != '') {
                $address .= $country->iso_code_2 . ",";
            }
            if ($country->name != '') {
                $address .= $country->name;
            }
            $address = str_replace(" ", "+", $address);

            $latLong = $this->getGeoCounty($address);

            // Update lat long values for address
            if (count($latLong['southwest']) > 0) {
//                $data = array('id' => $this->customer['default_billing_address'], 'longitude' => $latLong['southwest']['lng'], 'latitude' => $latLong['southwest']['lat']);
//                $this->Customer_model->save_latlong($data);
            }
        }
        $zoom = 11;
        if ($latLong['southwest']['lat'] == '' || $latLong['southwest']['lat'] == 18.9110642) {
            $latLong['southwest']['lat'] = 1.770915;
        }
        if ($latLong['southwest']['lng'] == '' || $latLong['southwest']['lng'] == 172.4458955) {
            $latLong['southwest']['lng'] = -72.330245;
            $zoom = 4;
        }
        //1.770915, -72.330245
        echo "[" . json_encode(array('southwest' => $latLong['southwest'], 'zoom' => $zoom)) . "]";
        //$this->view('customer/geomap', $viewData);
    }

    /**
     * Method to calculate and return latitude and longitude data for provided address
     * 
     * @author Mujaffar S  Created on 04 Aug 2015
     */
    function getGeoCounty($geoAddress) {
        $url = $this->config->item('geoLocation') . '?address=' . $geoAddress . '&sensor=false';
        $get = file_get_contents($url);
        $geoData = json_decode($get);
        $returnArr = array();
        if (isset($geoData->results[0])) {
            foreach ($geoData->results[0]->address_components as $addressComponet) {
                if (in_array('administrative_area_level_2', $addressComponet->types)) {
                    $returnArr['name'] = $addressComponet->long_name;
                }
                if (isset($geoData->results[0]->geometry->bounds)) {
                    $returnArr['southwest'] = array('lat' => $geoData->results[0]->geometry->bounds->southwest->lat,
                        'lng' => $geoData->results[0]->geometry->bounds->southwest->lng);
                    $returnArr['northeast'] = array('lat' => $geoData->results[0]->geometry->bounds->northeast->lat,
                        'lng' => $geoData->results[0]->geometry->bounds->northeast->lng);
                } else {
                    if (isset($geoData->results[0]->geometry->viewport)) {
                        $returnArr['southwest'] = array('lat' => $geoData->results[0]->geometry->viewport->southwest->lat,
                            'lng' => $geoData->results[0]->geometry->viewport->southwest->lng);
                        $returnArr['northeast'] = array('lat' => $geoData->results[0]->geometry->viewport->northeast->lat,
                            'lng' => $geoData->results[0]->geometry->viewport->northeast->lng);
                    }
                }
            }
        }
        return $returnArr;
    }

    /**
     * Method to edit venture
     */
    function add_venture() {
        //Edited
        $datas['countries']  = $this->Country_model->getCountries();
        $postData = $this->input->post();
        
        $venture_logo = $this->input->post('venture_logo');
        if($venture_logo!='') {
            $imgdata = $this->input->post('dataimage');
            $imgdata = explode(",", $imgdata);
            $imgdata[1] = str_replace(' ', '+', $imgdata[1]);
            //$fileName = $postData['venture_logo'];
            $serverFile = time().$venture_logo;
            $fp = fopen('uploads/images/venturelogo/'.$serverFile,'w'); //Prepends timestamp to prevent overwriting
            fwrite($fp, base64_decode($imgdata[1]));
            fclose($fp);
            $data['customer_logo'] = $serverFile;
        }
        
        
        //Edited 23/03/2016
        $cuisinedatas        = $this->input->post('cuisine_id');
        $daydatas            = $this->input->post('days');
        $startTime           = $this->input->post('startTime');
        $endTime             = $this->input->post('endTime');
        $datas['cuisines']   = $this->Customer_model->getCuisines();
        if ($postData['updateCase'] == 'venAdd') {
            $this->partial('venture/add_venture',$datas);
        } else if ($postData['updateCase'] == 'venAddSave') {
            //Edited 23/03/2016
            $this->load->library('form_validation');
            $this->form_validation->set_rules('minimum_delivery_amount', 'lang:minimum_delivery_amount', 'trim|required|numeric|floatval');
            $this->form_validation->set_rules('delivery_fee', 'lang:delivery_fee', 'trim|numeric|floatval');
            $this->form_validation->set_rules('avg_delivery_time', 'lang:avg_delivery_time', 'trim|required|numeric');
            $this->form_validation->set_rules('payment_method', 'lang:payment_method', 'required');

            /*if(empty($cuisinedatas))
            {
                $this->form_validation->set_rules('cuisine_id[]', 'lang:cuisine', 'required');
            }*/
            
            if(empty($daydatas))
            {
                $this->form_validation->set_rules('days[]', 'lang:working_days', 'required');
            }

            $this->form_validation->set_rules('startTime[]', 'lang:start_time', 'validate_time_12hr|trim');
            $this->form_validation->set_rules('endTime[]', 'lang:end_time', 'validate_time_12hr|trim');
            
            $this->form_validation->set_rules('venture_logo', 'lang:venture_logo', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $error = validation_errors();
                echo json_encode(array('err' => $error));
            } else {            
            // Check record for email already present
            $emailFound = $this->Customer_model->check_email($this->input->post('email'));

            if ($emailFound) {
                echo json_encode(array('status' => 'error', 'err' => '<p>Email already registered.</p>'));
                die();
            } else {
                // Create users account for Partner role
                $data['id'] = false;
                $data['firstname'] = $this->input->post('firstname');
                $data['lastname'] = $this->input->post('lastname');
                $data['email'] = $this->input->post('email');
                $data['phone'] = $this->input->post('phone');
                $data['company'] = $this->input->post('company');
                $data['active'] = '1';

                //Edited
                $data['address_l1'] = $this->input->post('ventureaddress_line1');
                $data['address_l2'] = $this->input->post('ventureaddress_line2');
                $data['city'] = $this->input->post('venturecity');
                $data['state'] = $this->input->post('venturestate');
                $data['country'] = $this->input->post('venturecountry');
                $data['zipcode'] = $this->input->post('venturezipcode');
                $data['license_no'] = $this->input->post('license_no');

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
                unset($data['field_data']);
                unset($data['customer_id']);

                // save the customer info and get their new id
                $id = $this->Customer_model->save($data);

                    //Edited 23/03/2016
                    if($this->input->post('minimum_delivery_amount')!='')
                    {
                        $saveOp['venture_id']          = $id;
                        $saveOp['min_delivery_amount'] = $this->input->post('minimum_delivery_amount');
                        $saveOp['avg_delivery_time']   = $this->input->post('avg_delivery_time');
                        $saveOp['delivery_fee']        = $this->input->post('delivery_fee');
                        $saveOp['payment_type']        = $this->input->post('payment_method');
                        $option_id = $this->Customer_model->saveVentureOption($saveOp);
                    }

                    if(!empty($cuisinedatas))
                    {
                        foreach ($cuisinedatas as $cuisinedata) {
                            $saveCus['venture_id']  = $id;
                            $saveCus['cuisine_id']  = $cuisinedata;
                            $cuisine_id = $this->Customer_model->saveCuisine($saveCus);
                        }
                    }

                    if(!empty($daydatas))
                    {
                        $check_day = array();
                        foreach ($daydatas as $daydata) {
                            foreach ($daydata as $daydat) {
                                
                                if($daydat['daydata']!='' && !in_array($daydat['daydata'], $check_day))
                                {
                                    $check_day[] = $daydat['daydata'];
                                    $saveDay['venture_id'] = $id;
                                    $saveDay['weekday']    = $daydat['daydata'];
                                    $saveDay['OpenTime']   = date("H:i:s", strtotime($daydat['starttime'])); //$daydat['starttime'];
                                    $saveDay['CloseTime']  = date("H:i:s", strtotime($daydat['endtime'])); //$daydat['endtime'];
                                    $day_id = $this->Customer_model->saveWorkingDays($saveDay);
                                }
                            }
                        }                        
                    }

                // Add vendor / venture mapping records
                $this->Mapvrvt_model->map_vr_vt($this->customer['id'], $id);

                echo json_encode(array('status' => 'success'));
                die();
            }
        }
    }
    }

    /**
     * Function to add venture address with mapping on Google Map
     */
    function manage_venture_address() {
        $postData = $this->input->post();

        if ($postData['reqFor'] == 'getForm') {
            //get the countries list for the dropdown
            $postData['countries_menu'] = $this->location_model->get_countries_menu();
            $this->partial('venture/add_venture_address', $postData);
        } else if ($postData['reqFor'] == 'addAdr') {
            $this->load->model('Ventureaddress_model');
            // Generate array to store in db
            $this->Ventureaddress_model->create_address($postData['formData']);
            echo json_encode(array('status' => 'success'));
        } else if ($postData['reqFor'] == 'editForm') {
            $this->load->model('Ventureaddress_model');
            // Get details
            $adrData = $this->Ventureaddress_model->get($postData['address_id']);
            $passData['countries_menu'] = $this->location_model->get_countries_menu();
            $passData['adrDtls'] = $adrData;
            $this->partial('venture/edit_venture_address', $passData);
        } else if ($postData['reqFor'] == 'updateForm') {
            $this->load->model('Ventureaddress_model');
            // Get details
            if ($postData['updateBy'] == 'addressId') {
                $this->Ventureaddress_model->update_address(false, $postData['formData'], $postData['address_id']);
            } else {
                $this->Ventureaddress_model->update_address($postData['venture_id'], $postData['formData'], false);
            }
            echo json_encode(array('status' => 'success'));
        } else if ($postData['reqFor'] == 'deleteForm') {
            $this->load->model('Ventureaddress_model');
            // Get details
            $this->Ventureaddress_model->delete_address($postData['address_id']);
            echo json_encode(array('status' => 'success'));
        }
    }

    /**
     * Method to edit venture
     */
    function edit_venture() {
        $postData = $this->input->post();

        $venture_logo = $this->input->post('venture_logo');
        if($venture_logo!='')
        {
            $imgdata = $this->input->post('dataimage');
            $imgdata = explode(",", $imgdata);
            $imgdata[1] = str_replace(' ', '+', $imgdata[1]);
            $serverFile = time().$venture_logo;
            $fp = fopen('uploads/images/venturelogo/'.$serverFile,'w'); //Prepends timestamp to prevent overwriting
            fwrite($fp, base64_decode($imgdata[1]));
            fclose($fp);
            $customer_logo = $serverFile;
        }

        if ($postData['updateCase'] == 'venGet') {

            // Get venture details from customers table
            $venture        = $this->Customer_model->get_venture($postData['venture_id']);
            //Edited 23/03/2016
            $ventureoption   = $this->Customer_model->get_ventureoption($postData['venture_id']);
            $venturecuisin   = $this->Customer_model->get_venturecuisin($postData['venture_id']);
            
            $venturetiming   = $this->Customer_model->get_venturetiming($postData['venture_id']);
            //$timings         = $this->Customer_model->get_venturetimings($postData['venture_id']);
            //print_r($venturetiming);
            
            $venturecuisinarray = array();
            foreach ($venturecuisin as $venturecus)
            {
                $venturecuisinarray[] = $venturecus->cuisine_id;
            }
            $passData = array();
            if ($venture) {
                $passData = array('status' => 'success', 'id' => $venture->id, 
                    'firstname' => $venture->firstname,
                    'lastname' => $venture->lastname, 'email' => $venture->email, 
                    'company' => $venture->company, 
                    'phone' => $venture->phone,'address_l1' => $venture->address_l1,
                    'address_l2' => $venture->address_l2,'city' => $venture->city,
                    'state' => $venture->state,'country' => $venture->country,
                    'zipcode' => $venture->zipcode,'license_no' => $venture->license_no,
                    'countries'=>$this->Country_model->getCountries(),
                     //Edited 23/03/2016
                    'cuisines'=>$this->Customer_model->getCuisines(),
                    'venturecuisines'=>$venturecuisinarray,
                    
                    'venturetiming'=>$venturetiming,
                    //'timings'=>$timings,
                    
                    'min_delivery_amount' => $ventureoption->min_delivery_amount,
                    'avg_delivery_time' => $ventureoption->avg_delivery_time,
                    'delivery_fee' => $ventureoption->delivery_fee,'payment_type' => $ventureoption->payment_type,
                    'customer_logo'=>$venture->customer_logo
                    );
            } else {
                $passData = array('status' => 'error', 'id' => '', 'firstname' => '',
                    'lastname' => '', 'email' => '', 'company' => '', 'phone' => '',
                     'address_l1'=> '', 'address_l2'=> '', 'city'=> '', 'state'=> '',
                     'country'=> '', 'zipcode'=> '', 'license_no'=>'','min_delivery_amount' =>'','avg_delivery_time' =>'',
                     'delivery_fee' =>'','payment_type' =>'','customer_logo'=>'');
            }
            $this->partial('venture/edit_venture', $passData);
        } else if ($postData['updateCase'] == 'venUpdate') {
            //Edited 23/03/2016
            $cuisinedatas = $postData['cuisin_id'];
            $daydatas     = $postData['days'];
            $startTime    = $postData['startTime'];
            $endTime      = $postData['endTime'];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('min_delivery_amount', 'lang:minimum_delivery_amount', 'trim|required|numeric|floatval');
            $this->form_validation->set_rules('delivery_fee', 'lang:delivery_fee', 'trim|numeric|floatval');
            $this->form_validation->set_rules('avg_delivery_time', 'lang:avg_delivery_time', 'trim|required|numeric');
            $this->form_validation->set_rules('payment_type', 'lang:payment_method', 'required');
            
            /*if(empty($cuisinedatas))
            {
                $this->form_validation->set_rules('cuisin_id[]', 'lang:cuisine', 'required');
            }*/
            
            if(empty($daydatas))
            {
                $this->form_validation->set_rules('days[]', 'lang:working_days', 'required');
            }
            
            $this->form_validation->set_rules('startTime[]', 'lang:start_time', 'validate_time_12hr|trim');
            $this->form_validation->set_rules('endTime[]', 'lang:end_time', 'validate_time_12hr|trim');
            
            if ($this->form_validation->run() == FALSE) {
                $error = validation_errors();
                echo json_encode(array('err' => $error));
            } else { 
                if(isset($customer_logo) && $customer_logo!='') {  
                    
                    $venturedetail  = $this->Customer_model->get_venture($postData['id']);
                    if($venturedetail->customer_logo!='') {
                        unlink("uploads/images/venturelogo/" . $venturedetail->customer_logo);
                    }                  
                    
            $updateData = array('id' => $postData['id'], 'firstname' => $postData['firstname'],
                 'lastname' => $postData['lastname'], 'company' => $postData['company'], 
                 'phone' => $postData['phone'],
                 'address_l1' => $postData['ventureaddress_line1'], 'address_l2' => $postData['ventureaddress_line2'],
                 'city' => $postData['venturecity'], 'state' => $postData['venturestate'],
                 'country' => $postData['venturecountry'], 'zipcode' => $postData['venturezipcode'],
                     'license_no' => $postData['license_no'],'customer_logo'=>$customer_logo
                    );
                } else {
                    $updateData = array('id' => $postData['id'], 'firstname' => $postData['firstname'],
                     'lastname' => $postData['lastname'], 'company' => $postData['company'], 
                     'phone' => $postData['phone'],
                     'address_l1' => $postData['ventureaddress_line1'], 'address_l2' => $postData['ventureaddress_line2'],
                     'city' => $postData['venturecity'], 'state' => $postData['venturestate'],
                     'country' => $postData['venturecountry'], 'zipcode' => $postData['venturezipcode'],
                 'license_no' => $postData['license_no']
                );
                }                
                //Edited 23/03/2016
                $updateOptionData = array('id' => $postData['id'],'min_delivery_amount' => $postData['min_delivery_amount'],
                     'avg_delivery_time' => $postData['avg_delivery_time'],'delivery_fee' => $postData['delivery_fee'],
                     'payment_type' => $postData['payment_type']
                    );
                
                if(!empty($postData['days']))
                {
                    $check_day = array();
                    $this->Customer_model->deleteWorkingDays($postData['id']);
                    foreach ($postData['days'] as $daydata) {                        
                        foreach ($daydata as $daydat) {
                            if($daydat['daydata']!='' && !in_array($daydat['daydata'], $check_day))
                            {
                                $check_day[] = $daydat['daydata'];
                                $saveDay['venture_id'] = $postData['id'];
                                $saveDay['weekday']    = $daydat['daydata'];
                                $saveDay['OpenTime']   = date("H:i:s", strtotime($daydat['starttime'])); 
                                $saveDay['CloseTime']  = date("H:i:s", strtotime($daydat['endtime'])); 
                                $day_id = $this->Customer_model->updateWorkingDays($saveDay);
                            }
                        }
                    }
                }
                
                $updateCuisineData = array('id' => $postData['id'],'cuisinedatas' => $postData['cuisin_id']);
                //Update venture details from customers table
                $venture        = $this->Customer_model->update_venture($updateData);
                $ventureoption  = $this->Customer_model->update_ventureoption($updateOptionData);
                $venturecuisine = $this->Customer_model->update_venturecuisine($updateCuisineData);
            echo json_encode(array('status' => 'success'));
        }
    }
    }

    /**
     * Function to add new store for venture
     * 
     * @author  Mujaffar Sanadi     23 oct 15
     */

    /**
     * Method to add new ventures associating to vendors
     * 
     * @author Mujaffar S      Created on 26 July 15
     * @param type $id
     */
    function addStore($id = 0) {
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
                $this->partial('venture/add_store', $data);
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

    function storeManage() {

        $postData = $this->input->post();
        $this->load->model(array('store_model'));
        $addData = array('venture_id' => $this->customer['id'], 'name' => $postData['name']);
        $storeId = $this->store_model->create_store($addData);

        $data['customer_id'] = $this->customer['id'];
        $data['field_data'] = serialize($postData);
        $data['coverage_area'] = $postData['coverage_area'];
        $data['latitude'] = $postData['lat'];
        $data['longitude'] = $postData['long'];
        $data['store_id'] = $storeId;
        $data['address_type'] = 'SA';

        $this->Customer_model->save_store_address($data);
    }

    function become_partner() {
        $postData = $this->input->post();

        // Check whether pending actions present for this mapping
        $record = $this->Mapvrpr_model->check_mapping($postData['vendor_id'], $this->customer['id']);

        if (!$record) {
            $passData = array('vendor_id' => $postData['vendor_id'], 'partner_id' => $this->customer['id']);
            $this->Mapvrpr_model->create_mapping($passData);
            echo json_encode(array('status' => 'success'));
        } else {
            if ($record[0]->approved) {
                echo json_encode(array('status' => 'error', 'info' => 'Already registered as partner'));
            } else {
                echo json_encode(array('status' => 'error', 'info' => 'Already registered as partner'));
            }
        }
    }
    
    function insert_venture_deliveryAddress()
    {
		$post = $this->input->post();
		
		if(!empty($post))
		{
			//echo '<pre>';  print_r($post); echo '</pre>';
			$this->load->model('Ventureaddress_model');
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
				
				$insertData['sublocality'] 	= isset($geoResult['sublocality'])?$geoResult['sublocality']:'';
				$insertData['locality'] 	= isset($geoResult['locality'])?$geoResult['locality']:'';
				$insertData['admin_2'] 		= isset($geoResult['admin_2'])?$geoResult['admin_2']:'';
				$insertData['admin_1'] 		= isset($geoResult['admin_1'])?$geoResult['admin_1']:'';
				$insertData['country']		= isset($geoResult['country'])?$geoResult['country']:'';
				$insertData['country_code']	= isset($geoResult['country_code'])?$geoResult['country_code']:'';
				$insertData['postal_code'] 	= isset($geoResult['postal_code'])?$geoResult['postal_code']:'';
				
				$this->Ventureaddress_model->create_delivery_address($insertData);
				
			}
			//redirect('secure/venture_delivery_address');
			
		}	
		$json = array();	
		$json['savedStatus'] = 'Y';
		echo json_encode($json);
	}

}
