<?php
class Student extends Ajax_Controller
{
    function edit_form()
    {
        $this->response('form', 'Welcome');
        $this->response('url', 'Welcome');
        $this->response('status', true);
    }
    function send_request()
    {
        $data = [];
        $student_id = $this->post('id');
        if($this->center_model->isCenter()) {
            $center_id = $this->center_model->loginId();
        }
        $status = 1;
        $data['student_id'] = $student_id;
        $data['center_id']  = $center_id;
        $data['status']     = $status;
        $data['date'] = date('d-m-Y H:i:s');
        $this->db->insert('student_change', $data);
        $this->response('status', true);
    }
    function change_admission_status()
    {
        // $this->response($this->post());
        $stuent_id = $this->post('student_id');
        $status = $this->post('type');
        $this->student_model->update_admission_status($stuent_id, $status);
        $this->response('status', true);
    }
    function search_by_roll_no()
    {
        $this->load->library('parser');
        // $this->response('roll_no',$this->post('roll_no'));
        $get = $this->student_model->get_student_via_roll_no($this->post('roll_no'));
        if ($get->num_rows()) {
            $this->response('status', true);
            $data = $get->row_array();
            $this->set_data($data);
            $this->set_data('admission_status', $data['admission_status'] ? label($this->ki_theme->keen_icon('verify text-white') . ' Verified Student') : label('Un-verified Student', 'danger'));
            $this->set_data('student_profile', $data['image'] ? base_url('upload/' . $data['image']) : base_url('assets/media/student.png'));
            $this->response('html', $this->template('student-profile-card'));
        } else
            $this->response('html', 'Student Not Found.');
    }
    function get_via_id()
    {
        $this->load->library('parser');
        $get = $this->student_model->get_student_via_id($this->post('student_id'));
        if ($get->num_rows()) {
            $this->response('status', true);
            $data = $get->row_array();
            $this->set_data($data);
            $this->set_data('admission_status', $data['admission_status'] ? label($this->ki_theme->keen_icon('verify text-white') . ' Verified Student') : label('Un-verified Student', 'danger'));
            $this->set_data('student_profile', $data['image'] ? base_url('upload/' . $data['image']) : base_url('assets/media/student.png'));
            $this->response('html', $this->template('student-profile-card'));
        } else
            $this->response('html', 'Student Not Found.');
    }
    function fetch_center_via_type()
    {
        if(isset($_POST["center_type"]) && $this->post("center_type") != 'selected' && $this->post("center_type") != 'all'){
            $row = $this->db->where('type', 'center')->where('isDeleted', $this->post("center_type"))->get('centers');
        } else {
            $row = $this->db->where('type', 'center')->get('centers');
        }
        
        $this->response('status', ($row->num_rows() > 0));
        $this->response('data', $row->result());
    }
    function fetch_student_via_center()
    {
        $row = $this->student_model->fetch_student_center_wise($this->post("center_id"));
        $this->response('status', ($row->num_rows() > 0));
        $this->response('data', $row->result());
    }
    function fetch_student_via_batch()
    {
        // sleep(3);
        $row = $this->student_model->get_student_via_batch($this->post("batch_id"));
        $this->response('status', ($row->num_rows() > 0));
        $this->response('data', $row->result());
    }
    function get_student_via_center_course_batch()
    {
        $row = $this->student_model->get_student_via_center_course_batch($this->post());
        $this->response('status', ($row->num_rows() > 0));
        $this->response('students', $row->result());
    }
    function get_course_subjects()
    {
        $get = $this->center_model->get_assign_courses($this->post('center_id'));
        $subjectsArray = [];
        if ($get->num_rows()) {
            foreach($get->result_array() as $key => $val){
                if($val['course_id'] == $this->post('course_id')){
                    $where                      = [];
                    $where['course_id']         = $val['course_id'];
                    $where['duration']          = $val['duration'];
                    $where['duration_type']     = $val['duration_type'];
                    $where['isDeleted']         = 0;
                    $subjectsArray              = $this->student_model->course_subject($where)->result_array();
                }
            }
        }
        
        
        $subjectNArray               = [];
        foreach($subjectsArray as $key => $value){
            $subjectNArray[$value['subject_id']]['id'] = $value['subject_id'];
            $subjectNArray[$value['subject_id']]['subject_name'] = $value['subject_name'];
            $subjectNArray[$value['subject_id']]['subject_code'] = $value['subject_code'];
        }

        $subjectArray               = [];
        foreach($subjectNArray as $key => $value){
            $subjectArray[] = $value;
        }

        $this->response('status', count($subjectArray));
        $this->response('subjects', $subjectArray);
        
    }
    function add()
    {

        /*if ($walletSystem = ( (CHECK_PERMISSION('WALLET_SYSTEM') && $this->center_model->isCenter()))) {
            $deduction_amount = $this->ki_theme->get_wallet_amount('student_admission_fees');
            $close_balance = $this->ki_theme->wallet_balance();
            if ($close_balance < 0 or $close_balance > $deduction_amount) {
                $this->response('html', 'Your Wallet Balance is Low.');
                exit;
            }
        }*/
        if ($walletSystem = (CHECK_PERMISSION('WALLET_SYSTEM_COURSE_WISE') && $this->center_model->isCenter())) {
            $deduction_amount = $this->center_model->get_assign_courses(
                $this->post('center_id'),
                ['course_id' => $this->post('course_id')]
            )->row('royality_fees');

            $close_balance = $this->ki_theme->wallet_balance();

            if(intval($close_balance) == 0){
                $this->response('html', "Please recharge your wallet $deduction_amount.");
                exit;
            }

            if(intval($deduction_amount) == 0){
                $this->response('html', 'Course Royality Fee is Low.');
                exit;
            }

            $close_balance = $close_balance - $deduction_amount;
            if ($close_balance < 0) {
                $this->response('html', "Wallet Balance is should be minimum $deduction_amount.");
                exit;
            }
        }

        $data = $this->post();
        $enquiry_id = 0;
        if(!empty($data['enquiry_id'])){
            $enquiry_id = $data['enquiry_id'];
        }

        unset($data['fees_type']);
        unset($data['fees_period']);
        unset($data['fees_payment_type']);
        unset($data['fees_amount']);
        unset($data['fees_paid']);
        unset($data['fees_description']);
        unset($data['enquiry_id']);

        if (isset($data['referral_id'])) {
            $referral_id = $data['referral_id'];
            unset($data['referral_id']);
        }

        $data['status'] = 0;
        $data['added_by'] = isset($data['added_by']) ? $data['added_by'] : 'admin';
        $data['admission_type'] = isset($data['admission_type']) ? $data['admission_type'] : 'offline';
        // $data['type'] = 'center';
        $email = $data['email_id'];
        unset($data['email_id'], $data['upload_docs']);
        $data['email'] = $email;
        $upload_docs_data = [];
        $upload_docs = $this->post('upload_docs');
        if (isset($upload_docs['title'])) {
            foreach ($upload_docs['title'] as $index => $file_index_name) {
                if (!empty($_FILES['upload_docs']['name']['file'][$index])) {
                    $file = $_FILES['upload_docs']; //['file'][$index];
                    if ($file['error']['file'][$index] == UPLOAD_ERR_OK) {
                        $encryptedFileName = substr(hash('sha256', uniqid(mt_rand(), true)), 0, 10) . '_' . basename($file['name']['file'][$index]);
                        // Build the full destination path, including the encrypted file name
                        $destination = UPLOAD . $encryptedFileName;
                        move_uploaded_file($file['tmp_name']['file'][$index], $destination);
                        $upload_docs_data[$file_index_name] = $encryptedFileName;
                    }
                }
            }
        }
        $data['adhar_front'] = $this->file_up('adhar_card');
        // $data['adhar_back'] = $this->file_up('adhar_back');
        $data['image'] = $this->file_up('image');
        $data['upload_docs'] = json_encode($upload_docs_data);
        $data['status'] = true;
        $data['admission_status'] = true;
        if ($this->form_validation->run()) {
            $this->db->insert('students', $data);
            $student_id = $this->db->insert_id();
            if ($walletSystem && $this->center_model->isCenter()) {
                $data = [
                    'center_id' => $this->center_model->loginId(),
                    'amount' => $deduction_amount,
                    'o_balance' => ($close_balance + $deduction_amount),
                    'c_balance' => $close_balance,
                    'type' => 'admission',
                    'description' => 'Student Addmission',
                    'type_id' => $student_id,
                    'added_by' => 'center',
                    'order_id' => strtolower(generateCouponCode(12)),
                    'status' => 1,
                    'wallet_status' => 'debit'
                ];
                $this->db->insert('wallet_transcations', $data);
                $this->response('res',$this->db->insert_id());
                $this->center_model->update_wallet($data['center_id'], $close_balance);
            }
            if (CHECK_PERMISSION('REFERRAL_ADMISSION') && $this->center_model->isAdmin() && isset($_POST['referral_id'])) {
                $this->db->insert('referral_coupons', [
                    'student_id' => $student_id,
                    'coupon_code' => generateCouponCode(),
                    'coupon_by' => $referral_id,
                    'amount' => 500
                ]);
            }

            $feeData = [];
            if (sizeof($this->post('fees_type'))) {
                $fees_type              = $this->post('fees_type');
                $fees_period            = $this->post('fees_period');
                $fees_payment_type      = $this->post('fees_payment_type');
                $fees_amount            = $this->post('fees_amount');
                $fees_paid              = $this->post('fees_paid');
                $fees_description       = $this->post('fees_description');
                foreach ($fees_type as $key => $value) {
                    $pending_amount = intval($fees_amount[$key]) - intval($fees_paid[$key]);
                    $feeData = [
                        'student_id' =>  $student_id,
                        'roll_no' => $this->post('roll_no'),
                        'course_id' => $this->post('course_id'),
                        'center_id' => $this->post('center_id'),
                        'fees_type' => $fees_type[$key],
                        'fees_period' => $fees_period[$key],
                        'payment_type' => $fees_payment_type[$key],
                        'total_amount' => $fees_amount[$key],
                        'paid_amount' => $fees_paid[$key],
                        'pending_amount' => $pending_amount
                    ];
                    $this->db->insert('student_fees', $feeData);
                    $trans_id = $this->db->insert_id();

                    $payment_id = date('hdYHis').rand(100,999);

                    $transData = [
                        'student_fees_id' =>  $trans_id,
                        'payment_id' => $payment_id,
                        'payment_date' => date('d-m-Y'),
                        'amount' => $fees_paid[$key],
                        'description' => $fees_description[$key],
                        'added_by' => $this->ki_theme->loginUser()
                    ];

                    $this->db->insert('student_fees_trans', $transData);
                }
            }

            if(intval($enquiry_id) > 0){
                 $this->db->update('students_enquiry', ['enquiry_status' => 1], ['id' => $enquiry_id]);
            }

            $this->response(
                'status',
                true
            );
        } else
            $this->response('html', $this->errors());

    }
    function enquiry()
    {
        $data = $this->post();
        $data['status'] = 0;
        $data['added_by'] = isset($data['added_by']) ? $data['added_by'] : 'admin';
        $data['enquiry_type'] = 'offline';
        // $data['type'] = 'center';
        $email = $data['email_id'];
        unset($data['email_id']);
        $data['email'] = $email;
        $data['status'] = true;
        $data['enquiry_status'] = 0;
        if (count($data) > 0) {
            $this->db->insert('students_enquiry', $data);
            $this->response(
                'status',
                true
            );
        } else {
            $this->response('html', $this->errors());
        }
    }
    function genrate_a_new_rollno()
    {
        $rollNo = $this->gen_roll_no($this->post('center_id'));
        if ($rollNo) {
            $this->response("status", true);
            $this->response('roll_no', $rollNo);
        }
    }
    function genrate_a_new_enquiryno()
    {
        $enquiryNo = 'ENQ/'.date('Ymd').'/'.$this->post('center_id').'/'.rand(100,999);
        if ($enquiryNo) {
            $this->response("status", true);
            $this->response('enquiry_no', $enquiryNo);
        }
    }
    function get_center_courses()
    {
        $get = $this->center_model->get_assign_courses($this->post('center_id'));
        if ($get->num_rows()) {
            $this->response('courses', $get->result_array());
            $this->response("status", true);
        }
    }

