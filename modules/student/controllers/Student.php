<?php
class Student extends MY_Controller
{
    function index()
    {
        redirect('student/profile');

    }
    function view_course_study_material()
    {
        $allsubjects = [];
        if ($this->student_model->isStudent()) {
            $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id,s.batch_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.id', $this->session->userdata('student_id'))->get()->result_array();
            
            if(!empty($receiverArray[0]['course_id'])){
                $where = ['course_id' => $receiverArray[0]['course_id'], 'isDeleted' => 0];
                $subjects = $this->student_model->course_subject_new($where);
                $allsubjects = $subjects->result_array();
            } else {
                $allsubjects = [];
            }
        } 
        
        $this->set_data('subjects', $allsubjects);
        $this->student_view('view_study_material');
    }
    function my_exam()
    {
        $this->student_view('my-exam');
    }
    function dashboard()
    {
        $this->student_view('dashboard');
    }
    function notification()
    {
        $this->student_view('notification');
    }
    function attendancereport()
    {
        $this->student_view('attendance');
    }
    function classes_report()
    {
        $this->student_view('classes_report');
    }
    function marksheets()
    {
        $this->student_view('marksheets');
    }
    function admit_card()
    {
        $this->student_view('admit-card');
    }
    function certificate()
    {
        $this->student_view('certificate');
    }
    function sign_out()
    {
        $this->session->unset_userdata('student_login');
        $this->session->unset_userdata('student_id');
        redirect('student');
    }

    function list_enquiry()
    {
        $this->view('listenquiry');
    }
    function pending_list()
    {
        $this->view('all', ['title' => 'Pending']);
    }
    function approve_list()
    {
        $this->view('all', ['title' => 'Approved']);
    }
    function cancel_list()
    {
        $this->view('all', ['title' => 'Cancel']);
    }
    function search()
    {
        $this->view('search');
    }
    function admission()
    {
        $data = [];
        if(!empty($_GET['enq'])){
            $data = $this->student_model->get_all_enquiry($_GET['enq'])->row_array();

        }
        $this->set_data('data',$data);
        //pre($data);
        //die;
        $this->ki_theme->get_wallet_amount('student_admission_fees');
        $this->view('admission');
    }
    function enquiry()
    {
        $this->ki_theme->get_wallet_amount('student_admission_fees');
        $this->view('enquiry');
    }
    function online_admission()
    {
        $this->view('online-admission');
    }
    function all()
    {
        $this->view('all');
    }
    function attendance()
    {
        $this->view('attendance');
    }
    function attendance_report()
    {
        $this->view('attendance');
    }
    function generate_admit_card()
    {
        $this->view('generate-admit-card');
    }
    function get_admit_card()
    {
        $this->view('get-admit-card');
    }
    function list_admit_card()
    {
        $this->view('list-admit-card');
    }
    function collect_fees() // old of collect_student_fees
    {
        $this->view('collect-fees');
    }
    function collect_student_fees() // updated from collect_fees
    {
        $this->view('collect-student-fees');
    }
    function search_fees_payment()
    {
        $this->view('search-fees-payment');
    }
    function create_certificate()
    {
        $this->ki_theme->get_wallet_amount('student_certificate_fees');

        $this->view('create-ceriticate');
    }
    function list_certificate()
    {
        $this->view('list-certificate');
    }
    function create_marksheet()
    {
        $this->ki_theme->get_wallet_amount('student_marksheet_fees');

        $this->view('create-marksheet');
    }
    function list_marksheet()
    {
        $this->view('list-marksheet');
    }
    function get_marksheet()
    {
        $this->view('get-marksheet');
    }

    function generate_marksheet_certificate()
    {
        $this->view('generate-marksheet-certificate');
    }

    function generate_marksheet_certificate_request()
    {
        $this->view('generate-marksheet-certificate-request');
    }

