<?php


error_reporting(E_ERROR);

class Cart extends Front_Controller {

    function __construct() {
        parent::__construct();
        $CI = & get_instance();
        if (!in_array(trim($CI->input->server('REDIRECT_QUERY_STRING'), '/'), [ 'terms-customer', 'terms-vendor', 'terms-partner', 'about-us', 'terms', 'privacy-policy', 'return-policy', 'faq'])) {
            // Check user role  Allowed only for Customer and Admin
            $this->customer = $this->mp_cart->customer();
            $admin = $CI->session->userdata('admin');
            
            if(!$this->customer && !$admin){
                redirect('/secure/login');
            }
                
            $this->load->helper('role_check');
            $responce = checkIfRoleValid2($this->customer, $admin, 'Customer');
            if ($responce == 'add_not_present') {
                $this->session->set_flashdata('error', 'Please add your address');
                redirect('/secure/manage_address');
            }

            $this->load->helper('pre_check');
            $this->load->model('address_model');
            $rechable_ventures = check_rechable_ven($this->customer, $this->Customer_model, $this->address_model);
            $arr_vent = array();
            $arrVenDtl = array();
            
            if ($rechable_ventures) {
                foreach ($rechable_ventures As $rowVen) {
                    $arrVenDtl[] = array('name' => $rowVen['firstname'] . " " . $rowVen['lastname'], 'id' => $rowVen['venture_id']);
                    $arr_vent[] = $rowVen['venture_id'];
                }

                $this->session->set_userdata("rechable_ventures", $arr_vent);
                $this->session->set_userdata("rechable_ventures_dtl", $arrVenDtl);
            } else {
                $this->session->set_flashdata('error', "<div>No product found for provided address</div>");
                redirect('secure/manage_address');
            }
        }
    }

    function index() {
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;
        $data['homepage'] = true;

        $this->view('homepage', $data);
    }

    function page($id = false) {
        //if there is no page id provided redirect to the homepage.
        $data['page'] = $this->Page_model->get_page($id);
        if (!$data['page']) {
            show_404();
        }
        $this->load->model('Page_model');
        $data['base_url'] = $this->uri->segment_array();

        $data['fb_like'] = true;

        $data['page_title'] = $data['page']->title;

        $data['meta'] = $data['page']->meta;
        $data['seo_title'] = (!empty($data['page']->seo_title)) ? $data['page']->seo_title : $data['page']->title;

        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $this->view('page', $data);
    }

    function search($code = false, $page = 0, $min_val = '', $max_val = '') {
        $this->load->model('Search_model');

        //check to see if we have a search term
        if (!$code) {
            //if the term is in post, save it to the db and give me a reference
            $term = $this->input->post('term', true);
            $code = $this->Search_model->record_term($term);

            $min_val = 0;
            $max_val = '';

            if ($this->input->post('term2') || $this->input->post('term3')) {
                if ($this->input->post('term2'))
                    $min_val = $this->input->post('term2');
                $max_val = $this->input->post('term3');
            }
            // no code? redirect so we can have the code in place for the sorting.
            // I know this isn't the best way...
            redirect('cart/search/' . $code . '/' . $page . '/' . $min_val . '/' . $max_val);
        } else {
            //if we have the md5 string, get the term
            $term = $this->Search_model->get_term($code);
        }

//        if (empty($term)) {
//            //if there is still no search term throw an error
//            $this->session->set_flashdata('error', lang('search_error'));
//            redirect('cart');
//        }

        $data['page_title'] = lang('search');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        //fix for the category view page.
        $data['base_url'] = array();

        $sort_array = array(
            'name/asc' => array('by' => 'name', 'sort' => 'ASC'),
            'name/desc' => array('by' => 'name', 'sort' => 'DESC'),
            'price/asc' => array('by' => 'sort_price', 'sort' => 'ASC'),
            'price/desc' => array('by' => 'sort_price', 'sort' => 'DESC'),
        );
        $sort_by = array('by' => false, 'sort' => false);

        if (isset($_GET['by'])) {
            if (isset($sort_array[$_GET['by']])) {
                $sort_by = $sort_array[$_GET['by']];
            }
        }

        $data['page_title'] = lang('search');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        //set up pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'cart/search/' . $code . '/';
        $config['uri_segment'] = 4;
        $config['per_page'] = 20;

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

        $venId = '';
        if (isset($_GET['fby'])) {
            $venId = $_GET['fby'];
        }
        
        $result = $this->Product_model->search_products($term, $config['per_page'], $page, $sort_by['by'], $sort_by['sort'], $min_val, $max_val, $venId);

        $config['total_rows'] = $result['count'];
        $this->pagination->initialize($config);

        $data['products'] = $result['products'];
        foreach ($data['products'] as &$p) {
            $p->images = (array) json_decode($p->images);
            $p->options = $this->Option_model->get_product_options($p->id);
        }
        $data['term'] = $term;
        $data['pmin'] = $min_val;
        $data['pmax'] = $max_val;
        
        // Pass rechable venture details to view
        if ($this->session->userdata("rechable_ventures_dtl")) {
            $data['rechable_ventures_dtl'] = $this->session->userdata("rechable_ventures_dtl");
        }
        $this->view('category', $data);
    }

