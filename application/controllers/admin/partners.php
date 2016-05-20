<?php

class Partners extends Admin_Controller {

    //this is used when editing or adding a vendor
    var $vendor_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Customer_model'));
        $this->load->helper('formatting_helper');
        $this->lang->load('customer');
    }

    function index($field = 'lastname', $by = 'ASC', $page = 0) {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Partners';
        $data['partners'] = $this->Customer_model->get_partners(50, $page, $field, $by);

        $this->load->library('pagination');

        $config['base_url'] = base_url() . '/' . $this->config->item('admin_folder') . '/partners/index/' . $field . '/' . $by . '/';
        $config['total_rows'] = $this->Customer_model->count_partners();
        $config['per_page'] = 50;
        $config['uri_segment'] = 6;
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


        $data['page'] = $page;
        $data['field'] = $field;
        $data['by'] = $by;

        // Get become partner requests
        $this->load->model('Mapvrpr_model');
        $mappings = $this->Mapvrpr_model->getall_mappings();

        if (count($mappings)) {
            $cntr = 0;
            foreach ($mappings As $rowRec) {
                // Get customer details which is of vendor
                $vendorDtl = $this->Customer_model->get_vendor($rowRec->vendor_id);
                $partnerdoc = $this->Mapvrpr_model->getpartner_doc($rowRec->partner_id);

                $mappings[$cntr]->vendor_id = $vendorDtl;
                $mappings[$cntr]->partner_doc = $partnerdoc;
                $cntr++;
            }
        }
        $data['becomepartner'] = $mappings;

        $this->view($this->config->item('admin_folder') . '/partners', $data);
    }

    function export_xml() {
        $data['customers'] = (array) $this->Customer_model->get_customers();

        $this->load->helper('download_helper');
        force_download_content('customers.xml', $this->load->view($this->config->item('admin_folder') . '/customers_xml', $data, true));
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page_title'] = lang('customer_form');

        //default values are empty if the customer is new
        $data['id'] = '';
        $data['group_id'] = '';
        $data['firstname'] = '';
        $data['lastname'] = '';
        $data['email'] = '';
        $data['phone'] = '';
        $data['company'] = '';
        $data['email_subscribe'] = '';
        $data['active'] = false;

        // get group list
        $groups = $this->Customer_model->get_groups();
        foreach ($groups as $group) {
            $group_list[$group->id] = $group->name;
        }
        $data['group_list'] = $group_list;



        if ($id) {
            $this->customer_id = $id;
            $customer = $this->Customer_model->get_customer($id);
            //if the customer does not exist, redirect them to the customer list with an error
            if (!$customer) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/customers');
            }

            //set values to db values
            $data['id'] = $customer->id;
            $data['group_id'] = $customer->group_id;
            $data['firstname'] = $customer->firstname;
            $data['lastname'] = $customer->lastname;
            $data['email'] = $customer->email;
            $data['phone'] = $customer->phone;
            $data['company'] = $customer->company;
            $data['active'] = $customer->active;
            $data['email_subscribe'] = $customer->email_subscribe;
        }

        $this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]|callback_check_email');
        $this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('company', 'lang:company', 'trim|max_length[128]');
        $this->form_validation->set_rules('active', 'lang:active');
        $this->form_validation->set_rules('group_id', 'group_id', 'numeric');
        $this->form_validation->set_rules('email_subscribe', 'email_subscribe', 'numeric|max_length[1]');

        //if this is a new account require a password, or if they have entered either a password or a password confirmation
        if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id) {
            $this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
        }


        if ($this->form_validation->run() == FALSE) {
            $this->view($this->config->item('admin_folder') . '/customer_form', $data);
        } else {
            $save['id'] = $id;
            $save['group_id'] = $this->input->post('group_id');
            $save['firstname'] = $this->input->post('firstname');
            $save['lastname'] = $this->input->post('lastname');
            $save['email'] = $this->input->post('email');
            $save['phone'] = $this->input->post('phone');
            $save['company'] = $this->input->post('company');
            $save['active'] = $this->input->post('active');
            $save['email_subscribe'] = $this->input->post('email_subscribe');


            if ($this->input->post('password') != '' || !$id) {
                $save['password'] = $this->input->post('password');
            }

            $this->Customer_model->save($save);

            $this->session->set_flashdata('message', lang('message_saved_customer'));

            //go back to the customer list
            redirect($this->config->item('admin_folder') . '/customers');
        }
    }

    function addresses($id = false) {
        $data['customer'] = $this->Customer_model->get_customer($id);

        //if the customer does not exist, redirect them to the customer list with an error
        if (!$data['customer']) {
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect($this->config->item('admin_folder') . '/customers');
        }

        $data['addresses'] = $this->Customer_model->get_address_list($id);

        $data['page_title'] = sprintf(lang('addresses_for'), $data['customer']->firstname . ' ' . $data['customer']->lastname);

        $this->view($this->config->item('admin_folder') . '/customer_addresses', $data);
    }

    function delete($id = false) {
        if ($id) {
            $customer = $this->Customer_model->get_customer($id);
            //if the customer does not exist, redirect them to the customer list with an error
            if (!$customer) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/customers');
            } else {
                //if the customer is legit, delete them
                $delete = $this->Customer_model->delete($id);

                $this->session->set_flashdata('message', lang('message_customer_deleted'));
                redirect($this->config->item('admin_folder') . '/customers');
            }
        } else {
            //if they do not provide an id send them to the customer list page with an error
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect($this->config->item('admin_folder') . '/customers');
        }
    }

    //this is a callback to make sure that customers are not sharing an email address
    function check_email($str) {
        $email = $this->Customer_model->check_email($str, $this->customer_id);
        if ($email) {
            $this->form_validation->set_message('check_email', lang('error_email_in_use'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function address_form($customer_id, $id = false) {
        $data['id'] = $id;
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

        $data['customer_id'] = $customer_id;

        $data['page_title'] = lang('address_form');
        //get the countries list for the dropdown
        $data['countries_menu'] = $this->Location_model->get_countries_menu();

        if ($id) {
            $address = $this->Customer_model->get_address($id);

            //fully escape the address
            form_decode($address);

            //merge the array
            $data = array_merge($data, $address);

            $data['zones_menu'] = $this->Location_model->get_zones_menu($data['country_id']);
        } else {
            //if there is no set ID, the get the zones of the first country in the countries menu
            $data['zones_menu'] = $this->Location_model->get_zones_menu(array_shift(array_keys($data['countries_menu'])));
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'lang:company', 'trim|max_length[128]');
        $this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('address1', 'lang:address', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('address2', 'lang:address', 'trim|max_length[128]');
        $this->form_validation->set_rules('city', 'lang:city', 'trim|required');
        $this->form_validation->set_rules('country_id', 'lang:country', 'trim|required');
        $this->form_validation->set_rules('zone_id', 'lang:state', 'trim|required');
        $this->form_validation->set_rules('zip', 'lang:zip', 'trim|required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $this->view($this->config->item('admin_folder') . '/customer_address_form', $data);
        } else {

            $a['customer_id'] = $customer_id; // this is needed for new records
            $a['id'] = (empty($id)) ? '' : $id;
            $a['field_data']['company'] = $this->input->post('company');
            $a['field_data']['firstname'] = $this->input->post('firstname');
            $a['field_data']['lastname'] = $this->input->post('lastname');
            $a['field_data']['email'] = $this->input->post('email');
            $a['field_data']['phone'] = $this->input->post('phone');
            $a['field_data']['address1'] = $this->input->post('address1');
            $a['field_data']['address2'] = $this->input->post('address2');
            $a['field_data']['city'] = $this->input->post('city');
            $a['field_data']['zip'] = $this->input->post('zip');


            $a['field_data']['zone_id'] = $this->input->post('zone_id');
            $a['field_data']['country_id'] = $this->input->post('country_id');

            $country = $this->Location_model->get_country($this->input->post('country_id'));
            $zone = $this->Location_model->get_zone($this->input->post('zone_id'));

            $a['field_data']['zone'] = $zone->code;  // save the state for output formatted addresses
            $a['field_data']['country'] = $country->name; // some shipping libraries require country name
            $a['field_data']['country_code'] = $country->iso_code_2; // some shipping libraries require the code 

            $this->Customer_model->save_address($a);
            $this->session->set_flashdata('message', lang('message_saved_address'));

            redirect($this->config->item('admin_folder') . '/customers/addresses/' . $customer_id);
        }
    }

    function delete_address($customer_id = false, $id = false) {
        if ($id) {
            $address = $this->Customer_model->get_address($id);
            //if the customer does not exist, redirect them to the customer list with an error
            if (!$address) {
                $this->session->set_flashdata('error', lang('error_address_not_found'));

                if ($customer_id) {
                    redirect($this->config->item('admin_folder') . '/customers/addresses/' . $customer_id);
                } else {
                    redirect($this->config->item('admin_folder') . '/customers');
                }
            } else {
                //if the customer is legit, delete them
                $delete = $this->Customer_model->delete_address($id, $customer_id);
                $this->session->set_flashdata('message', lang('message_address_deleted'));

                if ($customer_id) {
                    redirect($this->config->item('admin_folder') . '/customers/addresses/' . $customer_id);
                } else {
                    redirect($this->config->item('admin_folder') . '/customers');
                }
            }
        } else {
            //if they do not provide an id send them to the customer list page with an error
            $this->session->set_flashdata('error', lang('error_address_not_found'));

            if ($customer_id) {
                redirect($this->config->item('admin_folder') . '/customers/addresses/' . $customer_id);
            } else {
                redirect($this->config->item('admin_folder') . '/customers');
            }
        }
    }

    function updatePartnerStatus() {
        $partnerId = $this->input->post('partner_id');

        if ($partnerId) {
            $result = $this->Customer_model->updatePartnerStatus($partnerId);

            if ($result[0]->active) {
                $statusImg = '<i class="icon-ok"></li>';
            } else {
                $statusImg = '<i class="icon-remove"></li>';
            }

            // Check if account is enabled and does not have partner_no 
            // Then create new partner no
            if ($result[0]->active && !$result[0]->unique_id) {
                // Then create unique id for partner
                $this->load->helper('rand_no');
                $this->load->model('Partner_model');
                $unique_id = genUniqueId($this->Partner_model, 8);

                // Add mapping record in Partners table
                $data = array('unique_id' => $unique_id, 'customer_id' => $partnerId);
                $this->Partner_model->setUniqueId($data);
            }
            echo $statusImg;
        } else {
            echo 'false';
        }
    }

    function get_partner_vendors($pid = FALSE) {
        if ($pid) {
            $this->load->model(array('Mapvrpr_model'));
            /* $this->load->library('pagination');

              $config['base_url'] = base_url() . '/' . $this->config->item('admin_folder') . '/partners/get_partner_vendors/' . $pid . '/';
              $config['total_rows'] = $this->Customer_model->count_partners();
              $config['per_page'] = 50;
              $config['uri_segment'] = 5;
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

             */

            $vendors = $this->Mapvrpr_model->get_partner_vendors_by_partners($pid);
            //$this->view($this->config->item('admin_folder') . '/ajax/partner_vendors', $data);
            $result = '<table class="table table-striped partners">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>' . lang('firstname') . '
                                </th>

                                <th>' . lang('lastname') . '
                                </th>

                                <th>' . lang('email') . '
                                </th>
                                <th>' . lang('share_percentage') . '</th>                                
                            </tr>
                        </thead>

                        <tbody>';
            if (empty($vendors)) {
                $result.='<tr><td style="text-align:center;" colspan="5">No Partners Found.</td></tr>';
            } else {
                foreach ($vendors as $key => $value) {
                    $result.='<tr class="share_percentage">
                                    <td>' . ++$key . '</td>
                                    <td class="gc_cell_left">' . $value->firstname . ' </td>
                                    <td>' . $value->lastname . '</td>
                                    <td><a href="mailto:' . $value->email . '">' . $value->email . '</a></td>
                                    <td>
                                    <span class="share-value">' . $value->share_percentage . '</span><input value="' . $value->share_percentage . '" name="share_percentage" class="inline-edit span1" style="display:none"><span class="actions-container"><span class="icon-pencil mouseover-pointer"></span><span class="icon-ok mouseover-pointer" data-id="' . $value->id . '" style="display:none"></span><span class="icon-remove mouseover-pointer" style="display:none"></span></span></td>
                                </tr>';
                }
            }
            $result.='  </tbody>
                    </table>';
            echo $result;
        }
    }

    function update_share() {
        $id = intval($this->input->post('id'));
        $share_percentage = $this->input->post('share');
        if (is_numeric($share_percentage)) {
            if ((int) $share_percentage < 91 && (int) $share_percentage > 0) {
                $this->load->model(array('Mapvrpr_model'));
                $update_share = $this->Mapvrpr_model->update_share($id, $share_percentage);
                if ($update_share == 'SUCCESS') {
                    echo 'success';
                } else {
                    echo 'Please try after some time';
                }
            } else {
                echo "Please enter a valid percentage";
            }
        } else {
            echo 'Please enter a vaild number';
        }
    }

}
