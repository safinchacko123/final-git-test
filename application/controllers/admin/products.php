<?php

class Products extends Admin_Controller {

    private $use_inventory = false;
    public $is_venture = false;
    private $nutrional_fact_upload_data;

    function __construct() {
        parent::__construct();

        $this->auth->check_access('Venture', true);

        $this->load->model(array('Product_model', 'Customer_model'));
        $this->load->helper('form');
        $this->lang->load('product');
        $this->customer = $this->mp_cart->customer();

        $CI = & get_instance();
        $admin = $CI->session->userdata('admin');

        $this->load->helper('role_check');
        if (!checkIfRoleValid($admin, 'Venture')) {
            redirect('/');
        }
        if ($this->customer) {
            if ($this->customer['access'] == 'Venture') {
                $this->is_venture = true;
            }
        }
    }

    function index($order_by = "name", $sort_order = "ASC", $code = 0, $page = 0, $rows = 15) {

        $data['page_title'] = lang('products');

        $data['code'] = $code;
        $term = false;
        $category_id = false;

//get the category list for the drop menu
        $data['categories'] = $this->Category_model->get_categories_tiered();
        $data['vendorList'] = $this->Customer_model->get_vendors();

        $post = $this->input->post(null, false);
        $this->load->model('Search_model');
        if ($post) {
            $term = json_encode($post);
            $code = $this->Search_model->record_term($term);
            $data['code'] = $code;
        } elseif ($code) {
            $term = $this->Search_model->get_term($code);
        }

//store the search term
        $data['term'] = $term;
        $data['order_by'] = $order_by;
        $productData = array('created_on', 'show_rating', 'modified_on');
        $customerData = array('company');
        if (in_array($order_by, $productData)) {
            $order_by = 'p.' . $order_by;
        } elseif (in_array($order_by, $customerData)) {
            $order_by = 'c.' . $order_by;
        }

        $data['sort_order'] = $sort_order;

        $passData = array('term' => $term, 'order_by' => $order_by, 'sort_order' => $sort_order,
            'rows' => $rows, 'page' => $page);

        if ($this->is_venture) {
            $passData['ventureId'] = $this->customer['id'];
        }
        $data['products'] = $this->Product_model->products($passData);

        $passData2 = array('term' => $term, 'order_by' => $order_by, 'sort_order' => $sort_order);
        if ($this->is_venture) {
            $passData2['ventureId'] = $this->customer['id'];
        }

        //total number of products
        $data['total'] = $this->Product_model->products($passData2, true);

        $this->load->library('pagination');

        $config['base_url'] = site_url($this->config->item('admin_folder') . '/products/index/' . $order_by . '/' . $sort_order . '/' . $code . '/');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = $rows;
        $config['uri_segment'] = 7;
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

        $data['user_role'] = $this->customer['access'];
        $this->view($this->config->item('admin_folder') . '/products', $data);
    }

//basic category search
    function product_autocomplete() {
        $name = trim($this->input->post('name'));
        $limit = $this->input->post('limit');

        if (empty($name)) {
            echo json_encode(array());
        } else {
            $results = $this->Product_model->product_autocomplete($name, $limit);

            $return = array();

            foreach ($results as $r) {
                $return[$r->id] = $r->name;
            }
            echo json_encode($return);
        }
    }

    function bulk_save() {
        $products = $this->input->post('product');

        if (!$products) {
            $this->session->set_flashdata('error', lang('error_bulk_no_products'));
            redirect($this->config->item('admin_folder') . '/products');
        }

        foreach ($products as $id => $product) {
            $product['id'] = $id;
            $this->Product_model->save($product);
        }

        $this->session->set_flashdata('message', lang('message_bulk_update'));
        redirect($this->config->item('admin_folder') . '/products');
    }