    function get_center_course_batches()
    {
        $get = $this->center_model->get_center_course_batches($this->post('center_id'),$this->post('course_id'));
        if ($get->num_rows()) {
            $this->response("status", true);
            $this->response('batches', $get->result_array());
        } else {
            $this->response("status", false);
        }
    }

    function genrate_a_new_rollno_with_center_courses()
    {
        $this->genrate_a_new_rollno();
        $this->get_center_courses();
    }

    function genrate_a_new_enquiryno_with_center_courses()
    {
        $this->genrate_a_new_enquiryno();
        $this->get_center_courses();
    }

    
    function online_list()
    {
        // $list = $this->db->where('admission_type','online')->get('students');
        // $list = $this->db->select('s.roll_no,s.contact_number,s.name,s.email,c.course_name,s.id as student_id,c.duration,c.duration_type')
        //         ->from('students as s')
        //         ->join("course as c","s.course_id = c.id AND s.admission_type = 'online'")
        //         ->get();
        $this->response('data', $this->student_model->get_online_student());
    }
    function passout()
    {
        $this->response('data', $this->student_model->get_passout_student());
    }
    function list()
    {
        $list = $this->student_model->get_all_student($this->post());

        $this->response('data', $list);
    }
    function listenquiry()
    {
        $list = $this->student_model->get_all_enquiry()->result();
        //pre($list->result());
        $this->response('data', $list);
    }
    function search_student_for_attendance()
    {
        // $this->response($this->post());
        $list = $this->student_model->get_switch('batch', $this->post());
        $attendanceTypes = $this->db->get_where('attendence_type', ['is_active' => 'yes']);
        if ($list->num_rows()) {
            $html = form_hidden('batch_id', $this->post("batch_id")) .
                form_hidden('date', $this->post('attendance_date'));
            $i = 1;
            // $this->response('html', 'wait..');
            foreach ($list->result() as $std) {
                $select = $this->db->limit(1)->get_where('student_attendances', ['date' => $this->post('attendance_date'), 'roll_no' => $std->roll_no]);
                $html .= form_hidden('roll_no[]', $std->roll_no);
                $html .= '<tr>
                            <td>' . $i++ . '.</td>
                            <td>' . $std->roll_no . '</td>
                            <td>' . $std->student_name . '</td>
                            <td>';
                $remark = '';
                foreach ($attendanceTypes->result() as $type) {
                    if ($select->num_rows()) {
                        $row = $select->row();
                        $remark = $row->remark;
                        if ($type->id == $row->attendance_type_id)
                            $this->ki_theme->checked(true);
                    } else {
                        if ($type->id == 1)
                            $this->ki_theme->checked(true);
                    }
                    $html .= $this->ki_theme->html("$type->type &nbsp;&nbsp;")->radio('attendance_type_id[' . $std->roll_no . ']', $type->id, 'd-inline-block');
                }
                $html .= '</td>    
                       <td>
                            <input type="text" name="remark[]" value="' . $remark . '" class="form-control" placeholder="Remark">
                       </td>
                          </tr>';
            }
            $this->response('status', true);
            $this->set_data('tbody', $html);
            $this->response('html', $this->parser->parse('student/submit-attendance', $this->public_data, true));
        } else {
            $this->response('html', 'Students are Not Found of this course..');
        }
    }

