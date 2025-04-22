<?php
class Employer extends MY_Controller
{
    function index(){
        
        if(empty($this->center_model->loginId())){
            $this->ki_theme->set_title('Login', true);
            $this->view('index');
        } else {
            $emp_details    = $this->db->select('package_id')->from('emp_packages_trans')->where('status', '1')->where('employer_id', $this->center_model->loginId())->get()->result_array();
            if(!empty($emp_details[0]['package_id'])){
                $this->ki_theme->set_title('Dashboard', true);
                $this->view('index');
            } else {
                redirect('employer/packages');
            }
        }
    }

    function list_employer(){
        $this->ki_theme->set_title('List Employer(s)', true);
        $this->view('list_employer');
    }

    function employer_mgmt(){
        $this->ki_theme->set_title('Employer Mgmt', true);
        $this->view('employer_mgmt');
    }

    function list_jobs(){
        if($this->center_model->isEmployer()){
            $emp_details    = $this->db->select('package_id')->from('emp_packages_trans')->where('status', '1')->where('employer_id', $this->center_model->loginId())->get()->result_array();
            if(!empty($emp_details[0]['package_id'])){
                $this->ki_theme->set_title('List Job(s)', true);
                $this->view('list_jobs');
            } else {
                redirect('employer/packages');
            }
        } else {
            $this->ki_theme->set_title('List Job(s)', true);
            $this->view('list_jobs');
        }
    }

    function job_mgmt(){
        if($this->center_model->isEmployer()){
            $emp_details    = $this->db->select('package_id')->from('emp_packages_trans')->where('status', '1')->where('employer_id', $this->center_model->loginId())->get()->result_array();
            if(!empty($emp_details[0]['package_id'])){
                $data = [];
                if(!empty($_GET['id'])){
                    $get = $this->db->select('*')->from('jobs')->where('id', $_GET['id'])->get()->result_array();
                    if(!empty($get[0]['id'])){
                        $data = $get[0];
                    }
                }
                $type = '';
                if(!empty($_GET['type']) && $_GET['type'] == 'V'){
                    $type = 'V';
                }
                
                $this->ki_theme->set_title('Job Mgmt', true);
                $this->set_data('form', $data);
                $this->set_data('type', $type);
                $this->view('job_mgmt');
            } else {
                redirect('employer/packages');
            }
        } else {
            $data = [];
            if(!empty($_GET['id'])){
                $get = $this->db->select('*')->from('jobs')->where('id', $_GET['id'])->get()->result_array();
                if(!empty($get[0]['id'])){
                    $data = $get[0];
                }
            }
            $type = '';
            if(!empty($_GET['type']) && $_GET['type'] == 'V'){
                $type = 'V';
            }
            
            $this->ki_theme->set_title('Job Mgmt', true);
            $this->set_data('form', $data);
            $this->set_data('type', $type);
            $this->view('job_mgmt');
        }
    }

    function manage_group(){
        $this->ki_theme->set_title('Manage Group', true);
        $this->view('manage_group');
    }

    function manage_industry(){
        $this->ki_theme->set_title('Manage Industry', true);
        $this->view('manage_industry');
    }

    function mapping_group_industry(){
        $this->ki_theme->set_title('Mapping Group Industry', true);
        $this->view('mapping_group_industry');
    }

    function manage_department(){
        $this->ki_theme->set_title('Manage Department', true);
        $this->view('manage_department');
    }

    function job_role(){
        $this->ki_theme->set_title('Job Role', true);
        $this->view('job_role');
    }

    function mapping_department_role(){
        $this->ki_theme->set_title('Mapping Department Role', true);
        $this->view('mapping_department_role');
    }

    function job_skill(){
        $this->ki_theme->set_title('Job Skill', true);
        $this->view('job_skill');
    }

    function mapping_role_skill(){
        $this->ki_theme->set_title('Mapping Role Skill', true);
        $this->view('mapping_role_skill');
    }

    function employer_profile(){
        if ($this->center_model->isAdmin() OR $this->center_model->isMaster())
        {
            $this->access_method();
        
            $employer_id        = $this->uri->segment(3, 0);
            $employer_id        = $employer_id ? base64_decode($employer_id) : $employer_id;
            $tab                = $this->uri->segment(4, 'overview');
            $tabs = [
                'overview' => [
                    'title' => 'Overview',
                    'icon' => array('eye', 3),
                    'url' => 'overview'
                ],
                'documents' => [
                    'title' => 'Documents',
                    'icon' => array('file', 5),
                    'url' => 'documents'
                ],
                'change-password' => [
                    'title' => 'Change Password',
                    'icon' => array('key', 4),
                    'url' => 'change-password'
                ]
            ];
           
            if (isset($tabs[$tab])) {
                $this->ki_theme->set_breadcrumb($tabs[$tab]);
                $center = $this->center_model->get_emp($employer_id);
                $this->set_data('ttl_job_post', 0);
                $this->set_data('ttl_hired', 0);
                $this->set_data($center->row_array());
                $this->set_data('center_details', $center->row_array());
                $center_id = base64_encode($employer_id);
                $this->view('employer_profile', ['tabs' => $tabs, 'tab' => $tab, 'current_link' => base_url('employer/employer_profile/' . $center_id), 'center_id' => $center_id]);
            } 

        } else {
            if ($this->center_model->isEmployer()){

                $emp_details    = $this->db->select('package_id')->from('emp_packages_trans')->where('status', '1')->where('employer_id', $this->center_model->loginId())->get()->result_array();
                if(empty($emp_details[0]['package_id'])){
                    redirect('employer/packages');
                }

                $this->access_method();
        
               
                $employer_id        = $this->center_model->loginId();
                $tab                = $this->uri->segment(4, 'overview');
                $tabs = [
                    'overview' => [
                        'title' => 'Overview',
                        'icon' => array('eye', 3),
                        'url' => 'overview'
                    ],
                    'documents' => [
                        'title' => 'Documents',
                        'icon' => array('file', 5),
                        'url' => 'documents'
                    ],
                    'change-password' => [
                        'title' => 'Change Password',
                        'icon' => array('key', 4),
                        'url' => 'change-password'
                    ]
                ];
               
                if (isset($tabs[$tab])) {
                    $this->ki_theme->set_breadcrumb($tabs[$tab]);
                    $center = $this->center_model->get_emp($employer_id);
                    $this->set_data('ttl_job_post', 0);
                    $this->set_data('ttl_hired', 0);
                    $this->set_data($center->row_array());
                    $this->set_data('center_details', $center->row_array());
                    $center_id = base64_encode($employer_id);
                    $this->view('employer_profile', ['tabs' => $tabs, 'tab' => $tab, 'current_link' => base_url('employer/employer_profile/' . $center_id), 'center_id' => $center_id]);
                } 
            } else
            $this->employer_view('login');
        }
    }

    function manage_packages(){
        $this->ki_theme->set_title('Manage Packages', true);
        $data           = [];
        if(!empty($_GET['id'])){
            $get = $this->db->select('*')->from('emp_packages')->where('id', $_GET['id'])->get()->result_array();
            if(!empty($get[0]['id'])){
                $data = $get[0];
            }
        }
        $this->set_data('form', $data);
        $this->view('manage_packages');
    }

    function packages_list(){
        $this->ki_theme->set_title('Packages List', true);
        $this->view('packages_list');
    }

    function packages(){
        $this->ki_theme->set_title('Packages', true);
        $this->view('packages');
    }

    function job_request_report(){
        $this->ki_theme->set_title('Job Request Report', true);
        $this->view('job_request_report');
    }
}