    function student_request(){
        $this->view('student_request');
    }
    function profile($stdId = 0, $tab = 'overview')
    {
        $tabs = [
            'overview' => ['title' => 'Account Overview', 'icon' => array('people', 2), 'url' => ''],
            'setting' => ['title' => 'Update', 'icon' => array('pencil', 3), 'url' => 'setting'],
            'fee-record' => ['title' => 'Account Fees Record', 'icon' => array('two-credit-cart', 3), 'url' => 'fee-record'],
            'change-password' => ['title' => 'Account Change Password', 'icon' => array('key', 2), 'url' => 'change-password']
        ];
        
        $tabs['documents'] = [
            'title' => 'Document(s)',
            'icon' => array('tablet-book', 5),
            'url' => 'documents'
        ];
        if ($this->student_model->isStudent()) {
            $tabs['resume-headline-and-key-skill'] = [
                'title' => "Resume Headline & Key Skill's",
                'icon' => array('tablet-book', 5),
                'url' => 'resume-headline-and-key-skill'
            ];
        }
        /*if (table_exists('manual_notifications')) {
            $tabs['notification'] = [
                'title' => 'Notification(s)',
                'icon' => array('notification', 3),
                'url' => 'notification'
            ];
        }*/
        if (is_numeric($stdId) and $stdId) {
            if (!$this->student_model->isStudent()) {
                $tabs['other'] = [
                    'title' => 'Setting',
                    'icon' => array('setting-2', 2),
                    'url' => 'other'
                ];
            }
            $get = $this->student_model->get_student_via_id($stdId);
            if ($get->num_rows()) {
                if (isset($tabs[$tab]))
                    $this->ki_theme->set_breadcrumb($tabs[$tab]);
                
                if($this->center_model->isCenter()) {
                    $this->set_data('change_details', $this->student_model->change_request($stdId)->row_array());
                }


                $this->set_data($get->row_array());
                $this->set_data('student_details', $get->row_array());
                $this->view('profile', ['isValid' => true, 'tabs' => $tabs, 'tab' => $tab, 'current_link' => base_url('student/profile/' . $stdId), 'student_id' => $stdId]);

            }
        } else {
            if ($this->student_model->isStudent()) {
                $tab            = $this->uri->segment(3, 'overview');
                $stdId          = $this->student_model->studentId();
                $get            = $this->student_model->get_student_via_id($stdId);

                $dataArray                          = $get->row_array();
                $geta                               = $this->db->select('*')->where('student_id', $stdId)->get('student_profile');
                $dataArray['resume_headline']       = $geta->row('resume_headline');
                $dataArray['resume']                = $geta->row('resume');
                $dataArray['experience']            = $geta->row('experience');
                if(!empty($geta->row('key_skills'))){
                    $dataArray['key_skills']            = json_decode($geta->row('key_skills'),true);
                } else {
                    $dataArray['key_skills']            = [];
                }

                if(!empty($geta->row('industries'))){
                    $dataArray['industries']            = json_decode($geta->row('industries'),true);
                } else {
                    $dataArray['industries']            = [];
                }

                if(!empty($geta->row('pan_languages'))){
                    $dataArray['pan_languages']            = json_decode($geta->row('pan_languages'),true);
                } else {
                    $dataArray['pan_languages']            = [];
                }
                
                $dataArray['profile_summary']       = $geta->row('profile_summary');
                $dataArray['fluancy_in_english']    = $geta->row('fluancy_in_english');
                unset($tabs['setting']);
                if ($get->num_rows()) {
                    $this->ki_theme->set_breadcrumb($tabs[$tab]);
                    $this->set_data($dataArray);
                    $this->set_data('student_details', $get->row_array());
                    $this->set_data('isStudent', true);
                    // exit($tab);
                    // $this->student_view($tab,['isValid' => true,'isStudent' => true]);
                    $this->student_view('../profile', ['isValid' => true, 'tabs' => $tabs, 'tab' => $tab, 'current_link' => base_url('student/profile'), 'student_id' => $stdId]);
                }
            } else
                $this->student_view('index');
        }
    }
    function id_card()
    {
        if ($this->student_model->isStudent()) {
            redirect('id-card/' . $this->ki_theme->encrypt($this->student_model->studentId()));
        } else
            show_404();
    }

    function manage_study_material()
    {
        $this->view(__FUNCTION__);
    }
    // test area
    function loginId()
    {
        return 2;
    }
    function test()
    {
        //    $this->load->view('firebase');
        // $this->ki_theme->set_default_vars('max_upload_size',10485760);
        // echo $this->ki_theme->default_vars('max_upload_size') / 1024;
        // echo $this->student_model->study_materials()->num_rows();
        // $where = ['course_id' => 11, 'isDeleted' => 0];
        // $subjects = $this->student_model->course_subject($where);
        // echo $subjects->num_rows();
        $record = $this->exam_model->get_shuffled_questions(1, 10);
        pre($record);
    }
    // this is only for referral code
    function coupons()
    {
        $this->view(__FUNCTION__);
    }
    function passout_student_list()
    {
        $this->view('passout-student-list');
    }
    function get_id_card()
    {
        $this->view('get-id-card');
    }
    function list_by_center()
    {
        $this->view('list-by-center');
    }

    function downloads(){
        $down = [];
        if(!empty($_GET['id'])){
            $down = $this->db->select("d.*,(SELECT GROUP_CONCAT(file SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS concatenated_files,(SELECT GROUP_CONCAT(extention SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS files_extention")->from('downloads as d')->where('d.id', $_GET['id'])->get()->row_array();
        }

        $this->set_data('down', $down);
        $this->view('downloads');
    }

    function downloadss(){
        $this->student_view('downloads');
    }

    function fee_status(){
        $status = "pending";
        $this->set_data('status', $status);
        $this->view('fee_status');
    }

    function fee_statuss(){
        $status = "collection";
        $this->set_data('status', $status);
        $this->view('fee_status');
    }

    function received_jobs(){
        $this->student_view('received_jobs');
    }

    function apply_jobs(){
        $this->student_view('apply_jobs');
    }

    public function get_job_roles_for_industries() {
        $industry_ids = $this->input->post('industry_ids');
        $this->load->model('Employer_model');
        $roles = $this->Employer_model->get_job_roles_by_industries($industry_ids);
    
        echo json_encode([
            'status' => true,
            'roles' => $roles
        ]); 
    }

    public function get_skills_for_roles() {
        log_message('error', 'SKILL FETCH CALLED'); // Add this
        $role_ids = $this->input->post('role_ids');
        $this->load->model('Employer_model');
        $skills = $this->Employer_model->get_skills_by_roles($role_ids);
    
        echo json_encode(['status' => true, 'skills' => $skills]);
    }
    
    
    
    
    
}
?>