    function category($id) {
        //get the category
        $data['category'] = $this->Category_model->get_category($id);

        if (!$data['category'] || $data['category']->enabled == 0) {
            show_404();
        }

        $product_count = $this->Product_model->count_products($data['category']->id, $this->session->userdata("rechable_ventures"));

        //set up pagination
        $segments = $this->uri->total_segments();
        $base_url = $this->uri->segment_array();

        if ($data['category']->slug == $base_url[count($base_url)]) {
            $page = 0;
            $segments++;
        } else {
            $page = array_splice($base_url, -1, 1);
            $page = $page[0];
        }

        $data['base_url'] = $base_url;
        $base_url = implode('/', $base_url);

        $data['product_columns'] = $this->config->item('product_columns');
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $data['meta'] = $data['category']->meta;
        $data['seo_title'] = (!empty($data['category']->seo_title)) ? $data['category']->seo_title : $data['category']->name;
        $data['page_title'] = $data['category']->name;

        $sort_array = array(
            'name/asc' => array('by' => 'products.name', 'sort' => 'ASC'),
            'name/desc' => array('by' => 'products.name', 'sort' => 'DESC'),
            'price/asc' => array('by' => 'sort_price', 'sort' => 'ASC'),
            'price/desc' => array('by' => 'sort_price', 'sort' => 'DESC'),
        );
        $sort_by = array('by' => 'sequence', 'sort' => 'ASC');

        if (isset($_GET['by'])) {
            if (isset($sort_array[$_GET['by']])) {
                $sort_by = $sort_array[$_GET['by']];
            }
        }

        $venId = '';
        if (isset($_GET['fby'])) {
            $venId = $_GET['fby'];
        }

        //set up pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url($base_url);

        $config['uri_segment'] = $segments;
        $config['per_page'] = 24;
        $config['total_rows'] = $product_count;

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
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

        $data['products'] = $this->Product_model->get_products($data['category']->id, $config['per_page'], $page, $sort_by['by'], $sort_by['sort'], $venId);

        foreach ($data['products'] as &$p) {
            $p->images = (array) json_decode($p->images);
            $p->options = $this->Option_model->get_product_options($p->id);
        }

        // Pass rechable venture details to view
        if ($this->session->userdata("rechable_ventures_dtl")) {
            $data['rechable_ventures_dtl'] = $this->session->userdata("rechable_ventures_dtl");
        }
        $this->view('category', $data);
    }