    function calculateAttendancePercentage($totalClasses, $attendedClasses) {
        // Check to avoid division by zero
        if ($totalClasses == 0) {
            return 0;
        }
        
        // Calculate the attendance percentage
        $percentage = ($attendedClasses / $totalClasses) * 100;
        
        // Round off the percentage to two decimal places
        return round($percentage, 2);
    }

    function report_student_for_attendance()
    {
        //pre($this->post());
        // $this->response($this->post());
        $filterDate = explode(' - ', $this->post('attendance_date'));
        $startDate = $assigndate = $startForAtt = strtotime($filterDate[0]);
        $endDate = strtotime($filterDate[1]);
        $this->response('attendance_date', $filterDate);
        $listStudents = $this->student_model->get_switch('batch', ['center_id' => $this->post('center_id'),'course_id' => $this->post('course_id'),'batch_id' => $this->post('batch_id')]);
        $html = '';
        if ($listStudents->num_rows()) {
            $this->response('status', true);
            $allTypes = $this->db->where('is_active','yes')->get('attendence_type');
            $html .= '<div class="card card-image card-header p-0"><div class="mb-3 p-3">';
            $allAttTypes = [];
            foreach ($allTypes->result() as $rw) {
                $allAttTypes[$rw->id] = $rw->key_value;
                $html .= label($rw->key_value . "&nbsp;:&nbsp;" . $rw->type, 'light p-4 me-4 fs-3');
            }
            // $html = trim($html,',');
            $html .= '
                    </div>
                    <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead><tr>
                        <th class="bg-light">Student Name</th>
                        <th class="bg-light">Total (Days)</th>
                        <th class="bg-light">Present (Days)</th>
                        <th class="bg-light">Attendance (%)</th>';
            $totalDays = 0;
            while ($startDate <= $endDate && $startDate  <= strtotime(date('Y-m-d'))) {
                $html .= '<th style="width:100px">' . date('Y-m-d', $startDate) . '</th>';
                $startDate += 86400;
                $totalDays++;
            }
            $html .= '</tr></thead><tbody>';
            
            foreach ($listStudents->result() as $row) {
                $totalPresent = 0;
                $startForAtt = $assigndate;
                $html .= '<tr>
                            <th class="bg-light">' . $row->student_name . '</th>
                            <th class="bg-light">' . $totalDays . '</th>';

                $htmlColumn = "";
                while ($startForAtt <= $endDate && $startForAtt  <= strtotime(date('Y-m-d'))) {
                    $date = date('d-m-Y', $startForAtt);
                    $getAtt = $this->db->select('attendance_type_id')->where(['date' => $date, 'roll_no' => $row->roll_no, 'batch_id' => $this->post('batch_id')])->get('student_attendances');
                    $attID = 5;
                    if ($getAtt->num_rows()) {
                        $attID = $getAtt->row('attendance_type_id');
                        if(in_array($attID, [1,2,3,6])){
                            $totalPresent++;
                        }
                    }
                    
                    $htmlColumn .= '<td>' . $allAttTypes[$attID] . '</td>';
                    
                    $startForAtt += 86400;
                }



                $html .= '<td>' . $totalPresent . '</td>';
                $html .= '<td>'. $this->calculateAttendancePercentage($totalDays,$totalPresent) .'% </td>';
                $html .= $htmlColumn;
                $html .= '</tr>';
            }
            $html .= '</tbody></table></div></div>';
            $this->response('html', $html);
        } else {
            $this->response('html', 'Students are not found.');
        }
    }
    
