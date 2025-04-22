<?php
class Center extends MY_Controller
{
    function add()
    {
        $this->view('add');
    }
    function list()
    {
        $this->ki_theme->set_title('List Center(s)', true);
        $this->view('list');
    }
    function add_master_franchise()
    {
        $id = !empty($_GET['id']) ? base64_decode($_GET['id']) : 0;
        $data = [];
        if(!empty($id)){
            $this->db->where('type','master_franchise');
            $this->db->where('isDeleted', '0');
            $this->db->where('id', $id);
            $data = $this->db->get('centers')->row_array();
        }

        $this->set_data('data',$data);
       
        $this->view('add-master-franchise');
    }
    function list_master_franchise()
    {
        $this->ki_theme->set_title('List Master Franchise', true);
        $this->view('list-master-franchise');
    }
    function add_staff()
    {
        $staff_id = !empty($_GET['id']) ? base64_decode($_GET['id']) : 0;
        $data = [];
        if(!empty($staff_id)){
            $this->db->where('type','staff');
            $this->db->where('isDeleted', '0');
            $this->db->where('id', $staff_id);
            $data = $this->db->get('centers')->row_array();
        }

        $this->set_data('data',$data);
        $permissionArray = array(
            array(
                'label' => 'Course Area',
                'type' => 'course_area'
                ),
            array(
                'label' => 'Academics',
                'type' => 'academics'
                 ),
            array(
                'label' => 'Enquiry',
                'type' => 'enquiry'
                 ),
            array(
                'label' => 'Students',
                'type' => 'student_information'
                 ),
            array(
                'label' => 'Attendance',
                'type' => 'attendance'
                 ),
            array(
                'label' => 'Fee',
                'type' => 'fees_collection'
                 ),
            array(
                'label' => 'Exam(S)',
                'type' => 'exams'
                 ),
            array(
                'label' => 'Marksheet & Certificate',
                'type' => 'student_marksheet'
                 ),
            array(
                'label' => 'Staff',
                'type' => 'staff'
            ),
            array(
                'label' => 'Notification',
                'type' => 'notification'
            ),
            array(
                'label' => 'Downloads',
                'type' => 'downloads'
            ),
            array(
                'label' => 'Reports',
                'type' => 'reports'
            )
        );
        $this->set_data('permission',$permissionArray);
        $this->view('add-staff');
    }
    function list_staff()
    {
        $this->ki_theme->set_title('List Staff', true);
        $this->view('list-staff');
    }
    function deleted_list()
    {
        $this->ki_theme->set_title('List Deleted Center(s)', true);
        $this->view('delete-list');
    }
    function pending_list()
    {
        $this->ki_theme->set_title('List Pedning Center(s)', true);
        $this->view('pending-list');
    }
    function generate_certificate()
    {
        $this->view('generate-certificate');
    }
    function get_certificate()
    {
        $this->view('get-certificate');
    }
    function assign_courses()
    {
        $this->view('assign-courses');
    }
    function notification()
    {
        if ($this->access_method()) {
            $this->view('notification',[
                'ntype' => $_GET['ntype']
            ]);
        }
    }
    function profile()
    {
        if ($this->center_model->isAdmin() OR $this->center_model->isMaster())
            $this->access_method();
        $center_id = $this->uri->segment(3, 0);
        $center_id = $center_id ? base64_decode($center_id) : $center_id;
        // echo $center_id;
        $tab = $this->uri->segment(4, 'overview');
        if($this->center_model->isMaster()){
            $tabs = [
                'overview' => [
                    'title' => 'Overview',
                    'icon' => array('eye', 3),
                    'url' => 'overview'
                ]
            ];
        } else {
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
        }
       
        if (CHECK_PERMISSION('CENTRE_WISE_WALLET_SYSTEM') and $this->center_model->isAdmin()) {
            $tabs['fee-system'] = [
                'title' => 'Fee System',
                'icon' => array('bill', 5),
                'url' => 'fee-system'
            ];
        }
        /*if (table_exists('manual_notifications')) {
            $tabs['notification'] = [
                'title' => 'Notification(s)',
                'icon' => array('notification', 3),
                'url' => 'notification'
            ];
        }*/
        if (isset($tabs[$tab])) {
            // $this->ki_theme->set_title($tabs[$tab]['title']);
            $this->ki_theme->set_breadcrumb($tabs[$tab]);
            $center = $this->center_model->get_center($center_id);
            $this->set_data('ttl_student', $this->db->where('center_id', $center_id)->get('students')->num_rows());
            $this->set_data('ttl_course', $this->db->where('center_id', $center_id)->get('center_courses')->num_rows());
            $this->set_data($center->row_array());
            $this->set_data('center_details', $center->row_array());
            $center_id = base64_encode($center_id);
            $this->view('profile', ['tabs' => $tabs, 'tab' => $tab, 'current_link' => base_url('center/profile/' . $center_id), 'center_id' => $center_id]);
        } else
            show_404();
    }
    function test(){
        if ($walletSystem = ( (CHECK_PERMISSION('WALLET_SYSTEM') && $this->center_model->isCenter()))) {
            $deduction_amount = $this->ki_theme->get_wallet_amount('student_admission_fees');
            $close_balance = $this->ki_theme->wallet_balance();
            // echo $close_balance;
            if ($close_balance < 0 or $close_balance < 0) {
                echo ('Your Wallet Balance is Low..');
                exit;
            }
        }
        elseif ($walletSystem = (CHECK_PERMISSION('WALLET_SYSTEM_COURSE_WISE') && $this->center_model->isCenter())) {
            $deduction_amount = $this->center_model->get_assign_courses(
                5,
                ['course_id' => 44]
            )->row('course_fee');
            $close_balance = $this->ki_theme->wallet_balance();
            $close_balance = $close_balance - $deduction_amount;
            if ($close_balance < 0 or $close_balance < 0 ) {
                echo ('Wallet Balance is Low..'.$deduction_amount);
                exit;
            }
        }
        echo $deduction_amount;
    }

