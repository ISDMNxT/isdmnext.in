<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends MY_Controller
{
    function index()
    {
        $this->view('index');
    }
    function switch_back(){
        if($this->session->has_userdata('admin_login')){
            $this->session->unset_userdata('main_id');
            $this->session->unset_userdata('temp_login');
            $newData = [
                'admin_id' => $this->session->userdata('main_id'),
                'admin_type' => 'admin'
            ];
            $this->session->set_userdata($newData);
            redirect('admin');
        }
        else{
            redirect('admin');
        }
    }
    function change_password()
    {
        $this->view('change-password', ['isValid' => true]);
    }
    function sign_out()
    {
        $rr = 'admin';
        if ($this->center_model->isEmployer()){
            $rr = 'employer/index';
        }
        $this->session->sess_destroy();
        redirect($rr);
    }
    function profile()
    {

        $row = $this->center_model->get_verified([
            'id' => $this->center_model->loginId()
        ])->row_array() ?? [];

        /*pre($row);
        die;*/
        $row['isValid'] = true;
        $this->view('profile',$row);
    }
    function wallet_history(){
        $c_id = 0;
        if(!empty($_GET['id'])){
            $c_id = $_GET['id'];
        }
        
        $this->view('wallet-history',['isValid' => true, 'c_id' => $c_id]);
    }
    function manage_role_category(){
        $this->view('manage-role-category');
    }
    function test(){
        echo $this->ki_theme->wallet_balance();
    }

}