    function save_attendance()
    {
        $batch_id = $this->post('batch_id');
        $date = $this->post('date');
        $roll_nos = $this->post('roll_no');
        $attendanceTypeIDs = $this->post('attendance_type_id');
        $remarks = $this->post('remark');
        foreach ($roll_nos as $index => $roll_no) {
            $attendanceTypeId = $attendanceTypeIDs[$roll_no];
            $remark = $remarks[$index];
            $where = $data = [
                'roll_no' => $roll_no,
                'batch_id' => $batch_id,
                'date' => $date,
            ];
            $data['attendance_type_id'] = $attendanceTypeId;
            $data['remark'] = $remark;
            $get = $this->db->where($where)->get('student_attendances');
            if ($get->num_rows()) {
                $this->db->update('student_attendances', $data, ['id' => $get->row('id')]);
            } else
                $this->db->insert('student_attendances', $data);
            $this->response('html', 'Student Attendance Update Successfully..');
        }
    }
    function create_admint_card()
    {
        $data = [
            'session_id' => $this->post("session_id"),
            'student_id' => $this->post("student_id"),
            'center_id' => $this->post("center_id"),
            'duration_type' => $this->post("course_duration_type"),
            'course_id' => $this->post("course_id"),
            'exam_date' => $this->post('exam_date'),
            'enrollment_no' => $this->post('enrollment_no')
        ];
        $chk = $this->student_model->admit_card($data);
        if ($chk->num_rows()) {
            $this->response('html', alert('Please Check your selected <b>Session</b>. Session already used.', 'warning'));
        } else {
            $data['duration'] = $this->post("duration");
            $data['added_by'] = $this->student_model->login_type();
            $this->db->insert('admit_cards', $data);
            $this->response('status', true);
        }
    }
    function list_admint_cards()
    {
        $get = $this->student_model->admit_card();
        $this->response('data', $get->result_array());
    }
    function genrate_admit_card()
    {
        $dType = $this->post('duration_type');
        $d = $this->post('duration');
        $duration = $dType == 'month' ? 1 : $d;
        $where = ['duration_type' => $dType, 'course_id' => $this->post("course_id"), 'student_id' => $this->post("student_id")];
        $options = [];
        $examDone = false;
        $label = '';
        for ($i = 1; $i <= $duration; $i++) {
            $where['duration'] = ($dType == 'month') ? $d : $i;
            $chk = $this->student_model->check_admit_card($where);
            $sub_label = $this->post('course_name') . ' <b>Admit Card </b>';
            if ($chk->num_rows()) {
                $sub_label .= ' Created on  <b>' . ($chk->row('session')) . '</b>';
            } elseif ($examDone) {
                $sub_label .= "<label class='badge badge-danger'>$label Exam's is not create.</label>";
            } else {
                $sub_label .= "<label class='badge badge-info'>Ready to create.</label>";
            }
            $label = $dType == 'month' ? $d . ' ' . ucfirst($dType) : humnize_duration_with_ordinal($i, $dType);
            $options[] = [
                'id' => $dType == 'month' ? $d : $i,
                'label' => $label,
                'sub_label' => $sub_label, //$this->post('course_name') . ' <b>Admit Card </b>' . ($chk->num_rows() ? ' Created on  <b>' . ($chk->row('session')) . '</b>' : ''),
                'isCreated' => $examDone ? true : $chk->num_rows()
            ];
            if (!$chk->num_rows() || $examDone) {
                break;
            } else {
                $admitCardExam = $this->student_model->get_marksheet_using_admit_card($chk->row('id'));
                $examDone = $admitCardExam->num_rows() == 1;
            }
        }
        $this->response('options', $options);
        $this->response('status', true);
    }
    function marksheet_validation()
    {
        $dType = $this->post('duration_type');
        $d = $this->post('duration');
        $duration = $dType == 'month' ? 1 : $d;
        $where = ['duration' => '', 'duration_type' => $dType, 'course_id' => $this->post("course_id"), 'student_id' => $this->post("student_id")];
        $options = [];
        $admit_card_id = 0;
        for ($i = 1; $i <= $duration; $i++) {
            unset($where['admit_card_id'], $where['duration']);
            $where['duration'] = ($dType == 'month') ? $d : $i;
            $chk = $this->student_model->admit_card($where);
            $disable = true;
            $examNotCreate = false;
            $sublable = $this->post('course_name');
            if ($chk->num_rows()) {
                $admit_card_id = $chk->row('admit_card_id');
                $marksheet = $this->student_model->get_marksheet_using_admit_card($admit_card_id);
                if ($marksheet->num_rows()) {
                    $sublable .= ' <b>Marsksheet</b> Created on ' . $this->ki_theme->date($marksheet->row("date"));
                } else {
                    $examNotCreate = true;
                    $disable = false;
                }
            } else {
                $examNotCreate = true;
                $sublable .= ' <b>Admit card not Generated</b>';
            }
            $options[] = [
                'id' => $where['duration'],
                'label' => $dType == 'month' ? $d . ' ' . ucfirst($dType) : humnize_duration_with_ordinal($i, $dType),
                'sub_label' => $sublable,
                'isCreated' => $disable
            ];
            if ($examNotCreate) {
                break;
            }
        }
        $this->response('admit_card_id', $admit_card_id);
        $this->response('options', $options);
        $this->response('status', true);
    }
    function get_student_exams()
    {
        $post = $this->post();
        $options = [];
        $options = $this->center_model->get_student_exams($post)->result_array();
        $this->response('options', $options);
        $this->response('status', true);
    }
    function generate_marksheet_certificate(){
        if ($walletSystem = (CHECK_PERMISSION('WALLET_SYSTEM') && $this->center_model->isCenter())) {
            $deduction_amount = $this->ki_theme->get_wallet_amount('student_marksheet_fees');
            $close_balance = $this->ki_theme->wallet_balance();
            if ($close_balance < 0) {
                $this->response('html', 'Your Wallet Balance is Low..');
                exit;
            }
        }

        $post = $this->post();
        $data = [
            'admit_card_id' => $post['admit_card_id'],
            'center_id' => $post['center_id'],
            'student_id' => $post['student_id'],
            'duration_type' => $post['course_duration_type'],
            'duration' => $post['duration'],
            'course_id' => $post['course_id'],
            'exam_id' => $post['exam_id'],
            'date' => $post['date']
        ];
        $this->db->insert('marksheets_request', $data);

        $this->response('status', true);
    }
    function list_marksheets_request()
    {
        $center_id = 0;
        if(!empty($_GET['center']) && $_GET['center'] != 'undefined'){
           $center_id = $_GET['center'];
        }
        $this->response('data', $this->student_model->marksheet_request($center_id)->result_array());
    }
    function deletemr($id){
        $this->response('status', $this->db->where('id', $id)->delete('marksheets_request'));
    }
    function create_marksheet_certificate(){
        $post = $this->post();
        if($post['status'] == 'R'){
            $this->db->update('marksheets_request', ['status' => 3], ['id' => $post['id']]);
            $this->response('status', true);
        }

        if($post['status'] == 'A'){
           $this->db->update('marksheets_request', ['status' => 2], ['id' => $post['id']]);
           $data = $this->db->select('*')
            ->from('marksheets_request')
            ->where('id', $post['id'])->get()->result_array();

            if($data[0]['status'] == 2){
                unset($data[0]['id']);
                unset($data[0]['status']);

                $this->db->insert('marksheets', $data[0]);
                $marksheet_id               = $this->db->insert_id();

                $where                      = [];
                $npost                      = [];
                $where['course_id']         = $data[0]['course_id'];
                $where['duration']          = $data[0]['duration'];
                $where['duration_type']     = $data[0]['duration_type'];
                $where['isDeleted']         = 0;
                $npost['student_id']        = $data[0]['student_id'];
                $npost['course_id']         = $data[0]['course_id'];
                $npost['center_id']         = $data[0]['center_id'];
                
                $subjectsArray              = $this->student_model->course_subject($where)->result_array();
                $subjectArray               = [];
                foreach($subjectsArray as $key => $value){
                    $subjectArray[$value['subject_id']] = $value['id'];
                }

                $marks                      = $this->center_model->get_student_exam_marks($npost)->result_array();
                $marksArray = [];
                foreach($marks as $key => $value){
                    if($value['paper_type'] == 'theortical'){
                        $type = 'theory_marks';
                    } else {
                        $type = 'practical';
                    }
                    $marksArray[$subjectArray[$value['subject_id']]][$type] = $value['student_total_marks'];
                }
                
                $subjects                   = [];
                $k                          = 0;
                foreach ($marksArray as $subject_id => $numbers) {
                    $ttl = 0;
                    $theory_marks = (isset($numbers['theory_marks'])) ?
                        $numbers['theory_marks'] : 0;
                    $practical = (isset($numbers['practical'])) ?
                        $numbers['practical'] : 0;
                    $num = [
                        'theory_marks' => $theory_marks,
                        'practical' => $practical
                    ];
                    $num['marksheet_id'] = $marksheet_id;
                    $num['subject_id'] = $subject_id;
                    $num['ttl'] = $theory_marks + $practical;
                    $subjects[] = $num;
                    $k++;
                }
                if ($k) {
                    $this->db->insert_batch('marks_table', $subjects);
                }

                $npost['issue_date']         = $data[0]['date'];
                $checkCertificate = $this->student_model->student_certificates($npost);
                if (!$checkCertificate->num_rows()) {
                    $this->db->insert('student_certificates', $npost);
                }
            }

            $this->response('status', true);
        }
    }
    function create_marksheet()
    {
        // $this->response($this->post());
        if ($walletSystem = (CHECK_PERMISSION('WALLET_SYSTEM') && $this->center_model->isCenter())) {
            $deduction_amount = $this->ki_theme->get_wallet_amount('student_marksheet_fees');
            $close_balance = $this->ki_theme->wallet_balance();
            if ($close_balance < 0) {
                $this->response('html', 'Your Wallet Balance is Low..');
                exit;
            }
        }

        $post = $this->post();
        if (isset($post['marks'])) {
            $data = [
                'admit_card_id' => $post['admit_card_id'],
                'center_id' => $post['center_id'],
                'student_id' => $post['student_id'],
                'duration_type' => $post['course_duration_type'],
                'duration' => $post['duration'],
                'course_id' => $post['course_id'],
                'exam_id' => $post['exam_id'],
                'date' => $post['date']
            ];
            $this->db->insert('marksheets', $data);
            $marksheet_id = $this->db->insert_id();
            $subjects = [];
            $k = 0;
            foreach ($post['marks'] as $subject_id => $numbers) {
                $ttl = 0;
                $theory_marks = (isset($numbers['theory_marks'])) ?
                    $numbers['theory_marks'] : 0;
                $practical = (isset($numbers['practical'])) ?
                    $numbers['practical'] : 0;
                $num = [
                    'theory_marks' => $theory_marks,
                    'practical' => $practical
                ];
                $num['marksheet_id'] = $marksheet_id;
                $num['subject_id'] = $subject_id;
                $num['ttl'] = $theory_marks + $practical;
                $subjects[] = $num;
                $k++;
            }
            if ($k) {
                $this->db->insert_batch('marks_table', $subjects);
            }

            if ($walletSystem) {
                $data = [
                    'center_id' => $this->center_model->loginId(),
                    'amount' => $deduction_amount,
                    'o_balance' => ($close_balance + $deduction_amount),
                    'c_balance' => $close_balance,
                    'type' => 'marksheet',
                    'description' => 'Student Marksheet',
                    'type_id' => $marksheet_id,
                    'added_by' => 'center',
                    'order_id' => strtolower(generateCouponCode(12)),
                    'status' => 1,
                    'wallet_status' => 'debit'
                ];
                $this->db->insert('wallet_transcations', $data);
                $this->center_model->update_wallet($data['center_id'], $close_balance);
            }

            $this->response('subjects', $subjects);
            $this->response('status', true);
        }
    }
    function print_mark_table()
    {
        $where  = $this->post();
        $post   = $this->post();
        unset($where['student_id']);
        unset($where['center_id']);
        unset($where['exam_id']);
        $where['isDeleted'] = 0;
        $marks = $this->center_model->get_student_exam_marks($post)->result_array();
        $marksArray = [];
        foreach($marks as $key => $value){
            if($value['paper_type'] == 'theortical'){
                $type = 'theory_marks';
            } else {
                $type = 'practical';
            }
            
            $marksArray[$value['subject_id']][$type] = $value['student_total_marks'];
        }
        $subjects = $this->student_model->course_subject($where);
        $this->response('status', true);
        $this->set_data('subjects', $subjects->result_array());
        $this->set_data('marks', $marksArray);
        $this->response('marks_table', $this->template('marks-table'));
    }
    function list_admit_cards()
    {
        $data = [];
        $get = $this->student_model->admit_card();
        if ($get->num_rows()) {
            foreach ($get->result_array() as $ad) {
                $ad['admit_card_id'] = $this->encode($ad['admit_card_id']);
                array_push($data, $ad);
            }
        }
        $this->response(
            'data',
            $data
        );
    }
    function filter_for_select()
    {
        $this->response($this->post());
        $query = $this->post('q') ? $this->post('q') : '';
        $results[] = array(
            'id' => '',
            'student_name' => 'No matching records found',
            'disabled' => true
        );
        $get = $this->student_model->search_student_for_select2(['search' => $query]);
        if ($get->num_rows())
            $results = $get->result_array();
        $this->response('results', $results);
    }
    function genrate_certificate()
    {
        // $this->response($this->post());
        $where = $this->post();
        if (isset($where['exam_conduct_date'])) {
            $exam_conduct_date = $where['exam_conduct_date'];
            unset($where['exam_conduct_date']);
        }
        $course_name = $this->post('course_name');
        unset($where['course_name']);
        $certificateWhere = $where;
        unset($where['duration'], $where['duration_type']);
        $this->response('where', $where);
        $checkCertificate = $this->student_model->student_certificates($where);
        if (!$checkCertificate->num_rows()) {
            if (isset($where['exam_conduct_date'])) {
                $where['exam_conduct_date'] = $exam_conduct_date;
            }
            $get = $this->student_model->marksheet($where);
            if (CHECK_PERMISSION('GENERATE_CERTIFICATE_WITHOUT_MARKSHEET')) {
                $this->response('html', '<div class="alert alert-success">' . (
                    $get->num_rows() ? 'The course <b>' . $course_name . '</b> has been completed, you can generate the certificate.' : 'You can Generate Certificate But Marksheet Not Generated of This Student.') .
                    '</div>');
                $this->response('status', true);
            } else {
                if ($get->num_rows()) {
                    $this->response('status', true);
                    $this->response('html', '<div class="alert alert-success">The course <b>' . $course_name . '</b> has been completed, you can generate the certificate.</div>');
                } else
                    $this->response('html', '<div class="alert alert-danger">The course <b>' . $course_name . '</b> is not completed yet</div>');
            }
        } else
            $this->response('html', '<div class="alert alert-danger">The course <b>' . $course_name . '</b> Certificate Already Generated.</div>');
    }
    function create_certificate()
    {
        // $this->response($this->post());
        $data = $this->post();
        if ($walletSystem = (CHECK_PERMISSION('WALLET_SYSTEM') && $this->center_model->isCenter())) {
            $deduction_amount = $this->ki_theme->get_wallet_amount('student_certificate_fees');
            $close_balance = $this->ki_theme->wallet_balance();
            if ($close_balance < 0) {
                $this->response('html', 'Your Wallet Balance is Low..');
                exit;
            }
        }
        $checkCertificate = $this->student_model->student_certificates($data);
        $this->response('html', 'Something went wrong.');
        if (!$checkCertificate->num_rows()) {

            $this->db->insert('student_certificates', $data);
            $certificate_id = $this->db->insert_id();
            if ($walletSystem) {
                $data = [
                    'center_id' => $this->center_model->loginId(),
                    'amount' => $deduction_amount,
                    'o_balance' => ($close_balance + $deduction_amount),
                    'c_balance' => $close_balance,
                    'type' => 'certificate',
                    'description' => 'Student Certificate',
                    'type_id' => $certificate_id,
                    'added_by' => 'center',
                    'order_id' => strtolower(generateCouponCode(12)),
                    'status' => 1,
                    'wallet_status' => 'debit'
                ];
                $this->db->insert('wallet_transcations', $data);
                $this->center_model->update_wallet($data['center_id'], $close_balance);
            }
            $this->response('status', true);
        }
    }
    function list_certificate()
    {
        $this->response('data', $this->student_model->student_certificates()->result_array());
    }
    function delete_certificate($id)
    {
        // $this->response($this->post());
        $this->response('status', $this->db->where('id', $id)->delete('student_certificates'));
    }
    function delete_admit_card($id)
    {
        $check = $this->db->where('admit_card_id', $id)->get('marksheets');
        if ($check->num_rows()) {
            $this->response('html','This Admit Card used in Marksheet, please delete marksheet first.');
        } else {
            $this->response(
                'status',
                $this->db->where('id', $id)->delete('admit_cards')
            );
        }
    }
    function delete_marksheet($id)
    {
        $this->db->where('id', $id)->delete('marksheets');
        $this->db->where('marksheet_id', $id)->delete('marks_table');
        $this->response(
            'status',
            true
        );
    }
    function list_marksheets()
    {
        $this->response('data', $this->student_model->marksheet()->result_array());
    }
    function upload_study_material()
    {
        $this->ki_theme->set_default_vars('max_upload_size', 10485760); // 10MB
        if ($file = $this->file_up('file')) {
            $this->response('status', true);
            $data = $this->post();
            $data['file'] = $file;
            $data['upload_by'] = $this->student_model->login_type();
            $this->db->insert('study_material', $data);
        }
    }
    function list_study_material()
    {
        $this->response('data', $this->student_model->study_materials()->result_array());
    }
    function list_assign_students()
    {
        $students = $this->student_model->get_switch('assign_study_student_list', [
            'course_id' => $this->post("course_id"),
            'center_id' => $this->post('center_id')
        ]);
        $this->set_data('study_id', $this->post('id'));
        $this->set_data('students', $students->result_array());
        $this->response('status', ($students->num_rows() > 0));
        $this->response('html', $this->template('list-study-assign-students'));
    }

