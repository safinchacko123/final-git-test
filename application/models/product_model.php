<?php

Class Product_model extends CI_Model {

    function product_autocomplete($name, $limit) {
        return $this->db->like('name', $name)->get('products', $limit)->result();
    }

    function products($data = array(), $return_count = false) {
        $this->db->select('p.*, c.company, cur.currency_symbol', false);
        $this->db->from('products AS p');
        $this->db->join('customers AS c', 'p.added_by_cust = c.id');

        $this->db->join('map_vr_vt AS m', 'm.venture_id = c.id');

        $this->db->join('customers AS c1', 'm.vendor_id = c1.id');
        //$this->db->join('currencies AS cur', 'c1.business_currency_id = cur.id', 'left');
        $this->db->join('currencies AS cur', 'c.country = cur.country', 'left');
        //$this->db->where('role_id', 3);

        if (empty($data)) {
            //if nothing is provided return the whole shabang
            $this->get_all_products();
        } else {
            //grab the limit
            if (!empty($data['rows'])) {
                $this->db->limit($data['rows']);
            }

            //grab the offset
            if (!empty($data['page'])) {
                $this->db->offset($data['page']);
            }

            //do we order by something other than category_id?
            if (!empty($data['order_by'])) {
                //if we have an order_by then we must have a direction otherwise KABOOM
                $this->db->order_by($data['order_by'], $data['sort_order']);
            }

            //do we have a search submitted?
            if (!empty($data['term'])) {
                $search = json_decode($data['term']);
                //if we are searching dig through some basic fields
                if (!empty($search->term)) {
                    $this->db->like('name', $search->term);
                    $this->db->or_like('description', $search->term);
                    $this->db->or_like('excerpt', $search->term);
                    $this->db->or_like('sku', $search->term);
                }

                if (!empty($search->category_id)) {
                    //lets do some joins to get the proper category products
                    $this->db->join('category_products', 'category_products.product_id = p.id', 'right');
                    $this->db->where('category_products.category_id', $search->category_id);
                    $this->db->order_by('sequence', 'ASC');
                }
            }

            // If $ventureId then add in where clause
            if (isset($data['ventureId'])) {
                //$this->db->select('venture_id', false);
                //$this->db->from('map_vr_vt');
                //$this->db->where('vendor_id', 'p.added_by_cust = c.id');


                $this->db->where('p.added_by_cust', $data['ventureId']);
            }

            if ($return_count) {
                return $this->db->count_all_results();
            } else {
                $result = $this->db->get()->result();
                // echo $this->db->last_query();
                return $result;
            }
        }
    }

    function get_all_products() {
        //sort by alphabetically by default

        $this->db->select('p.*, c.company', false);
        $this->db->from('products AS p');
        $this->db->join('customers AS c');
        $this->db->order_by('name', 'ASC');
        $result = $this->db->get();

        return $result->result();
    }

    function get_filtered_products($product_ids, $limit = false, $offset = false) {

        if (count($product_ids) == 0) {
            return array();
        }

        $this->db->select('id, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)->from('products');

        if (count($product_ids) > 1) {
            $querystr = '';
            foreach ($product_ids as $id) {
                $querystr .= 'id=\'' . $id . '\' OR ';
            }

            $querystr = substr($querystr, 0, -3);

            $this->db->where($querystr, null, false);
        } else {
            $this->db->where('id', $product_ids[0]);
        }

        $result = $this->db->limit($limit)->offset($offset)->get()->result();

        //die($this->db->last_query());

        $contents = array();
        $count = 0;
        foreach ($result as $product) {

            $contents[$count] = $this->get_product($product->id);
            $count++;
        }

        return $contents;
    }

    function get_products($category_id = false, $limit = false, $offset = false, $by = false, $sort = false, $venId = false) {
        //if we are provided a category_id, then get products according to category
        if ($category_id) {
            $this->db->select('category_products.*, products.*, customers.firstname, customers.lastname, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)
                    ->from('category_products')->join('products', 'category_products.product_id=products.id')
                    ->where(array('category_id' => $category_id, 'enabled' => 1));

            if (count($this->session->userdata("rechable_ventures"))) {
                $this->db->join('customers', 'products.added_by_cust = customers.id');
                $this->db->where_in('customers.id', $this->session->userdata("rechable_ventures"));
            } else {
                $this->db->join('customers', 'products.added_by_cust = customers.id');
            }

            if ($venId) {
                $this->db->where(array('products.added_by_cust' => $venId));
            }

            $this->db->order_by($by, $sort);

            $result = $this->db->limit($limit)->offset($offset)->get()->result();
            return $result;
        } else {
            //sort by alphabetically by default
            $this->db->order_by('name', 'ASC');
            $result = $this->db->get('products');

            return $result->result();
        }
    }

    function count_all_products() {
        return $this->db->count_all_results('products');
    }

    function count_products($id, $rechable_ventures = null) {

        $select = $this->db->select('product_id')
                ->from('category_products')
                ->join('products', 'category_products.product_id = products.id')
                ->where(array('category_id' => $id, 'enabled' => 1));
        if ($rechable_ventures) {
            $select->join('customers', 'products.added_by_cust = customers.id');
            $select->where_in('customers.id', $rechable_ventures);
        }

        return $select->count_all_results();
    }

    function get_product($id, $related = true) {
        $result = $this->db->get_where('products', array('id' => $id))->row();
        if (!$result) {
            return false;
        }

        $related = json_decode($result->related_products);

        if (!empty($related)) {
            //build the where
            $where = array();
            foreach ($related as $r) {
                $where[] = '`id` = ' . $r;
            }

            $this->db->where('(' . implode(' OR ', $where) . ')', null);
            $this->db->where('enabled', 1);

            $result->related_products = $this->db->get('products')->result();
        } else {
            $result->related_products = array();
        }
        $result->categories = $this->get_product_categories($result->id);

        return $result;
    }

    function get_product_categories($id) {
        return $this->db->where('product_id', $id)->join('categories', 'category_id = categories.id')->get('category_products')->result();
    }

    function get_slug($id) {
        return $this->db->get_where('products', array('id' => $id))->row()->slug;
    }

    function check_slug($str, $id = false) {
        $this->db->select('slug');
        $this->db->from('products');
        $this->db->where('slug', $str);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function save($product, $options = false, $categories = false, $size_info = FALSE, $addons_info = FALSE) {
        if ($product['id']) {
            $this->db->where('id', $product['id']);
            $this->db->update('products', $product);

            $id = $product['id'];
        } else {
            $this->db->insert('products', $product);
            $id = $this->db->insert_id();
        }

        //loop through the product options and add them to the db
        if ($options !== false) {
            $obj = & get_instance();
            $obj->load->model('Option_model');

            // wipe the slate
            $obj->Option_model->clear_options($id);

            // save edited values
            $count = 1;
            foreach ($options as $option) {
                $values = $option['values'];
                unset($option['values']);
                $option['product_id'] = $id;
                $option['sequence'] = $count;

                $obj->Option_model->save_option($option, $values);
                $count++;
            }
        }

        if ($categories !== false) {
            if ($product['id']) {
                //get all the categories that the product is in
                $cats = $this->get_product_categories($id);

                //generate cat_id array
                $ids = array();
                foreach ($cats as $c) {
                    $ids[] = $c->id;
                }

                //eliminate categories that products are no longer in
                foreach ($ids as $c) {
                    if (!in_array($c, $categories)) {
                        $this->db->delete('category_products', array('product_id' => $id, 'category_id' => $c));
                    }
                }

                //add products to new categories
                foreach ($categories as $c) {
                    if (!in_array($c, $ids)) {
                        $this->db->insert('category_products', array('product_id' => $id, 'category_id' => $c));
                    }
                }
            } else {
                //new product add them all
                foreach ($categories as $c) {
                    $this->db->insert('category_products', array('product_id' => $id, 'category_id' => $c));
                }
            }
        }

        //process product sizes
        if ($size_info !== FALSE) {
            $available_sizes = array();
            $this->db->delete('products_size', array('product_id' => $id));
            /*$temp_size = $this->db->get_where('products_size', array('product_id' => $id))->result_array();
            foreach ($temp_size as $value) {
                $available_sizes[] = $value['size_name'];
            }*/
            for ($i = 0; $i < count($size_info['name']); $i++) {
                /*if (in_array($size_info['name'][$i], $available_sizes)) {
                    continue;
                }*/
                $data = array(
                    'product_id' => $id,
                    'size_name' => $size_info['name'][$i],
                    'size_price' => $size_info['price'][$i],
                    'size_sale_price' => isset($size_info['sale_price'][$i]) ? $size_info['sale_price'][$i] : '',
                    'size_stock' => $size_info['stock'][$i]
                );
                $this->db->insert('products_size', $data);
            }
        } else {
            $this->db->delete('products_size', array('product_id' => $id));
        }

        if ($addons_info !== FALSE) {
            $this->db->where('product_id', $id);
            $q = $this->db->get('products_addons');

            if ($q->num_rows() > 0) {
                $this->db->where('product_id', $id);
                $this->db->update('products_addons', array('addons' => serialize($addons_info)));
            } else {
                $this->db->insert('products_addons', array(
                    'product_id' => $id,
                    'addons' => serialize($addons_info))
                );
            }
        } else {
            $this->db->delete('products_addons', array('product_id' => $id));
        }

        //return the product id
        return $id;
    }

    function delete_product($id) {
        // delete product 
        $this->db->where('id', $id);
        $this->db->delete('products');

        //delete references in the product to category table
        $this->db->where('product_id', $id);
        $this->db->delete('category_products');

        // delete coupon reference
        $this->db->where('product_id', $id);
        $this->db->delete('coupons_products');
    }

    function add_product_to_category($product_id, $optionlist_id, $sequence) {
        $this->db->insert('product_categories', array('product_id' => $product_id, 'category_id' => $category_id, 'sequence' => $sequence));
    }

    function search_products($term, $limit = false, $offset = false, $by = false, $sort = false, $min_val = false, $max_val = false, $ven_id = false) {
        $results = array();

        $this->db->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
        //this one counts the total number for our pagination
        $this->db->where('enabled', 1);
        $this->db->where('(name LIKE "%' . $term . '%" OR description LIKE "%' . $term . '%" OR excerpt LIKE "%' . $term . '%" OR sku LIKE "%' . $term . '%")');
        $results['count'] = $this->db->count_all_results('products');


        $this->db->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
        //this one gets just the ones we need.
        $this->db->where('enabled', 1);
        $this->db->where('(name LIKE "%' . $term . '%" OR description LIKE "%' . $term . '%" OR excerpt LIKE "%' . $term . '%" OR sku LIKE "%' . $term . '%")');

        if ($min_val && !$max_val) {
            $this->db->where("saleprice >= '" . $min_val . "'");
        } else if (!$min_val && $max_val) {
            $this->db->where("saleprice <= '" . $max_val . "'");
        } else if ($min_val && $max_val) {
            $this->db->where("saleprice between $min_val and $max_val");
        }

        if ($ven_id) {
            $this->db->where(array('added_by_cust' => $ven_id));
        }

        if ($by && $sort) {
            $this->db->order_by($by, $sort);
        }

        $results['products'] = $this->db->get('products', $limit, $offset)->result();

        return $results;
    }

    // Build a cart-ready product array
    function get_cart_ready_product($id, $quantity = false) {
        $product = $this->db->get_where('products', array('id' => $id))->row();

        //unset some of the additional fields we don't need to keep
        if (!$product) {
            return false;
        }

        $product->base_price = $product->price;

        if ($product->saleprice != 0.00) {
            $product->price = $product->saleprice;
        }


        // Some products have n/a quantity, such as downloadables
        //overwrite quantity of the product with quantity requested
        if (!$quantity || $quantity <= 0 || $product->fixed_quantity == 1) {
            $product->quantity = 1;
        } else {
            $product->quantity = $quantity;
        }


        // attach list of associated downloadables
        $product->file_list = $this->Digital_Product_model->get_associations_by_product($id);

        return (array) $product;
    }

    function reviews($productId, $data = array(), $return_count = false) {

        if (empty($data)) {
            //if nothing is provided return the whole shabang
            $this->get_all_reviews($productId);
        } else {
            $this->db->select('pr.*, c.id, CONCAT_WS(" ", c.firstname, c.lastname) AS fullname', false);
            $this->db->from('product_reviews AS pr');
            $this->db->where('product_id', $productId);
            $this->db->join('customers AS c', 'c.id = pr.user_id');

            //grab the limit
            if (!empty($data['rows'])) {
                $this->db->limit($data['rows']);
            }

            //grab the offset
            if (!empty($data['page'])) {
                $this->db->offset($data['page']);
            }

            //do we order by something other than category_id?
            if (!empty($data['order_by'])) {
                //if we have an order_by then we must have a direction otherwise KABOOM
                $this->db->order_by($data['order_by'], $data['sort_order']);
            }
            //$res = $this->db->get('product_reviews')->result();
            //echo $this->db->last_query();
            return $this->db->get()->result();

            //echo $this->db->last_query(); exit;
            if ($return_count) {
                return $this->count_reviews($productId);
            } else {

                //$res = $this->db->get('product_reviews')->result();
                //echo $this->db->last_query();
                return $this->db->get('product_reviews')->result();
            }
        }
    }

    function get_all_reviews($productId) {
        //sort by alphabetically by default
        $this->db->order_by('review_id', 'DESC');

        $result = $this->db->select('pr.*, c.id, CONCAT_WS(" ", c.firstname, c.lastname) AS fullname', false)
                ->from('product_reviews AS pr')
                ->join('customers AS c', 'c.id = pr.user_id')
                ->where('product_id = ' . $productId)
                ->get();

        return $result->result();
    }

    function count_reviews($productId) {
        $cnt = $this->db->select('review_id')->from('product_reviews AS pr')->join('customers AS c', 'c.id = pr.user_id')->where('product_id', $productId)->count_all_results();
        $this->db->last_query();
        return $cnt;
    }

    function updateReviewStatus($reviewId) {
        $this->db->set('is_approved', 'CASE WHEN is_approved = 0 THEN 1 ELSE 0 END', FALSE);
        $this->db->where('review_id', $reviewId);
        $this->db->update('product_reviews');
        $result = $this->db->select('is_approved', false)->from('product_reviews')->where('review_id', $reviewId)->get();

        return $result->result();
    }

    function getApprovedReviews($productId) {
        //sort by alphabetically by default
        $this->db->order_by('review_id', 'DESC');
        $result = $this->db->select('pr.*, c.id, CONCAT_WS(" ", c.firstname, c.lastname) AS fullname', false)
                ->from('product_reviews AS pr')
                ->join('customers AS c', 'c.id = pr.user_id')
                ->where('product_id = ' . $productId)
                ->where('is_approved = 1')
                ->get();

        return $result->result();
    }

    function updateRating($productId) {
        $this->db->set('show_rating', 'CASE WHEN show_rating = 0 THEN 1 ELSE 0 END', FALSE);
        $this->db->where('id', $productId);
        $this->db->update('products');

        $result = $this->db->select('show_rating', false)->from('products')->where('id', $productId)->get();


        return $result->result();
    }

    function delete_review($reviewId) {
        // delete product 
        $this->db->where('review_id', $reviewId);
        $this->db->delete('product_reviews');
    }

    public function addReview($review) {
        $this->db->insert('product_reviews', $review);
        return $this->db->insert_id();
    }

    /**
     * Method to get provided product name related matching products
     * @param string $productName
     */
    public function getReleventProducts($productName) {
        $strProductName = '';

        foreach ($productName As $rowPro) {
            if ($strProductName != '') {
                $strProductName .= " OR name like ";
            }
            $strProductName .= "'%$rowPro%'";
        }
        //sort by alphabetically by default
        $this->db->select('id, images', false)
                ->from('products')
                ->where("name like $strProductName");
        return $this->db->get()->result();
    }

    public function getTotalWishlist($customerId) {
        $this->db->select('COUNT(wishlist_id)', false)
                ->from('customer_wishlist')
                ->where("customer_id", $customerId);
        return $this->db->get()->result();
    }

    public function getWishlist($customerId, $data = array()) {

        $this->db->select('w.wishlist_id, w.customer_id, p.id, p.name, p.images, p.seo_title', false);
        $this->db->from('customer_wishlist AS w');
        $this->db->join('products AS p', 'p.id = w.product_id');
        $this->db->where('customer_id', $customerId);

        //grab the limit
        if (!empty($data['rows'])) {
            $this->db->limit($data['rows']);
        }

        //grab the offset
        if (!empty($data['page'])) {
            $this->db->offset($data['page']);
        }

        //do we order by something other than category_id?
        if (!empty($data['order_by'])) {
            //if we have an order_by then we must have a direction otherwise KABOOM
            $this->db->order_by($data['order_by'], $data['sort_order']);
        }
        //$res = $this->db->get('product_reviews')->result();

        return $this->db->get()->result();
    }

    public function getWishlistStatus($productId, $customerId) {
        $this->db->select('wishlist_id', false);
        $this->db->from('customer_wishlist');
        $this->db->where('customer_id', $customerId);
        $this->db->where('product_id', $productId);

        $result = $this->db->get()->result();

        if ($result) {
            return $result[0]->wishlist_id;
        } else {
            return 0;
        }
    }

    public function updateWishList($productId, $customerId, $wishlistId = 0) {
        if ($wishlistId == 0) {
            $this->db->insert('customer_wishlist', array('customer_id' => $customerId, 'product_id' => $productId));
            return $this->db->insert_id();
        } else {
            $result = $this->deleteFromWishList($wishlistId);
            return 0;
        }

        return $result->result();
    }

    public function deleteFromWishList($wishlistId) {
        // delete product 
        $this->db->where('wishlist_id', $wishlistId);
        $this->db->delete('customer_wishlist');
        return true;
    }

    public function getRatingCustomerCount($productId) {
        $this->db->select('COUNT(rating_id) AS cnt', false)
                ->from('product_rating')
                ->where("product_id", $productId);

        $result = $this->db->get()->row()->cnt;

        return $result;
    }

    public function getProductRating($productId) {
        $this->db->select('ROUND(AVG(rating), 1) AS average', false)
                ->from('product_rating')
                ->where("product_id", $productId);

        return $this->db->get()->row()->average;
    }

    public function checkDuplicateRating($productId, $customerId) {
        $this->db->select('rating_id', false);
        $this->db->from('product_rating');
        $this->db->where("product_id", $productId);
        $this->db->where("user_id", $customerId);

        $result = $this->db->get()->row()->rating_id;
        //echo $this->db->last_query(); exit;
        return $result;
    }

    public function addProductRating($productId, $customerId, $rating) {
        $this->db->insert('product_rating', array('user_id' => $customerId, 'product_id' => $productId, 'rating' => $rating));
        return $this->db->insert_id();
    }

    //Edited 23/03/2016
    function getCuisines() {
        $result = $this->db->get('cuisine');
        return $result->result();
}

    function saveCuisine($saveCus) {
        $this->db->insert('product_cuisine_map', $saveCus);
        return $this->db->insert_id();
    }

    function get_productcuisin($productId) {
        $this->db->select('cuisine_id');
        $this->db->from('product_cuisine_map');
        $this->db->where('product_id', $productId);
        $result = $this->db->get()->result();
        return $result;
    }

    function update_productcuisine($updateCuisineData) {
        $this->db->delete('product_cuisine_map', array('product_id' => $updateCuisineData['id']));
        foreach ($updateCuisineData['cuisinedatas'] as $cuisinedata) {
            $data = array(
                'product_id' => $updateCuisineData['id'],
                'cuisine_id' => $cuisinedata
            );
            $this->db->insert('product_cuisine_map', $data);
        }
        return $this->db->insert_id();
    }

}
