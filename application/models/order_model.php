<?php
Class order_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_gross_monthly_sales($year, $vendorId = 0) {
        //$this->db->select('SUM(coupon_discount) as coupon_discounts');
        //$this->db->select('SUM(gift_card_discount) as gift_card_discounts');
        $this->db->select('SUM(subtotal) as product_totals');
        $this->db->select('SUM(shipping) as shipping');
        $this->db->select('SUM(tax) as tax');
        $this->db->select('SUM(total) as total');
        $this->db->select('YEAR(ordered_on) as year');
        $this->db->select('MONTH(ordered_on) as month');
        //$this->db->select('currencies.currency_symbol as currency');
        $this->db->group_by(array('MONTH(ordered_on)'));
        $this->db->order_by("ordered_on", "desc");
        $this->db->where('YEAR(ordered_on)', $year);
        $this->db->from('orders');

        if ($vendorId > 0) {
            //$this->db->join('order_items', 'order_items.order_id = orders.id');//redesign
            $this->db->join('customers', 'orders.venture_id = customers.id');//$this->db->join('customers', 'order_items.venture_id = customers.id');//redesign
            $this->db->where('customers.id IN (SELECT venture_id FROM gc_map_vr_vt WHERE vendor_id = ' . $vendorId . ')');
        }

        //$this->db->join('currencies', 'customers.country = currencies.country', 'left');
        return $this->db->get()->result();
    }

    function get_sales_years() {
        $this->db->order_by("ordered_on", "desc");
        $this->db->select('YEAR(ordered_on) as year');
        $this->db->group_by('YEAR(ordered_on)');
        $records = $this->db->get('orders')->result();
        $years = array();
        foreach ($records as $r) {
            $years[] = $r->year;
        }
		if(count($years) == 0)
		{
			$years = array(date('Y'));
		}

        return $years;
    }

    function get_orders($search = false, $sort_by = '', $sort_order = 'DESC', $limit = 0, $offset = 0, $customer_id = 0, $group = false) {


        $this->db->select('o.*, c.id AS ventureId, c.company, mvp.share_percentage', false);
        $this->db->from('orders AS o ', false);
        //$this->db->join('order_items AS oi', 'oi.order_id = o.id');//redesign
        $this->db->join('customers AS c', 'o.venture_id = c.id'); //$this->db->join('customers AS c', 'oi.venture_id = c.id');//redesign
        $this->db->join('map_vr_vt AS mvt', 'mvt.venture_id = c.id');
        $this->db->join('map_vr_pr AS mvp', 'mvt.vendor_id = mvp.vendor_id', 'left');

        if ($this->customer['access'] == 'Partner') {
            $this->db->where("mvp.partner_id", $this->customer['id']);
        }



        if ($this->customer['id'] != '' && $this->customer['access'] == 'Venture') {
            $this->db->where('c.id', $this->customer['id']);
        }

        if ($search) {
            if (!empty($search->vendorDropdown)) {
                $this->db->where('c.id IN (SELECT venture_id FROM gc_map_vr_vt WHERE vendor_id = ' . $search->vendorDropdown . ')');
            }

            if (!empty($search->term)) {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach ($term as $t) {
                    $not = '';
                    $operator = 'OR';
                    if (substr($t, 0, 1) == '-') {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t, 1, strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `bill_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `bill_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `status` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `notes` " . $not . "LIKE '%" . $t . "%' )";

                    $this->db->where($like);
                }
            }

            if ($customer_id) {
                $this->db->where('customer_id', $customer_id);
            }

            if (!empty($search->start_date)) {
                $this->db->where('ordered_on >=', $search->start_date);
            }
            if (!empty($search->end_date)) {
                //increase by 1 day to make this include the final day
                //I tried <= but it did not function. Any ideas why?
                $search->end_date = date('Y-m-d', strtotime($search->end_date) + 86400);
                $this->db->where('ordered_on <', $search->end_date);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($sort_by)) {
            if (($sort_by == "order_number") && ($sort_order == 'desc')) {
                $this->db->order_by('(`status`="order_disputed")', 'DESC');
            }
            $this->db->order_by($sort_by, $sort_order);
        }
        if ($group) {
            $this->db->group_by('o.id');
        }

        $result = $this->db->get()->result();
        //$this->db->last_query(); 
        return $result;
    }

    function get_orders_count($search = false) {
        $this->db->select('o.*, c.id AS ventureId, c.company', false);
        $this->db->from('orders AS o ', false);
        //$this->db->join('order_items AS oi', 'oi.order_id = o.id');//redesign
        $this->db->join('customers AS c', 'o.venture_id = c.id'); //$this->db->join('customers AS c', 'oi.venture_id = c.id');//redesign
        $this->db->join('map_vr_vt AS mvt', 'mvt.venture_id = c.id');

        if ($this->customer['access'] == 'Partner') {
            $this->db->join('map_vr_pr AS mvp', 'mvt.vendor_id = mvp.vendor_id');
        } else {
            $this->db->join('map_vr_pr AS mvp', 'mvt.vendor_id = mvp.vendor_id', 'left');
        }

        if ($this->customer['id'] != '' && $this->customer['access'] == 'Venture') {
            $this->db->where('c.id', $this->customer['id']);
        }

        if ($search) {
            if (!empty($search->vendorDropdown)) {
                $this->db->where('c.id IN (SELECT venture_id FROM gc_map_vr_vt WHERE vendor_id = ' . $search->vendorDropdown . ')');
            }

            if (!empty($search->term)) {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach ($term as $t) {
                    $not = '';
                    $operator = 'OR';
                    if (substr($t, 0, 1) == '-') {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t, 1, strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `bill_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `bill_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `status` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `notes` " . $not . "LIKE '%" . $t . "%' )";

                    $this->db->where($like);
                }
            }
            if (!empty($search->start_date)) {
                $this->db->where('ordered_on >=', $search->start_date);
            }
            if (!empty($search->end_date)) {
                $this->db->where('ordered_on <', $search->end_date);
            }
        }

        return $this->db->count_all_results();
    }

    //get an individual customers orders
    function get_customer_orders($id, $offset = 0) {
        $this->db->join('order_items', 'orders.id = order_items.order_id');
        $this->db->order_by('ordered_on', 'DESC');
        return $this->db->get_where('orders', array('customer_id' => $id), 15, $offset)->result();
    }

    function count_customer_orders($id) {
        $this->db->where(array('customer_id' => $id));
        return $this->db->count_all_results('orders');
    }

    function get_order($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('orders');

        $order = $result->row();
        $order->contents = $this->get_items($order->id);

        return $order;
    }

    function get_items($id) {
        $this->db->select('order_id, contents, venture_id');
        $this->db->where('order_id', $id);
        $result = $this->db->get('order_items');

        $items = $result->result_array();

        $return = array();
        $count = 0;
        foreach ($items as $item) {

            $item_content = unserialize($item['contents']);

            //remove contents from the item array
            unset($item['contents']);
            $return[$count] = $item;

            //merge the unserialized contents with the item array
            $return[$count] = array_merge($return[$count], $item_content);

            $count++;
        }
        return $return;
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('orders');

        //now delete the order items
        $this->db->where('order_id', $id);
        $this->db->delete('order_items');
    }

    function save_order($data, $contents = false) {//redesign 
        if ($contents && !empty($contents[0]['id'])) {        
            // Get details about product added by
            $this->load->model('product_model');
            $productDtl = $this->product_model->get_product($contents[0]['id']);
            $data['venture_id'] = $productDtl->added_by_cust; //part of redesign 
        }
        if (isset($data['id'])) {
            if ($data['status'] == 'order_delivered') {
                $data['shipped_on'] = date("Y-m-d H:i:s");
            }
            $this->db->where('id', $data['id']);
            $this->db->update('orders', $data);
            $id = $data['id'];

            // we don't need the actual order number for an update
            $order_number = $id;
        } else {
            $this->db->insert('orders', $data);
            $id = $this->db->insert_id();

            //create a unique order number
            //unix time stamp + unique id of the order just submitted.
            $order = array('order_number' => date('U') . $id);

            //update the order with this order id
            $this->db->where('id', $id);
            $this->db->update('orders', $order);

            //return the order id we generated
            $order_number = $order['order_number'];
        }

        //if there are items being submitted with this order add them now
        if ($contents) {
            // clear existing order items
            $this->db->where('order_id', $id)->delete('order_items');
            // update order items
            foreach ($contents as $item) {
                $save = array();
                $save['contents'] = $item;

                $item = unserialize($item);
                $save['product_id'] = $item['id'];
                $save['quantity'] = $item['quantity'];
                $save['order_id'] = $id;
                
                // Get details about product added by
                //$this->load->model('product_model');
                //$productDtl = $this->product_model->get_product($contents[0]['id']);
                            
                //$save['venture_id'] = $productDtl->added_by_cust;//redesign-
                
                $this->db->insert('order_items', $save);
            }
        }

        return $order_number;
    }

    function get_best_sellers($start, $end) {
        if (!empty($start)) {
            $this->db->where('ordered_on >=', $start);
        }
        if (!empty($end)) {
            $this->db->where('ordered_on <', $end);
        }

        // just fetch a list of order id's
        $orders = $this->db->select('id')->get('orders')->result();

        $items = array();
        foreach ($orders as $order) {
            // get a list of product id's and quantities for each
            $order_items = $this->db->select('product_id, quantity')->where('order_id', $order->id)->get('order_items')->result_array();

            foreach ($order_items as $i) {

                if (isset($items[$i['product_id']])) {
                    $items[$i['product_id']] += $i['quantity'];
                } else {
                    $items[$i['product_id']] = $i['quantity'];
                }
            }
        }
        arsort($items);

        // don't need this anymore
        unset($orders);

        $return = array();
        foreach ($items as $key => $quantity) {
            $product = $this->db->where('id', $key)->get('products')->row();
            if ($product) {
                $product->quantity_sold = $quantity;
            } else {
                $product = (object) array('sku' => 'Deleted', 'name' => 'Deleted', 'quantity_sold' => $quantity);
            }

            $return[] = $product;
        }

        return $return;
    }

    /**
     * Method to get shares for partner w.r.t. associated vendor product sale
     * 
     * @author Mujaffar S   6 Sep 2015
     * @param int $partner_id
     */
    function get_shares($partner_id){
        // First of all get Partner related vendors
        
    }
    
    //get an individual customers orders
    function get_vendor_orders($vendorId = 0) {
        $this->db->join('order_items', 'orders.id = order_items.order_id');
        $this->db->order_by('ordered_on', 'DESC');
        if($vendorId){
            //$this->db->where('order_id', $id);
        }
        return $this->db->get('orders')->result();
    }

    function get_venture_orders($arrVentures){
        $this->db->join('order_items', 'orders.id = order_items.order_id');
        $this->db->where_in('orders.venture_id', $arrVentures);//$this->db->where_in('order_items.venture_id', $arrVentures);//redesign
        $this->db->group_by('order_items.order_id');
        
        return $this->db->get('orders')->result();
    }
    
    function get_uninvoiced_orders($invoice_date) {
        
        $invoicedate     = date("Y-m-$invoice_date");
        $previous_date   = date("Y-m-$invoice_date", strtotime("-1 months"));
        $current_date    = date('Y-m-d', strtotime('-1 day', strtotime($invoicedate)));
        
        $this->db->select('o.*, c.id AS ventureId, c.company, c.firstname as venturefirstname, c.email as ventureemail');
        $this->db->from('orders AS o ');
        //$this->db->join('order_items AS oi', 'oi.order_id = o.id');
        $this->db->join('customers AS c', 'o.venture_id = c.id');
        
        $this->db->where('shipped_on <=',$current_date." 24:59:59");
        $this->db->where('shipped_on >=',$previous_date." 00:00:00");
        
        $this->db->order_by("o.venture_id", "asc");
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    function get_uninvoiced_orders_group_by($invoice_date) {
        
        $invoicedate     = date("Y-m-$invoice_date");
        $previous_date   = date("Y-m-$invoice_date", strtotime("-1 months"));
        $current_date    = date('Y-m-d', strtotime('-1 day', strtotime($invoicedate)));
        
        $this->db->select('c.id AS ventureId');
        $this->db->from('orders AS o ');
        //$this->db->join('order_items AS oi', 'oi.order_id = o.id');
        $this->db->join('customers AS c', 'o.venture_id = c.id');
        
        $this->db->where('shipped_on <=',$current_date." 24:59:59");
        $this->db->where('shipped_on >=',$previous_date." 00:00:00");
        
        $this->db->group_by('o.venture_id');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    
/*          functions by me(Bin). future of these functions are uncertain    
    function get_orders_with_vendors($search = false, $sort_by = '', $sort_order = 'DESC', $limit = 0, $offset = 0, $customer_id = 0, $group = false) {// copy of function "get_orders" with vendors taken care in the SQL itself.. (Stupid idea that the previous developer did this using PHP. Grrr)
        $this->db->select('o.*, c.id AS ventureId, c.company, cvendor.company as vendorName', false);
        $this->db->from('orders AS o ', false);
        $this->db->join('order_items AS oi', 'oi.order_id = o.id');
        $this->db->join('customers AS c', 'oi.venture_id = c.id');//redesign
        $this->db->join('map_vr_vt AS mvrvt', 'mvrvt.venture_id = oi.venture_id');
        $this->db->join('customers AS cvendor', 'mvrvt.vendor_id = cvendor.id');

        if ($search) {
            if (!empty($search->vendorDropdown)) {
                $this->db->where('c.id IN (SELECT venture_id FROM gc_map_vr_vt WHERE vendor_id = ' . $search->vendorDropdown . ')');
            }

            if (!empty($search->term)) {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach ($term as $t) {
                    $not = '';
                    $operator = 'OR';
                    if (substr($t, 0, 1) == '-') {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t, 1, strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `bill_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `bill_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `status` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `notes` " . $not . "LIKE '%" . $t . "%' )";

                    $this->db->where($like);
                }
            }
            
            if($customer_id){
                $this->db->where('customer_id', $customer_id);
            }
            
            if (!empty($search->start_date)) {
                $this->db->where('ordered_on >=', $search->start_date);
            }
            if (!empty($search->end_date)) {
                //increase by 1 day to make this include the final day
                //I tried <= but it did not function. Any ideas why?
                $search->end_date = date('Y-m-d', strtotime($search->end_date) + 86400);
                $this->db->where('ordered_on <', $search->end_date);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($sort_by)) {
            $this->db->order_by($sort_by, $sort_order);
        }
        if($group){
            $this->db->group_by('o.id');
        }

        $result = $this->db->get()->result();
        //$this->db->last_query(); 
        return $result;
    }
    
    
    function get_orders_with_vendors_adminview($search = false, $sort_by = '', $sort_order = 'DESC', $limit = 0, $offset = 0, $customer_id = 0, $group = false) {// copy of function "get_orders" with vendors taken care in the SQL itself.. (Stupid idea that the previous developer did this using PHP. Grrr)
        $this->db->select('o.*, c.id AS ventureId, c.company, cvendor.company as vendorName, mvrpr.share_percentage', false);
        $this->db->from('orders AS o ', false);
        $this->db->join('order_items AS oi', 'oi.order_id = o.id');
        $this->db->join('customers AS c', 'oi.venture_id = c.id');;//redesign
        $this->db->join('map_vr_vt AS mvrvt', 'mvrvt.venture_id = oi.venture_id');
        $this->db->join('customers AS cvendor', 'mvrvt.vendor_id = cvendor.id');
        $this->db->join('map_vr_pr AS mvrpr', 'mvrpr.vendor_id = cvendor.id', 'left');

        if ($search) {
            if (!empty($search->vendorDropdown)) {
                $this->db->where('c.id IN (SELECT venture_id FROM gc_map_vr_vt WHERE vendor_id = ' . $search->vendorDropdown . ')');
            }

            if (!empty($search->term)) {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach ($term as $t) {
                    $not = '';
                    $operator = 'OR';
                    if (substr($t, 0, 1) == '-') {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t, 1, strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `bill_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `bill_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_firstname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `ship_lastname` " . $not . "LIKE '%" . $t . "%'  ";
                    $like .= $operator . " `status` " . $not . "LIKE '%" . $t . "%' ";
                    $like .= $operator . " `notes` " . $not . "LIKE '%" . $t . "%' )";

                    $this->db->where($like);
                }
            }
            
            if($customer_id){
                $this->db->where('customer_id', $customer_id);
            }
            
            if (!empty($search->start_date)) {
                $this->db->where('ordered_on >=', $search->start_date);
            }
            if (!empty($search->end_date)) {
                //increase by 1 day to make this include the final day
                //I tried <= but it did not function. Any ideas why?
                $search->end_date = date('Y-m-d', strtotime($search->end_date) + 86400);
                $this->db->where('ordered_on <', $search->end_date);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($sort_by)) {
            $this->db->order_by($sort_by, $sort_order);
        }
        if($group){
            $this->db->group_by('o.id');
        }

        $result = $this->db->get()->result();
        //$this->db->last_query(); 
        return $result;
    }
    
 * 
 */
    /* By Lynn 12 May Start */
    function get_revenue_forAdmim($persentage = 5,$adminDetail = array())
	{
		//~ $this->db->select('gc_orders.id AS order_id');
		//~ $this->db->select('gc_orders.venture_id');
		//~ $this->db->select('gc_orders.status');
		//~ $this->db->select('gc_orders.subtotal');
		//~ $this->db->select('gc_map_vr_vt.vendor_id AS vendor_id');
		//~ $this->db->select('gc_map_vr_pr.partner_id');
		//~ $this->db->select('gc_map_vr_pr.share_percentage');
		//echo "<pre>"; print_r($adminDetail); echo "</pre>";
		$this->db->select('sum(gc_orders.subtotal) as sum_sub_total');
		$this->db->select('sum(gc_orders.subtotal * (5 /100) ) AS admin_ren');
		$this->db->select('sum((gc_orders.subtotal * (5 /100) ) * (gc_map_vr_pr.share_percentage /100) ) AS partner_ren');
		$this->db->select('sum(gc_orders.subtotal * (5 /100) ) - sum((gc_orders.subtotal * (5 /100) ) * (gc_map_vr_pr.share_percentage /100) )  AS rest_ren');
		$this->db->from('gc_orders');	
		$this->db->join('gc_map_vr_vt', 'gc_map_vr_vt.venture_id = gc_orders.venture_id','left');		
		$this->db->join('gc_map_vr_pr', 'gc_map_vr_pr.vendor_id = gc_map_vr_vt.vendor_id','left');	
		
		if($adminDetail['access'] == 'Partner')	
		{
			$this->db->where('gc_map_vr_pr.partner_id',$adminDetail['id']);	    
		}
		if($adminDetail['access'] == 'Venture')	
		{
			$this->db->where('gc_orders.venture_id',$adminDetail['id']);	    
		}			
		$this->db->where('gc_orders.status','order_delivered');	    
		$query = $this->db->get();
		$result = $query->row();
		return $result;
			
	}
	
	function get_partner_releasedPayment($partner_id)
	{
		$this->db->select('sum(released_payment) as sumcoulmn ');
		$this->db->where('partner_id',$partner_id);			    
		$query = $this->db->get('revenue_record');
		return $query->row()->sumcoulmn;            
	}
    /* By Lynn 12 May End */
    public function get_order_status($id = "") {
        $this->db->select("status");
        $this->db->from("orders");
        $this->db->where("id", $id);
        $data = $this->db->get()->result_array();
        if ($data) {
            return $data[0]['status'];
        } else {
            return "Invalid";
        }
    }
}