    function maximumCheck($num) {
        if ($num < 1) {
            $this->form_validation->set_message(
                    'maximumCheck', 'The %s field must be greater than zero'
            );
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function form($id = false, $duplicate = false) {
        $this->product_id = $id;
        $this->load->library('form_validation');
        $this->load->model(array('Option_model', 'Category_model', 'Digital_Product_model'));
        $this->lang->load('digital_product');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['categories'] = $this->Category_model->get_categories_tiered();
        $data['file_list'] = $this->Digital_Product_model->get_list();

        $data['page_title'] = lang('product_form');

//default values are empty if the product is new
        $data['id'] = '';
        $data['sku'] = '';
        $data['name'] = '';
        $data['slug'] = '';
        $data['description'] = '';
        $data['excerpt'] = '';
        $data['price'] = '';
        $data['saleprice'] = '';
        $data['weight'] = '';
        $data['track_stock'] = '';
        $data['seo_title'] = '';
        $data['meta'] = '';
        $data['shippable'] = '';
        $data['taxable'] = '';
        $data['fixed_quantity'] = '';
        $data['quantity'] = '';
        $data['enabled'] = '';
        $data['related_products'] = array();
        $data['product_categories'] = array();
        $data['images'] = array();
        $data['product_files'] = array();
        $data['price_type'] = 'fixed';
        //echo $data['price_type'];
        $data['price_type_check'] = $this->input->post('price_option');
        //echo $data['price_type_check'];
        $data['size'] = $this->input->post('size');
        $data['addons'] = $this->input->post('createmainaddons');
        $data['weight_volume_unit'] = $this->input->post('unit');
        $data['subunit'] = $this->input->post('subunit');
        //Edited
        $data['ingredients'] = '';
        $data['directions'] = '';
        $data['nutritional_facts'] = '';
        $data['alt_tag'] = '';
        $data['caption'] = '';
        $data['cuisines'] = $this->Product_model->getCuisines();
        $data['cuisinedatas'] = $this->input->post('cuisine_id');
        
        
        
        
        //$datas['cuisines'] = $this->Customer_model->getCuisines();
//create the photos array for later use
        $data['photos'] = array();

        if ($id) {
// get the existing file associations and create a format we can read from the form to set the checkboxes
            $pr_files = $this->Digital_Product_model->get_associations_by_product($id);
            foreach ($pr_files as $f) {
                $data['product_files'][] = $f->file_id;
            }

// get product & options data  -----  Size and addons too
            $data['product_options'] = $this->Option_model->get_product_options($id);
            $temp_size = $this->db->get_where('products_size', array('product_id' => $id))->result_array();
            foreach ($temp_size as $key => $value) {
                $data['size']['name'][$key] = $value['size_name'];
                $data['size']['price'][$key] = $value['size_price'];
                $data['size']['sale_price'][$key] = $value['size_sale_price'];
                $data['size']['stock'][$key] = $value['size_stock'];
            }
            $temp_addon = $this->db->get_where('products_addons', array('product_id' => $id))->result_array();
            $data['addons'] = (!empty($temp_addon)) ? unserialize($temp_addon[0]['addons']) : array();
            $product = $this->Product_model->get_product($id);

//if the product does not exist, redirect them to the product list with an error
            if (!$product) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/products');
            }

//helps us with the slug generation
            $this->product_name = $this->input->post('slug', $product->slug);

//set values to db values
            $data['id'] = $id;
            $data['sku'] = $product->sku;
            $data['name'] = $product->name;
            $data['seo_title'] = $product->seo_title;
            $data['meta'] = $product->meta;
            $data['slug'] = $product->slug;
            $data['description'] = $product->description;
            $data['excerpt'] = $product->excerpt;
            $data['price'] = $product->price;
            $data['saleprice'] = $product->saleprice;
            $data['weight'] = $product->weight;
            $data['track_stock'] = $product->track_stock;
            $data['shippable'] = $product->shippable;
            $data['quantity'] = $product->quantity;
            $data['taxable'] = $product->taxable;
            $data['fixed_quantity'] = $product->fixed_quantity;
            $data['enabled'] = $product->enabled;
            $data['price_type'] = $product->price_type;
            $data['weight_volume_unit'] = $product->weight_volume_unit;
            $data['subunit'] = $product->subunit;
            //Edited
            $data['ingredients'] = $product->ingredients;
            $data['directions'] = $product->directions;
            $data['cuisinedatas'] = $this->input->post('cuisine_id');
            $productcuisin = $this->Product_model->get_productcuisin($id);

            $productcuisinarray = array();

            foreach ($productcuisin as $productcus) {
                $productcuisinarray[] = $productcus->cuisine_id;
            }

            $data['productcuisines'] = $productcuisinarray;



//make sure we haven't submitted the form yet before we pull in the images/related products from the database
            if (!$this->input->post('submit')) {

                $data['product_categories'] = array();
                foreach ($product->categories as $product_category) {
                    $data['product_categories'][] = $product_category->id;
                }

                $data['related_products'] = $product->related_products;
                $data['images'] = (array) json_decode($product->images);
                $nutrition_images = (array) json_decode($product->images);

                foreach ($nutrition_images as $nutrition_image) {
                    if (isset($nutrition_image->is_nutritional) && $nutrition_image->is_nutritional == 1) {
                        $data['nutrition_image'] = $nutrition_image->filename;
                        $data['alt_tag'] = $nutrition_image->alt;
                        $data['caption'] = $nutrition_image->caption;
                    }
                }
            }
        }

//if $data['related_products'] is not an array, make it one.
        if (!is_array($data['related_products'])) {
            $data['related_products'] = array();
        }
        if (!is_array($data['product_categories'])) {
            $data['product_categories'] = array();
        }


//no error checking on these
        $this->form_validation->set_rules('caption', 'Caption');
        $this->form_validation->set_rules('primary_photo', 'Primary');

        $this->form_validation->set_rules('sku', 'lang:sku', 'trim');
        $this->form_validation->set_rules('seo_title', 'lang:seo_title', 'trim');
        $this->form_validation->set_rules('meta', 'lang:meta_data', 'trim');
        $this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('slug', 'lang:slug', 'trim');
        $this->form_validation->set_rules('description', 'lang:description', 'trim');
        $this->form_validation->set_rules('excerpt', 'lang:excerpt', 'trim');
        //$this->form_validation->set_rules('price', 'lang:price', 'trim|numeric|floatval');
        //$this->form_validation->set_rules('saleprice', 'lang:saleprice', 'trim|numeric|floatval');
        $this->form_validation->set_rules('weight', 'lang:weight_volume', 'trim|numeric|floatval');
        $this->form_validation->set_rules('subunit', 'lang:sub_unit', 'trim|numeric');
        $this->form_validation->set_rules('track_stock', 'lang:track_stock', 'trim|numeric');
        //$this->form_validation->set_rules('quantity', 'lang:quantity', 'trim|numeric');
        //$this->form_validation->set_rules('quantity', 'lang:max_items_allowed', 'trim|is_natural');
        $this->form_validation->set_rules('shippable', 'lang:shippable', 'trim|numeric');
        $this->form_validation->set_rules('taxable', 'lang:taxable', 'trim|numeric');
        $this->form_validation->set_rules('fixed_quantity', 'lang:fixed_quantity', 'trim|numeric');
        $this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
        $this->form_validation->set_rules('images', 'product image', 'required'); //bin
        $this->form_validation->set_rules('nutritional_facts', 'lang:nutritional_facts', 'callback_image_upload');

        $price_option = $this->input->post('price_option'); // This isn't the correct place but it is like this when it was handed over to us grrr!
        if ($price_option == "size") {
            $this->form_validation->set_rules('size[name][]', 'lang:Size_name', 'required|trim');
            $this->form_validation->set_rules('size[price][]', 'lang:price', 'required|trim|numeric|floatval');
            //$this->form_validation->set_rules('size[sale_price][]', 'lang:saleprice', 'trim|numeric|floatval');
            $this->form_validation->set_rules('size[stock][]', 'lang:max_items_allowed', 'trim|is_natural|callback_maximumCheck');
        } elseif ($price_option == "fixed") {
            $this->form_validation->set_rules('price', 'lang:price', 'required|trim|numeric|floatval');
            $this->form_validation->set_rules('saleprice', 'lang:saleprice', 'trim|numeric|floatval');
            $this->form_validation->set_rules('quantity', 'lang:max_items_allowed', 'trim|is_natural|callback_maximumCheck');
        }

        /*
          if we've posted already, get the photo stuff and organize it
          if validation comes back negative, we feed this info back into the system
          if it comes back good, then we send it with the save item

          submit button has a value, so we can see when it's posted
         */

        if ($duplicate) {
            $data['id'] = false;
        }
        if ($this->input->post('submit')) {
//reset the product options that were submitted in the post
            $data['product_options'] = $this->input->post('option');
            $data['related_products'] = $this->input->post('related_products');
            $data['product_categories'] = $this->input->post('categories');
            $data['images'] = $this->input->post('images');
            $data['product_files'] = $this->input->post('downloads');
            $data['weight_volume_unit'] = $this->input->post('unit');
            $data['subunit'] = $this->input->post('subunit');
        }

        $data['user_role'] = $this->customer['access'];
        if ($this->form_validation->run() == FALSE) {
            $this->view($this->config->item('admin_folder') . '/product_form', $data);
        } else {

            $this->load->helper('text');

//first check the slug field
            $slug = $this->input->post('slug');

//if it's empty assign the name field
            if (empty($slug) || $slug == '') {
                $slug = $this->input->post('name');
            }

            $slug = url_title(convert_accented_characters($slug), 'dash', TRUE);

//validate the slug
            $this->load->model('Routes_model');

            if ($id) {
                $slug = $this->Routes_model->validate_slug($slug, $product->route_id);
                $route_id = $product->route_id;
            } else {
                $slug = $this->Routes_model->validate_slug($slug);

                $route['slug'] = $slug;
                $route_id = $this->Routes_model->save($route);
            }

            $save['id'] = $id;
            $save['sku'] = $this->input->post('sku');
            $save['name'] = $this->input->post('name');
            $save['seo_title'] = $this->input->post('seo_title');
            $save['meta'] = $this->input->post('meta');
            $save['description'] = $this->input->post('description');
            $save['excerpt'] = $this->input->post('excerpt');
            $save['weight'] = $this->input->post('weight');
            $save['weight_volume_unit'] = $this->input->post('unit');
            $save['subunit'] = $this->input->post('subunit');
            $save['track_stock'] = $this->input->post('track_stock');
            $save['fixed_quantity'] = $this->input->post('fixed_quantity');
            $save['quantity'] = $this->input->post('quantity');
            $save['shippable'] = $this->input->post('shippable');
            $save['taxable'] = $this->input->post('taxable');
            $save['enabled'] = $this->input->post('enabled');
            $save['price_type'] = $price_option;
            $post_images = $this->input->post('images');

            // Edited
            $save['ingredients'] = $this->input->post('ingredients');
            $save['directions'] = $this->input->post('directions');

            // Edited
            $timestamp = date('Y-m-d H:i:s');

            if ($save['id'] == '') {
                $save['created_on'] = $timestamp;
            }

            $save['modified_on'] = $timestamp;

            $save['slug'] = $slug;
            $save['route_id'] = $route_id;

            if ($primary = $this->input->post('primary_image')) {
                if ($post_images) {
                    foreach ($post_images as $key => &$pi) {
                        if ($primary == $key) {
                            $pi['primary'] = true;
                            continue;
                        }
                    }
                }
            }
            
            //process images
            $nutritional_fact_img = isset($this->nutrional_fact_upload_data['file_name']) ? $this->nutrional_fact_upload_data['file_name'] : false;
                if ($nutritional_fact_img) {
                    $nutritional_fact_exp = explode('.', $nutritional_fact_img);
                    $nutritional_img = array(
                        $nutritional_fact_exp[0] => array(
                            'filename' => $nutritional_fact_img,
                            'alt' => $this->input->post('alt_tag'),
                            'caption' => $this->input->post('caption'),
                            'is_nutritional' => '1'
                        )
                    );
                    if ($save['id'] != '') {
                        $nutrition_product = $this->Product_model->get_product($id);
                        $nutrition_product_images = (array) json_decode($nutrition_product->images);
                        foreach ($nutrition_product_images as $key => $nutrition_product_image) {
                            if ($nutrition_product_image->is_nutritional === '1') {
                                unset($nutrition_product_images[$key]);
                                unlink("uploads/images/full/" . $nutrition_product_image->filename);
                                unlink("uploads/images/medium/" . $nutrition_product_image->filename);
                                unlink("uploads/images/small/" . $nutrition_product_image->filename);
                                unlink("uploads/images/thumbnails/" . $nutrition_product_image->filename);
                            }
                        }
                        $result = array_merge($nutrition_product_images, $nutritional_img);
                    } else {
                        $result = array_merge($post_images, $nutritional_img);
                    }
                    $save['images'] = json_encode($result);
                    //echo $save['images']; exit();
                }else{
                    $save['images'] = json_encode($post_images);
                }
            

            if ($this->input->post('related_products')) {
                $save['related_products'] = json_encode($this->input->post('related_products'));
            } else {
                $save['related_products'] = '';
            }

            // process price according to price type
            if ($price_option == "fixed") {
                $save['price'] = $this->input->post('price');
                $save['saleprice'] = $this->input->post('saleprice');
                $size_info = FALSE;
            } elseif ($price_option == "size") {
                $size_info = $this->input->post('size');

                $save['price'] = $size_info['price'][0];
                $save['saleprice'] = $size_info['sale_price'][0];
            }

            //format addons.. Grr.. HTML is made dirty thats why I have to do this
            $temp = $this->input->post('createmainaddons');
            $addons_info = false;
            if ($temp) {
                $i = 0;
                foreach ($temp as $value) {
                    if ($value['mainaddonsname'] == "") {
                        continue;
                    }
                    if (isset($value['subaddons'])) {
                        foreach ($value['subaddons'] as $sub_value) {
                            if ($sub_value['subaddonsname'] == "") {
                                continue;
                            }
                            $sub_value['subaddonsprice'] = floatval($sub_value['subaddonsprice']);
                            $addons_info[$i]['subaddons'][] = $sub_value;
                        }
                        if (isset($addons_info[$i]['subaddons'])) {
                            $temp_val = intval($value['mainaddoncnt']);
                            $value['mainaddoncnt'] = ($temp_val == 0) ? 1 : $temp_val;
                            $addons_info[$i]['mainaddonsname'] = $value['mainaddonsname'];
                            $addons_info[$i]['mainaddoncnt'] = $value['mainaddoncnt'];
                            $addons_info[$i]['required'] = isset($value['required']) ? $value['required'] : '';
                            $i++;
                        }
                    }
                }
            }

//save categories
            $categories = $this->input->post('categories');
            if (!$categories) {
                $categories = array();
            }


// format options
            $options = array();
            if ($this->input->post('option')) {
                foreach ($this->input->post('option') as $option) {
                    $options[] = $option;
                }
            }

// Set adding user id, Customer_id if customer session present else admin id            
            $authDtl = $this->auth->get_auth();
            if ($authDtl['logedInAs'] == 'Admin') {
                $save['added_by'] = $authDtl['id'];
            } else if ($authDtl['logedInAs'] == 'Customer') {
                $save['added_by_cust'] = $authDtl['id'];
            }
// save product 
            $product_id = $this->Product_model->save($save, $options, $categories, $size_info, $addons_info);

            if ($save['id'] == '') {
               
                if (!empty($data['cuisinedatas'])) {
                    $saveCus['product_id'] = $product_id;
                    foreach ($data['cuisinedatas'] as $cuisinedata) {
                        $saveCus['cuisine_id'] = $cuisinedata;
                        $cuisine_id = $this->Product_model->saveCuisine($saveCus);
                    }
                }
            } elseif (!empty($save['id'])) {
                
                $updateCuisineData = array('id' => $id, 'cuisinedatas' => $data['cuisinedatas']);
                $productcuisine = $this->Product_model->update_productcuisine($updateCuisineData);
            }

// add file associations
// clear existsing
            $this->Digital_Product_model->disassociate(false, $product_id);
// save new
            $downloads = $this->input->post('downloads');
            if (is_array($downloads)) {
                foreach ($downloads as $d) {
                    $this->Digital_Product_model->associate($d, $product_id);
                }
            }

//save the route
            $route['id'] = $route_id;
            $route['slug'] = $slug;
            $route['route'] = 'cart/product/' . $product_id;

            $this->Routes_model->save($route);

            $this->session->set_flashdata('message', lang('message_saved_product'));

//go back to the product list
            redirect($this->config->item('admin_folder') . '/products');
        }
    }

    function product_image_form() {
        $data['file_name'] = false;
        $data['error'] = false;
        $this->load->view($this->config->item('admin_folder') . '/iframe/product_image_uploader', $data);
    }

    function product_image_upload() {
        $data['file_name'] = false;
        $data['error'] = false;

        $config['allowed_types'] = 'gif|jpg|png';
//$config['max_size']	= $this->config->item('size_limit');
        $config['upload_path'] = 'uploads/images/full';
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload()) {
            $upload_data = $this->upload->data();

            $this->load->library('image_lib');
            /*

              I find that ImageMagick is more efficient that GD2 but not everyone has it
              if your server has ImageMagick then you can change out the line

              $config['image_library'] = 'gd2';

              with

              $config['library_path']		= '/usr/bin/convert'; //make sure you use the correct path to ImageMagic
              $config['image_library']	= 'ImageMagick';
             */

//this is the larger image
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'uploads/images/full/' . $upload_data['file_name'];
            $config['new_image'] = 'uploads/images/medium/' . $upload_data['file_name'];
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 600;
            $config['height'] = 500;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();

//small image
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'uploads/images/medium/' . $upload_data['file_name'];
            $config['new_image'] = 'uploads/images/small/' . $upload_data['file_name'];
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 235;
            $config['height'] = 235;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();

//cropped thumbnail
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'uploads/images/small/' . $upload_data['file_name'];
            $config['new_image'] = 'uploads/images/thumbnails/' . $upload_data['file_name'];
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 150;
            $config['height'] = 150;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();

            $data['file_name'] = $upload_data['file_name'];
        }

        if ($this->upload->display_errors() != '') {
            $data['error'] = $this->upload->display_errors();
        }
        $this->load->view($this->config->item('admin_folder') . '/iframe/product_image_uploader', $data);
    }

