<?php

/**
 * Class for Default module action reques handling
 *
 * This class is designed for handling default module actions
 * 
 * @package    Default
 * @author     Mujaffar S added on 11 July 2015
 */
class Customer extends Front_Controller {

    var $customer;

    function __construct() {
        parent::__construct();
        $this->load->model(array('location_model'));
        $this->customer = $this->mp_cart->customer();
    }

    function index() {
        $this->view('index/home');
    }

    /**
     * Home action to handle home page request
     * @author     Mujaffar S added on 11 July 2015
     */
    function geomap() {
        // Get default address of Customer
        $data['addresses'] = $this->Customer_model->get_address_list($this->customer['id']);

        // Generate address to get Lat, Lang values
        $address = '';
        foreach ($data['addresses'] As $rowAdd) {
            if ($rowAdd['id'] == $this->customer['default_billing_address']) {

                if ($rowAdd['field_data']['address1'] != '') {
                    $address = $rowAdd['field_data']['address1'] . ",";
                }
                if ($rowAdd['field_data']['country_code'] != '') {
                    $address .= $rowAdd['field_data']['country_code'] . ",";
                }
                if ($rowAdd['field_data']['country'] != '') {
                    $address .= $rowAdd['field_data']['country'];
                }
                $address = str_replace(" ", "+", $address);
            }
        }

        $latLong = $this->getGeoCounty($address);

        // Update lat long values for address
        if (count($latLong['southwest']) > 0) {
            $data = array('id' => $this->customer['default_billing_address'], 'longitude' => $latLong['southwest']['lng'], 'latitude' => $latLong['southwest']['lat']);
            $this->Customer_model->save_latlong($data);
        }

        $viewData['addresses'] = "[" . json_encode(array('southwest' => $latLong['southwest'])) . "]";
        $this->view('customer/geomap', $viewData);
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

    function orders($sort_by = 'order_number', $sort_order = 'desc', $code = 0, $page = 0, $rows = 15) {

        if ($this->customer['access'] == 'Customer') {
            //if they submitted an export form do the export
            if ($this->input->post('submit') == 'export') {
                $this->load->model('customer_model');
                $this->load->helper('download_helper');
                $post = $this->input->post(null, false);
                $term = (object) $post;

                $data['orders'] = $this->Order_model->get_orders($term);
            }

            $data['orders'] = $this->Order_model->get_orders('', $sort_by, $sort_order, $rows, $page, $this->customer['id'], true);

            $data['term'] = '';
            $data['code'] = '';
            $data['sort_by'] = $sort_by;
            $data['sort_order'] = $sort_order;
            //$data['total'] = $this->Order_model->get_orders_count($term);
            $this->load->library('pagination');
            $this->view('customer/orders', $data);
        } else {
            redirect('secure/my_account');
        }
    }

    function shares() {
        if ($this->customer['access'] == 'Partner') {
            // Get shares for partner w.r.t. associated vendor income
            // Get partner vendors
            $this->load->model(array('Mapvrpr_model'));
            $vendors = $this->Mapvrpr_model->get_partner_vendors($this->customer['id']);

            $this->load->model(array('Mapvrvt_model'));

            $this->load->model(array('order_model'));

            $arrVentures = array();
            // Get total sale by vendor
            foreach ($vendors As $rowVen) {
                // Get all ventures of Vendor
                $vendorVentures = $this->Mapvrvt_model->get_vr_vt($rowVen->id);
                foreach ($vendorVentures As $rowVen) {
                    if ($rowVen->venture_id) {
                        if (!in_array($rowVen->venture_id, $arrVentures))
                            $arrVentures[] = $rowVen->venture_id;
                    }
                }
            }
            $orders = array();
            if ($arrVentures) {
                // Get venture orders by compairing porduct_items table            
                $orders = $this->order_model->get_venture_orders($arrVentures);
            }

            //$orders = $this->order_model->get_vendor_orders();
            $subTotal = 0;
            if (count($orders)) {
                foreach ($orders As $rowOrder) {
                    $subTotal += $rowOrder->total;
                }
            }
            $data = array();
            $data['subTotal'] = $subTotal;
            $data['orders'] = $orders;
            // Convert it to 25%
            $gained = $subTotal / 4;
            $data['gained'] = $gained;
            $this->view('customer/shares', $data);
        } else {
            redirect('secure/my_account');
        }
    }

}