<?php
class Ajax extends Ajax_Controller
{

    function generate_link()
    {
        $allLinks = $this->ki_theme->project_config('open_links');
        /*echo "<pre>";
        print_r($allLinks);
        die;*/
        if (isset($allLinks[$this->post('type')])) {
            $this->response('link', base_url($allLinks[$this->post('type')] . '/' . $this->encode($this->post('id'))));
            $this->response('status', true);
        }
        $this->response($this->post());
    }
    function deleted()
    {
        $this->response(
            'status',
            $this->db->where($this->post('field'), $this->post('field_value'))->update($this->post('table_name'), [
                'isDeleted' => 1
            ])
        );
    }
    function undeleted()
    {
        $this->response(
            'status',
            $this->db->where($this->post('field'), $this->post('field_value'))->update($this->post('table_name'), [
                'isDeleted' => 0
            ])
        );
    }
    function admin_login()
    {
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));

        $table = $this->db->where('email', $email)->get('centers');
        if ($table->num_rows()) {

            $row = $table->row();
            if (($row->status && $row->type == 'center') or ($row->status && $row->type == 'employer') or $row->type == 'admin') {
                if ($row->password == $password) {
                    $this->load->library('session');
                    $this->session->set_userdata([
                        'admin_login' => true,
                        'admin_type' => $row->type,
                        'admin_id' => $row->id
                    ]);
                    $this->response('status', 1);
                } else
                    $this->response('error', alert('Sorry, the email or password is incorrect, please try again.', 'danger'));
            } else if($row->status && $row->type == 'staff') {
                if ($row->password == $password) {
                    $this->load->library('session');
                    $this->session->set_userdata([
                        'admin_login' => true,
                        'admin_type' => 'center',
                        'admin_id' => $row->parent_center_id,
                        'staff_id' => $row->id,
                        'role' => $row->role,
                        'permission' => json_decode($row->permission,true)
                    ]);
                    $this->response('status', 1);
                } else
                    $this->response('error', alert('Sorry, the email or password is incorrect, please try again.', 'danger'));

            } else if($row->status && $row->type == 'master_franchise') {
                if ($row->password == $password) {
                    $this->load->library('session');
                    $this->session->set_userdata([
                        'admin_login' => true,
                        'admin_type' => $row->type,
                        'admin_id' => $row->id,
                        'permission' => json_decode($row->permission,true)
                    ]);
                    $this->response('status', 1);
                } else
                    $this->response('error', alert('Sorry, the email or password is incorrect, please try again.', 'danger'));

            } else {
                $this->response('error', alert('Your Account is In-active. Please Contact Your Admin', 'danger'));
            }
        }
        else
            $this->response('error',alert('Sorry, this email  is not found..','danger'));
    }
    function delete_enquiry($id)
    {
        $this->response('status', $this->db->where('id', $id)->delete('contact_us_action'));
    }
    function upload_file()
    {
        if ($this->file_up('image'))
            $this->response('status', true);
    }

    function centre_wallet_load()
    {
        $post = $this->post();
        $this->db->where('type','master_franchise');
        $this->db->where('isPending',0);
        $this->db->where('isDeleted',0);
        $this->db->where('status',1);
        $master                 = $this->db->get('centers')->result_array();
        $master_franchise_id    = 0;
        $earning_percent        = 0;
        $pre_wallet             = 0;
        $wallet_id              = 0;
        foreach($master as $key => $value){
            $permission                 = json_decode($value['permission'],true);
            if(in_array($post['centre_id'], $permission)){
                $master_franchise_id    = $value['id'];
                $earning_percent        = $value['earning_percent'];
                $pre_wallet             = $value['wallet'];
            }
        }

        $closing_balance                = ($post['amount'] + $post['closing_balance']);
        $sdata = [
            'center_id' => $post['centre_id'],
            'amount' => $post['amount'],
            'o_balance' => $post['closing_balance'],
            'c_balance' => $closing_balance,
            'type' => 'wallet_load',
            'description' => $post['description'],
            'added_by' => 'admin',
            'status' => 1,
            'wallet_status' => 'credit'
        ];
        $get     = $this->db->where($sdata)->get('wallet_transcations')->num_rows();

        if($get == 0){
            $data = [
                'center_id' => $post['centre_id'],
                'amount' => $post['amount'],
                'o_balance' => $post['closing_balance'],
                'c_balance' => $closing_balance,
                'type' => 'wallet_load',
                'description' => $post['description'],
                'added_by' => 'admin',
                'order_id' => strtolower(generateCouponCode(12)),
                'status' => 1,
                'wallet_status' => 'credit'
            ];
            $this->db->insert('wallet_transcations', $data);
            $wallet_id = $this->db->insert_id();
            $this->center_model->update_wallet($post['centre_id'], $closing_balance);

            if($master_franchise_id && $wallet_id){
                $percentValue                       = round((intval($post['amount']) * intval($earning_percent)) / 100);
                $masterData                         = [];
                $masterData['wallet_id']            = $wallet_id;
                $masterData['master_franchise_id']  = $master_franchise_id;
                $masterData['center_id']            = $post['centre_id'];
                $masterData['total_amount']         = $post['amount'];
                $masterData['amount']               = $percentValue;
                $masterData['type']                 = 'wallet_load';
                $masterData['title']                = "Wallet Load";
                if(!empty($post['description']))
                $masterData['description']          = $post['description'];
                $masterData['wallet_status']        = 'credit';

                $this->db->insert('master_wallet', $masterData);

                $t_wallet = intval($pre_wallet) + intval($percentValue);
                $this->center_model->update_wallet($master_franchise_id, $t_wallet);
            }
            $this->response('status', true);
        } else {
            $this->response('status', false);
        }
    }

    function remove_transaction(){
        $this->db->where('id', $this->post('id'))->delete('wallet_transcations');
        $all                        = $this->db->where('center_id', $this->post('center_id'))->get('wallet_transcations')->result_array();
        $o_balance                  = 0;
        $c_balance                  = 0;
        foreach($all as $key => $val){
            $data                       = [];
            if($key == 0 && $val['wallet_status'] == 'debit'){
                $o_balance              = $val['o_balance'];
                $c_balance              = intval($o_balance) - intval($val['amount']);
            } else if($key == 0 && $val['wallet_status'] == 'credit'){
                $o_balance              = $val['o_balance'];
                $c_balance              = intval($o_balance) + intval($val['amount']);
            } else if($key > 0 && $val['wallet_status'] == 'debit'){
                $o_balance              = $c_balance;
                $c_balance              = intval($o_balance) - intval($val['amount']);
            } else if($key > 0 && $val['wallet_status'] == 'credit'){
                $o_balance              = $c_balance;
                $c_balance              = intval($o_balance) + intval($val['amount']);
            }
            $data['o_balance']      = $o_balance;
            $data['c_balance']      = $c_balance;

            $this->db->where('id', $val['id'])->update('wallet_transcations', $data);
        }
        $this->center_model->update_wallet($this->post('center_id'), $c_balance);
        $this->response('status', true);
    }

    function edit_transaction(){
        $get     = $this->db->where('id', $this->post('id'))->get('wallet_transcations')->result_array();
        $this->response('status', true);
        $this->response('url', 'ajax/wallet-update');
        $this->set_data($get[0]);
        $this->response('form', $this->template('update-wallet'));
    }

    function wallet_update(){
        $status = true;
        $errors = [];
        if(empty($this->post('amount')) || (!empty($this->post('amount')) && intval($this->post('amount')) < 100)){
            $status = false;
            $errors[] = 'Please Enter amount minimum ₹100!';
        }

        if(empty($this->post('description'))){
            $status = false;
            $errors[] = 'Please Enter Description!';
        }

        if($status){
            $perticular = $this->db->where('id', $this->post('id'))->get('wallet_transcations')->result_array();
            $wallet     = $this->db->where('wallet_id', $this->post('id'))->get('master_wallet')->result_array();

            $this->db->where('type','master_franchise');
            $this->db->where('isPending',0);
            $this->db->where('isDeleted',0);
            $this->db->where('status',1);
            $master                 = $this->db->get('centers')->result_array();
            $master_franchise_id    = 0;
            $earning_percent        = 0;
            $pre_wallet             = 0;
            foreach($master as $key => $value){
                $permission         = json_decode($value['permission'],true);
                if(in_array($perticular[0]['center_id'], $permission)){
                    $master_franchise_id    = $value['id'];
                    $earning_percent        = $value['earning_percent'];
                    $pre_wallet             = intval($value['wallet']) - intval($wallet[0]['amount']);
                }
            }


            if($perticular[0]['amount'] != $this->post('amount') || $perticular[0]['description'] != $this->post('description')){
                $all = $this->db->where('center_id', $perticular[0]['center_id'])->where('id >=', $this->post('id'))->get('wallet_transcations')->result_array();

                $o_balance                  = 0;
                $amount                     = $this->post('amount');
                $c_balance                  = 0;
                foreach($all as $key => $val){
                    $data                       = [];
                    if($key == 0 && $val['wallet_status'] == 'debit'){
                        $o_balance              = $val['o_balance'];
                        $c_balance              = intval($o_balance) - intval($amount);
                        $data['amount']         = $amount;
                        $data['description']    = $this->post('description');
                    } else if($key == 0 && $val['wallet_status'] == 'credit'){
                        $o_balance              = $val['o_balance'];
                        $c_balance              = intval($o_balance) + intval($amount);
                        $data['amount']         = $amount;
                        $data['description']    = $this->post('description');
                    } else if($key > 0 && $val['wallet_status'] == 'debit'){
                        $o_balance              = $c_balance;
                        $c_balance              = intval($o_balance) - intval($val['amount']);
                    } else if($key > 0 && $val['wallet_status'] == 'credit'){
                        $o_balance              = $c_balance;
                        $c_balance              = intval($o_balance) + intval($val['amount']);
                    }
                    $data['o_balance']      = $o_balance;
                    $data['c_balance']      = $c_balance;

                    $this->db->where('id', $val['id'])->update('wallet_transcations', $data);
                }

                if($master_franchise_id && !empty($wallet[0]['id'])){
                    $percentNew                         = round((intval($this->post('amount')) * intval($earning_percent)) / 100);
                    $masterData                         = [];
                    $masterData['total_amount']         = $this->post('amount');
                    $masterData['amount']               = $percentNew;
                    if(!empty($this->post('description')))
                    $masterData['description']          = $this->post('description');
                    $this->db->where('id', $wallet[0]['id'])->update('master_wallet', $masterData);
                    $t_wallet = intval($pre_wallet) + intval($percentNew);
                    $this->center_model->update_wallet($master_franchise_id, $t_wallet);
                }
                $this->center_model->update_wallet($perticular[0]['center_id'], $c_balance);
            }
        }

        $this->response('status', $status);
        $this->response('errors', $errors);
    }

    function add_payout_request(){
        $post                               = $this->post();
        $masterData                         = [];
        $masterData['master_franchise_id']  = $post['master_franchise_id'];
        $masterData['center_id']            = 1;
        $masterData['total_amount']         = $post['amount'];
        $masterData['amount']               = $post['amount'];
        $masterData['type']                 = $post['type'];
        $masterData['title']                = $post['title'];
        if(!empty($post['description']))
        $masterData['description']          = $post['description'];
        $masterData['wallet_status']        = 'debit';
        $masterData['status']               = 3;

        $this->db->insert('master_wallet', $masterData);
        $this->response('status', true);
        //$t_wallet = intval($pre_wallet) + intval($percentValue);
        //$this->center_model->update_wallet($master_franchise_id, $t_wallet);
    }

    function payout_request_act(){
        $post = $this->post();
        if($post['status'] == 'R'){
            $this->db->update('master_wallet', ['status' => 2], ['id' => $post['id']]);
            $this->response('status', true);
        }

        if($post['status'] == 'A'){
            $data                   = $this->db->from('master_wallet')->where('id', $post['id'])->get()->result_array();
            $master_franchise_id    = $data[0]['master_franchise_id'];
            $master                 = $this->db->where('id',$data[0]['master_franchise_id'])->where('type','master_franchise')->get('centers')->result_array();
            if($master[0]['id']){
                $wallet                 = $master[0]['wallet'];
                $this->db->update('master_wallet', ['status' => 1], ['id' => $post['id']]);

                $t_wallet = intval($wallet) - intval($data[0]['amount']);
                $this->center_model->update_wallet($master_franchise_id, $t_wallet);
                $this->response('status', true);
            } else {
                $this->response('status', false);
            }
        }
    }

    function master_wallet_load(){
        $post                               = $this->post();
        $master                             = $this->db->where('id',$post['centre_id'])->get('centers')->result_array();
        $masterData                         = [];
        $masterData['master_franchise_id']  = $post['centre_id'];
        $masterData['center_id']            = 1;
        $masterData['total_amount']         = $post['amount'];
        $masterData['amount']               = $post['amount'];
        $masterData['type']                 = 'other_income';
        $masterData['title']                = $post['title'];
        if(!empty($post['description']))
        $masterData['description']          = $post['description'];
        $masterData['wallet_status']        = 'credit';

        $this->db->insert('master_wallet', $masterData);
        $t_wallet = intval($master[0]['wallet']) + intval($post['amount']);
        $this->center_model->update_wallet($post['centre_id'], $t_wallet);
        $this->response('status', true);
    }
}