    function delete($id = false) {
        $admin = $this->session->userdata('admin');
        if ($id) {
            $product = $this->Product_model->get_product($id);
//if the product does not exist, redirect them to the customer list with an error
            if (!$product) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/products');
            } else {
                if($admin['access']=='Admin' || $admin['id']==$product->added_by_cust) {
// remove the slug
                $this->load->model('Routes_model');
                $this->Routes_model->delete($product->route_id);

//if the product is legit, delete them
                $this->Product_model->delete_product($id);

                $this->session->set_flashdata('message', lang('message_deleted_product'));
                redirect($this->config->item('admin_folder') . '/products');
                } else {
                    $this->session->set_flashdata('error', lang('error_no_permission'));
                    redirect($this->config->item('admin_folder') . '/products');
            }
            }
        } else {
//if they do not provide an id send them to the product list page with an error
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect($this->config->item('admin_folder') . '/products');
        }
    }

    function review($productId, $order_by = "date", $sort_order = "ASC", $page = 0, $rows = 15) {
        $data['page_title'] = 'Product Reviews';

        $data['productId'] = $productId;
        $data['order_by'] = $order_by;
        $data['sort_order'] = $sort_order;

        //total number of reviews
        $data['total'] = $this->Product_model->count_reviews($productId);

        $data['reviews'] = $this->Product_model->reviews($productId, array('order_by' => $order_by, 'sort_order' => $sort_order, 'rows' => $rows, 'page' => $page), false);



        $this->load->library('pagination');

        $config['base_url'] = site_url($this->config->item('admin_folder') . '/products/review/' . $productId . '/' . $order_by . '/' . $sort_order . '/');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = $rows;
        $config['uri_segment'] = 7;
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

        $this->view($this->config->item('admin_folder') . '/review', $data);
    }

    function updateReviewStatus() {
        $reviewId = $this->input->post('review_id');
        if ($reviewId) {
            $result = $this->Product_model->updateReviewStatus($reviewId);

            if ($result[0]->is_approved) {
                $statusImg = '<i class="icon-ok"></li>';
            } else {
                $statusImg = '<i class="icon-remove"></li>';
            }

            echo $statusImg;
        } else {
            echo 'false';
        }
    }

    function bulk_delete_review($productId) {

        $reviewIds = $this->input->post('review');

        if ($reviewIds) {
            foreach ($reviewIds as $review) {
                $this->Product_model->delete_review($review);
            }
            $this->session->set_flashdata('message', 'Review(s) has been deleted successsfully');
        }

        //redirect as to change the url
        redirect($this->config->item('admin_folder') . '/products/review/' . $productId);
    }

    function updateRating() {
        $productId = $this->input->post('product_id');

        if ($productId) {
            $result = $this->Product_model->updateRating($productId);

            if ($result[0]->show_rating) {
                $statusImg = '<i class="icon-ok"></li>';
            } else {
                $statusImg = '<i class="icon-remove"></li>';
            }

            echo $statusImg;
        } else {
            echo 'false';
        }
        }

        function image_upload(){
        if ($_FILES['nutritional_facts']['size'] != 0) {
            //Code for Uploading Nutritional Facts Image            
            $config['allowed_types'] = 'gif|jpg|png';
            $config['upload_path'] = 'uploads/images/full';
            $config['encrypt_name'] = true;
            $config['remove_spaces'] = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('nutritional_facts')) {

                $this->nutrional_fact_upload_data = $this->upload->data();
                $this->load->library('image_lib');

                //this is the larger image
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/full/' . $this->nutrional_fact_upload_data['file_name'];
                $config['new_image'] = 'uploads/images/medium/' . $this->nutrional_fact_upload_data['file_name'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 600;
                $config['height'] = 500;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();

                //small image
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/medium/' . $this->nutrional_fact_upload_data['file_name'];
                $config['new_image'] = 'uploads/images/small/' . $this->nutrional_fact_upload_data['file_name'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 235;
                $config['height'] = 235;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();

                //cropped thumbnail
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/small/' . $this->nutrional_fact_upload_data['file_name'];
                $config['new_image'] = 'uploads/images/thumbnails/' . $this->nutrional_fact_upload_data['file_name'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            } else {
                $this->form_validation->set_message('image_upload', str_replace('<p>', '<p>' . lang('nutritional_facts') . ": ", $this->upload->display_errors()));
                return false;
            }
        }
        return TRUE;
    }

}