    function product($id) {

        $this->load->library('form_validation');
        //get the product
        $data['product'] = $this->Product_model->get_product($id);


        if (!$data['product'] || $data['product']->enabled == 0) {
            show_404();
        }
        $authDtl = $this->auth->get_auth();


        $data['customerCount'] = $this->Product_model->getRatingCustomerCount($id);
        $data['rating'] = $this->Product_model->getProductRating($id);

        $data['reviewList'] = $this->Product_model->getApprovedReviews($id);
        $data['wishlistStatus'] = $this->Product_model->getWishlistStatus($id, $authDtl['id']);
        $data['customer_id'] = $authDtl['id'];

        if ($this->input->post('btnSubmit') == 'Submit') {
            $this->form_validation->set_rules('txtreview', 'Review Content', 'trim|required|min_length[5]');

            if ($this->form_validation->run() == false) {
                $this->view('customer/product_review', $data);
            } else {
                $userId = $_SESSION['userdata']['cart_contents']['customer']['id'];
                $user_firstName = $_SESSION['userdata']['cart_contents']['customer']['firstname'];
                $user_LastName = $_SESSION['userdata']['cart_contents']['customer']['lastname'];


                $review['product_id'] = $id;
                $review['user_id'] = $userId;
                $review['review_content'] = $this->input->post('txtreview');
                $review['is_approved'] = 0;
                $review['date'] = strtotime("today");

                $result = $this->Product_model->addReview($review);

                if ($result) {
                    $this->session->set_flashdata('message', 'Review has been succesfully and sent for approval');
                } else {
                    $this->session->set_flashdata('error', 'Review has not been added, please try again later');
                }


                redirect($this->Product_model->get_slug($id));
            }
        }

        $data['base_url'] = $this->uri->segment_array();

        // load the digital language stuff
        $this->lang->load('digital_product');

        $data['options'] = $this->Option_model->get_product_options($data['product']->id);

        $related = $data['product']->related_products;
        $data['related'] = array();



        $data['posted_options'] = $this->session->flashdata('option_values');

        $data['page_title'] = $data['product']->name;
        $data['meta'] = $data['product']->meta;
        $data['seo_title'] = (!empty($data['product']->seo_title)) ? $data['product']->seo_title : $data['product']->name;

        if ($data['product']->images == 'false') {
            $data['product']->images = array();
        } else {
            $data['product']->images = array_values((array) json_decode($data['product']->images));
        }

        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $this->view('product', $data);
    }

    function add_to_cart() {
        // Get our inputs
        $product_id = $this->input->post('id');
        $quantity = $this->input->post('quantity');
        $post_options = $this->input->post('option');

        // Get a cart-ready product array
        $product = $this->Product_model->get_cart_ready_product($product_id, $quantity);

        //if out of stock purchase is disabled, check to make sure there is inventory to support the cart.
        if (!$this->config->item('allow_os_purchase') && (bool) $product['track_stock']) {
            $stock = $this->Product_model->get_product($product_id);

            //loop through the products in the cart and make sure we don't have this in there already. If we do get those quantities as well
            $items = $this->mp_cart->contents();
            $qty_count = $quantity;
            foreach ($items as $item) {
                if (intval($item['id']) == intval($product_id)) {
                    $qty_count = $qty_count + $item['quantity'];
                }
            }

            if ($stock->quantity < $qty_count) {
                //we don't have this much in stock
                $this->session->set_flashdata('error', sprintf(lang('not_enough_stock'), $stock->name, $stock->quantity));
                $this->session->set_flashdata('quantity', $quantity);
                $this->session->set_flashdata('option_values', $post_options);

                redirect($this->Product_model->get_slug($product_id));
            }
        }

        // Validate Options 
        // this returns a status array, with product item array automatically modified and options added
        //  Warning: this method receives the product by reference
        $status = $this->Option_model->validate_product_options($product, $post_options);

        // don't add the product if we are missing required option values
        if (!$status['validated']) {
            $this->session->set_flashdata('quantity', $quantity);
            $this->session->set_flashdata('error', $status['message']);
            $this->session->set_flashdata('option_values', $post_options);

            redirect($this->Product_model->get_slug($product_id));
        } else {

            //Add the original option vars to the array so we can edit it later
            $product['post_options'] = $post_options;

            //is giftcard
            $product['is_gc'] = false;

            // Add the product item to the cart, also updates coupon discounts automatically
            $this->mp_cart->insert($product);

            // go go gadget cart!
            redirect('cart/view_cart');
        }
    }