    function coupons()
    {
        $this->response('data', $this->student_model->coupons()->result_array());
    }
    function coupon_update()
    {
        $this->response(
            'status',
            $this->db->where('id', $this->post('id'))->update('referral_coupons', [
                'isUsed' => $this->post('isUsed')
            ])
        );
        $this->response('last_query', $this->db->last_query());
    }
    function coupon_update_form()
    {
        $this->response('status', true);
        $this->response('url', 'student/coupon-update');
        $this->set_data($this->student_model->get_coupon_by_id($this->post('id'))->row_array());
        $this->response('form', $this->template('update-coupon-status'));
    }
    function get_id_card_url()
    {
        $this->response([
            'status' => true,
            'url' => base_url('id-card/' . $this->ki_theme->encrypt($this->post('student_id')))
        ]);
    }

    function list_student_request(){
        $list = $this->db->
            select('m.id,m.status,m.date,c.institute_name as center_name,s.name as student_name,s.roll_no')
            ->from('student_change as m')
            ->join('students as s', 's.id = m.student_id', 'left')
            ->join('centers as c', 'c.id = m.center_id', 'left')
            ->where('m.status', 1)
            ->order_by('m.id', 'DESC')
            ->get();
        $data = [];
        if ($list->num_rows())
            $data = $list->result();
        $this->response('data', $data);
    }

    function approve_or_reject(){
        $post = $this->post();
        if($post['status'] == 'R'){
            $this->db->update('student_change', ['status' => 3], ['id' => $post['id']]);
            $this->response('status', true);
        }
        if($post['status'] == 'A'){
            $this->db->update('student_change', ['status' => 2], ['id' => $post['id']]);
            $this->response('status', true);
        }
    }

    function list_student_feestatus(){
        if(empty($_GET['status'])){
            $_GET['status'] = "pending";
        }
        $get = $this->student_model->feestatus($_GET['status']);
        $this->response('data', $get->result_array());
    }

}