    function financial(){
        $master_id = 0;
        if (!empty($_GET['id'])) {
            $master_id = $_GET['id'];
        } else if ($this->center_model->isMaster()) {
            $master_id = $this->center_model->loginId();
        }
        $this->set_data('master_id',$master_id);
        $this->view('financial');
    }

    function payout_request(){
        $this->view('payout_request');
    }

    function wallet_report(){
        if(!empty($_GET['search_datee'])){
            $filterDate     = $_GET['search_datee'];
        } else {
            $filterDate     = "";
        }
        $this->set_data('filterDate',$filterDate);
        $this->view('wallet_report');
    }

    function trainers_report(){
        $this->view('trainers_report');
    }

    function trainer_view_report(){

        $this->db->select('cp.staff_id,c.institute_name as center_name, cc.name as trainer, cc.email as trainer_email, sm.subject_name, cp.title as class_name, cp.type as class_type, clr.rating, s.name as student_name, s.roll_no,cp.plan_date')
            ->from('class_plan_rating as clr')
            ->join('class_plan as cp', 'cp.id = clr.class_plan_id')
            ->join('centers as c', "c.id = cp.center_id AND c.type = 'center'", 'left')
            ->join('centers as cc', "cc.id = cp.staff_id AND cc.type = 'staff' AND cc.role = 'Trainer'", 'left')
            ->join('subject_master as sm', "sm.id = cp.subject_id", 'left')
            ->join('students as s', "s.id = clr.student_id", 'left');

            if($this->center_model->isCenter()) {
                $this->db->where('cp.center_id', $this->center_model->loginId());
            }
            
            if(!empty($_GET['staff'])) {
                $this->db->where('cp.staff_id', $_GET['staff']);
            }
        $data = $this->db->get()->result();

        //pre($data);

        $this->set_data('data',$data);
        $this->view('trainer_view_report');
    }
}