    function view_cart() {

        $data['page_title'] = 'View Cart';
        $data['gift_cards_enabled'] = $this->gift_cards_enabled;

        $this->view('view_cart', $data);
    }

    function remove_item($key) {
        //drop quantity to 0
        $this->mp_cart->update_cart(array($key => 0));

        redirect('cart/view_cart');
    }

    function update_cart($redirect = false) {
        //if redirect isn't provided in the URL check for it in a form field
        if (!$redirect) {
            $redirect = $this->input->post('redirect');
        }

        // see if we have an update for the cart
        $item_keys = $this->input->post('cartkey');
        $coupon_code = $this->input->post('coupon_code');
        $gc_code = $this->input->post('gc_code');

        if ($coupon_code) {
            $coupon_code = strtolower($coupon_code);
        }

        //get the items in the cart and test their quantities
        $items = $this->mp_cart->contents();
        $new_key_list = array();
        //first find out if we're deleting any products
        foreach ($item_keys as $key => $quantity) {
            if (intval($quantity) === 0) {
                //this item is being removed we can remove it before processing quantities.
                //this will ensure that any items out of order will not throw errors based on the incorrect values of another item in the cart
                $this->mp_cart->update_cart(array($key => $quantity));
            } else {
                //create a new list of relevant items
                $new_key_list[$key] = $quantity;
            }
        }
        $response = array();
        foreach ($new_key_list as $key => $quantity) {
            $product = $this->mp_cart->item($key);
            //if out of stock purchase is disabled, check to make sure there is inventory to support the cart.
            if (!$this->config->item('allow_os_purchase') && (bool) $product['track_stock']) {
                $stock = $this->Product_model->get_product($product['id']);

                //loop through the new quantities and tabluate any products with the same product id
                $qty_count = $quantity;
                foreach ($new_key_list as $item_key => $item_quantity) {
                    if ($key != $item_key) {
                        $item = $this->mp_cart->item($item_key);
                        //look for other instances of the same product (this can occur if they have different options) and tabulate the total quantity
                        if ($item['id'] == $stock->id) {
                            $qty_count = $qty_count + $item_quantity;
                        }
                    }
                }
                if ($stock->quantity < $qty_count) {
                    if (isset($response['error'])) {
                        $response['error'] .= '<p>' . sprintf(lang('not_enough_stock'), $stock->name, $stock->quantity) . '</p>';
                    } else {
                        $response['error'] = '<p>' . sprintf(lang('not_enough_stock'), $stock->name, $stock->quantity) . '</p>';
                    }
                } else {
                    //this one works, we can update it!
                    //don't update the coupons yet
                    $this->mp_cart->update_cart(array($key => $quantity));
                }
            } else {
                $this->mp_cart->update_cart(array($key => $quantity));
            }
        }

        //if we don't have a quantity error, run the update
        if (!isset($response['error'])) {
            //update the coupons and gift card code
            $response = $this->mp_cart->update_cart(false, $coupon_code, $gc_code);
            // set any messages that need to be displayed
        } else {
            $response['error'] = '<p>' . lang('error_updating_cart') . '</p>' . $response['error'];
        }


        //check for errors again, there could have been a new error from the update cart function
        if (isset($response['error'])) {
            $this->session->set_flashdata('error', $response['error']);
        }
        if (isset($response['message'])) {
            $this->session->set_flashdata('message', $response['message']);
        }

        if ($redirect) {
            redirect($redirect);
        } else {
            redirect('cart/view_cart');
        }
    }

    /*     * *********************************************************
      Gift Cards
      - this function handles adding gift cards to the cart
     * ********************************************************* */

    function giftcard() {
        if (!$this->gift_cards_enabled)
            redirect('/');

        // Load giftcard settings
        $gc_settings = $this->Settings_model->get_settings("gift_cards");

        $this->load->library('form_validation');

        $data['allow_custom_amount'] = (bool) $gc_settings['allow_custom_amount'];
        $data['preset_values'] = explode(",", $gc_settings['predefined_card_amounts']);

        if ($data['allow_custom_amount']) {
            $this->form_validation->set_rules('custom_amount', 'lang:custom_amount', 'numeric');
        }

        $this->form_validation->set_rules('amount', 'lang:amount', 'required');
        $this->form_validation->set_rules('preset_amount', 'lang:preset_amount', 'numeric');
        $this->form_validation->set_rules('gc_to_name', 'lang:recipient_name', 'trim|required');
        $this->form_validation->set_rules('gc_to_email', 'lang:recipient_email', 'trim|required|valid_email');
        $this->form_validation->set_rules('gc_from', 'lang:sender_email', 'trim|required');
        $this->form_validation->set_rules('message', 'lang:custom_greeting', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $data['page_title'] = lang('giftcard');
            $data['gift_cards_enabled'] = $this->gift_cards_enabled;
            $this->view('giftcards', $data);
        } else {

            // add to cart

            $card['price'] = set_value(set_value('amount'));

            $card['id'] = -1; // just a placeholder
            $card['sku'] = lang('giftcard');
            $card['base_price'] = $card['price']; // price gets modified by options, show the baseline still...
            $card['name'] = lang('giftcard');
            $card['code'] = generate_code(); // from the string helper
            $card['excerpt'] = sprintf(lang('giftcard_excerpt'), set_value('gc_to_name'));
            $card['weight'] = 0;
            $card['quantity'] = 1;
            $card['shippable'] = false;
            $card['taxable'] = 0;
            $card['fixed_quantity'] = true;
            $card['is_gc'] = true; // !Important
            $card['track_stock'] = false; // !Imporortant

            $card['gc_info'] = array("to_name" => set_value('gc_to_name'),
                "to_email" => set_value('gc_to_email'),
                "from" => set_value('gc_from'),
                "personal_message" => set_value('message')
            );

            // add the card data like a product
            $this->mp_cart->insert($card);

            redirect('cart/view_cart');
        }
    }

    function updateWishlist() {
        $productId = $this->input->post('product_id');
        $customerId = $this->input->post('customer_id');
        $wishlistId = $this->input->post('wishlist_id');

        if ($productId) {
            $result = $this->Product_model->updateWishList($productId, $customerId, $wishlistId);


            if ($result) {
                $statusMsg = '<a href="javascript:void(0);" class="btn btn-primary btn-large" onclick="updateStatus(' . $result . ');">Remove from Favorites</a>';
            } else {
                $statusMsg = '<a href="javascript:void(0);" class="btn btn-primary btn-large"  onclick="updateStatus(0);">Add to Favorites</a>';
            }

            echo $statusMsg;
        } else {
            echo 'false';
        }
    }

    function removeFromWishlist($wishlistId) {
        $result = $this->Product_model->deleteFromWishList($wishlistId);

        if ($result) {
            $this->session->set_flashdata('message', 'Product has been removed sucessfully');
        } else {
            $this->session->set_flashdata('error', 'Product has been not been removed');
        }
        redirect('cart/favorites');
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

    function updateRating() {
        $productId = $this->input->post('product_id');
        $customerId = $this->input->post('customer_id');
        $rating = $this->input->post('rating');

        if ($productId) {
            $isDuplicate = $this->Product_model->checkDuplicateRating($productId, $customerId);

            if ($isDuplicate) {
                echo '1';
            } else {
                $result = $this->Product_model->addProductRating($productId, $customerId, $rating);
                echo '2';
            }
        } else {
            echo '0';
        }
    }

}