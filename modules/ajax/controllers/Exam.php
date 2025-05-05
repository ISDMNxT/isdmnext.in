<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
class Exam extends Ajax_Controller
{
    function create()
    {
        $data = $this->post();
        $data['timer_status'] = isset ($data['timer_status']) ? 1 : 0;
        $data['schedule_status'] = isset ($data['schedule_status']) ? 1 : 0;
        // $this->response($this->post());
        $this->db->insert('exams', $data);
        $this->response('status', true);
    }

    function update_status()
    {
        // $this->response($this->post());
        $this->db->where('id', $this->post('id'))->update('exams', ['status' => $this->post('status')]);
        $this->response('satuts', true);
    }

    function update($id)
    {
        $id = $this->decode($id);
        $data = $this->post();
        $data['timer_status'] = isset ($data['timer_status']) ? 1 : 0;
        $data['schedule_status'] = isset ($data['schedule_status']) ? 1 : 0;
        $this->response('status', $this->db->where('id', $id)->update('exams', $data));
    }

    function list()
    {
        $this->response('data', $this->exam_model->fetch_all()->result());
    }

    function delete($id)
    {
        $this->db->where('id', $id)->update('exams_master', ['isDeleted' => 1]);
        $this->response('satuts', true);
    }

    function edit_form()
    {
        $get = $this->exam_model->fetch_all($this->post('id'));
        // $this->set_data($this->post());
        if ($get->num_rows()) {
            $this->set_data($get->row_array());
            $this->response([
                'url' => 'exam/update/' . $this->encode($get->row('exam_id')),
                'form' => $this->parse('exam/edit', [], true),
                'status' => true
            ]);
        } else {
            $this->response('form', alert('Exam Not Found', 'danger'));
        }
    }

    function paper_list()
    {
        $popupType = isset($_POST['popupType']);
        $getPapers = $this->exam_model->list_subject_paper($this->post('id'));
        $this->set_data('papers', $getPapers->result_array());
        $this->set_data('popupType', $popupType);
        $this->set_data('num_rows', $getPapers->num_rows() ? true : false);
        $this->response('status', $getPapers->num_rows() ? true : false);
        $this->response(
            'html',
            $getPapers->num_rows() ?
            $this->parse('exam/paper-list', [], true)
            : alert('No Paper(s) found in this subject.', 'danger mb-10')
            . '<img class="mx-auto h-150px h-lg-200px" src="' . base_url('assets/media/illustrations/sigma-1/13.png') . '">'
        );
    }

    function manage_papers()
    {
        $isEdit = isset($_POST['paper_id']);
        $data = [
            'subject_id' => $this->post('subject_id'),
            'paper_name' => $this->post('paper_name'),
            'paper_type' => $this->post('paper_type'),
            'paper_duration' => $this->post('paper_duration'),
            'total_marks' => $this->post('total_marks')
        ];
        if ($isEdit) {
            $paper_id = $this->post("paper_id");
            $this->db->where('id', $paper_id)->update('question_paper', $data);
        } else {
            $data['active'] = 1;
            $data['status'] = 1;

            $this->db->insert('question_paper', $data);
            $paper_id = $this->db->insert_id();
        }
        
        if ($paper_id) {
            $this->response('status', true);
        }
    }

    function delete_paper()
    {
        $this->db->where('id', $this->post('p_id'))->delete('question_paper');
        $this->response('status', true);
    }

    function manage_question_papers()
    {
        $total_marks    = $this->post('total_marks');
        if(!isset($_POST['q_ids'])){
            $this->response('status', false);
            $this->response('error', 'Please Select Atleast One Question.');
        }  else {
            $temp_marks     = 0;
            foreach($this->post('q_ids') as $key => $qid){
                $q_marks          = $this->post('marks_'.$qid);
                $temp_marks = intval($temp_marks) + intval($q_marks);
            }

            if($temp_marks > intval($total_marks)){
                $this->response('status', false);
                $this->response('error', 'Selected Questions Total Marks Should be Lessthen Or Equalto Paper Total Marks.');
            } else {
                $this->db->where('paper_id', $this->post('paper_id'))->delete('question_paper_trans');
                foreach($this->post('q_ids') as $key => $qid){
                    $data = [
                        'paper_id' => $this->post('paper_id'),
                        'question_id' => $qid,
                        'status' => 1
                    ];
                    $this->db->insert('question_paper_trans', $data);
                }
                
                $this->response('status', true);
            }
        }
    }

    function list_questions()
    { 
        $getQuestions = $this->exam_model->list_subject_questions($this->post('id'));
        $this->set_data('popupType', $this->post('popupType'));
        if($this->post('popupType') == 'paper')
        $this->set_data('paperData', $this->post('paperData'));
        else
        $this->set_data('paperData', []);    
        $this->set_data('questions', $getQuestions->result_array());
        $this->set_data('num_rows', $getQuestions->num_rows() ? true : false);
        $this->set_data('subject_id', $this->post('id'));
        $this->response('status', $getQuestions->num_rows() ? true : false);
        $this->response(
            'html',
            $getQuestions->num_rows() ?
            $this->parse('exam/manage-questions-and-answers', [], true)
            : alert('No Question(s) found in this subject.', 'danger mb-10')
            . '<img class="mx-auto h-150px h-lg-200px" src="' . base_url('assets/media/illustrations/sigma-1/13.png') . '">'
        );
    }

    function add_question_with_answers()
    {
        if ($this->validation('question_add')) {
            $answers = json_decode($this->post('answer_list'));
            $data = [
                'exam_id' => $this->post('exam_id'),
                'question' => $this->post('question')
            ];
            $this->db->insert('exam_questions', $data);
            $ques_id = $this->db->insert_id();
            $saveAns = [];
            $updateAns = [];
            foreach ($answers as $i => $ans) {
                $saveAns[] = [
                    'answer' => $ans->answer,
                    'is_right' => $ans->is_right,
                    'ques_id' => $ques_id
                ];
            }
            if ($ques_id) {
                $this->db->insert_batch('exam_ques_answers', $saveAns);
                $this->response('status', true);
            }
        }
    }

    function manage_question_with_answers()
    {
        $isEdit = isset ($_POST['question_id']);
        if(!$isEdit){
            $this->form_validation->set_rules('question','Question','required|is_unique[questions.question]');
        }
        if ($this->validation('question_add')) {
            $ansIDs = $this->post("ans_id");
            $answers = json_decode($this->post('answer_list'));
            $data = [
                'subject_id' => $this->post('subject_id'),
                'question' => $this->post('question'),
                'question_type' => $this->post('question_type'),
                'difficulty_level' => $this->post('difficulty_level'),
                'marks' => $this->post('marks'),
                'negative_marks' => $this->post('negative_marks')
            ];
            if ($isEdit) {
                $ques_id = $this->post("question_id");
                $this->db->where('id', $ques_id)->update('questions', $data);
            } else {
                $data['status'] = 1;
                $this->db->insert('questions', $data);
                $ques_id = $this->db->insert_id();
            }
            $saveAns = [];
            $updateAns = [];
            foreach ($answers as $i => $ans) {
                $tempAns = [
                    'answer' => $ans->answer,
                    'is_right' => $ans->is_right,
                    'question_id' => $ques_id
                ];

                if (isset ($ansIDs[$i]) AND $ansIDs[$i]) {
                    $tempAns['id'] = $ansIDs[$i];
                    $updateAns[] = $tempAns;
                } else {
                    $saveAns[] = $tempAns;
                }
            }
            if ($ques_id) {
                if (count($saveAns) > 0){
                    $this->db->insert_batch('questions_answers', $saveAns);
                }
                if (count($updateAns) > 0){
                    $this->db->update_batch('questions_answers', $updateAns, 'id');
                }
                $this->response('status', true);
            }
        }
    }

    function manage_question_with_answers_file(){
        $subject_id     = $this->post('subject_id');
        // Load the Excel file
        $spreadsheet    = IOFactory::load($_FILES['question_file']['tmp_name']);
        $sheet          = $spreadsheet->getActiveSheet();
        // Read data
        $data           = $sheet->toArray();

        $header                     = [];
        $header[]                   = 'Difficulty Level';
        $header[]                   = 'Question Type';
        $header[]                   = 'Question';
        $header[]                   = 'Option1';
        $header[]                   = 'Option2';
        $header[]                   = 'Option3';
        $header[]                   = 'Option4';
        $header[]                   = 'Marks';
        $header[]                   = 'Negative Marks';
        $header[]                   = 'Right Answer';

        $difficultyLevel            = [];
        $difficultyLevel[]          = 'easy';
        $difficultyLevel[]          = 'medium';
        $difficultyLevel[]          = 'hard';

        $questionType               = [];
        $questionType[]             = 'objective';
        $questionType[]             = 'truefalse';
        $questionType[]             = 'fillintheblanks';
        $questionType[]             = 'subjective';

        $option                     = [];
        $option[]                   = 'option1';
        $option[]                   = 'option2';
        $option[]                   = 'option3';
        $option[]                   = 'option4';
        
        $error                      = "";
        foreach($data as $index => $values){
            if($index == 0){
                foreach($values as $key => $val){
                    if($val != $header[$key]){
                        $error = "Headers Row is not in proper sequence.";
                        $this->response('error', $error);
                        $this->response('status', false);
                        break;
                    }
                }
            }

            if($index > 0){
                if(!in_array(strtolower($values[0]), $difficultyLevel)){
                    $error = "Difficulty Level Column Value Should be Easy/Medium/Hard.";
                    $this->response('error', $error);
                    $this->response('status', false);
                    break;
                }

                $values[1] = str_replace('/', '', $values[1]);
                $values[1] = str_replace(' ', '', $values[1]);
                if(!in_array(strtolower($values[1]), $questionType)){
                    $error = "Question Type Column Value Should be Objective or True/False or Fill in the blanks or Subjective.";
                    $this->response('error', $error);
                    $this->response('status', false);
                    break;
                }

                if(!in_array(strtolower($values[9]), $option)){
                    $error = "Right Answer Column Value Should be Option1/Option2/Option3/Option4.";
                    $this->response('error', $error);
                    $this->response('status', false);
                    break;
                }

                if(empty($values[7])){
                    $error = "Marks Column Value required.";
                    $this->response('error', $error);
                    $this->response('status', false);
                    break;
                } else {
                    if(!is_numeric($values[7])){
                        $error = "Marks Column Value Should be numeric.";
                        $this->response('error', $error);
                        $this->response('status', false);
                        break;
                    } else {
                        if(intval($values[7]) <= 0){
                            $error = "Marks Column Value Should be greater then zero.";
                            $this->response('error', $error);
                            $this->response('status', false);
                            break;
                        }
                    }
                }

                if(!empty($values[8])){
                    if(!is_numeric($values[8])){
                        $error = "Negative Marks Column Value Should be numeric.";
                        $this->response('error', $error);
                        $this->response('status', false);
                        break;
                    } else {
                        if(is_numeric($values[8]) && intval($values[8]) < 0){
                            $error = "Negative Marks Column Value Should be greater then zero.".$values[8];
                            $this->response('error', $error);
                            $this->response('status', false);
                            break;
                        }
                    }
                } else {
                    $values[8] = 0;
                } 
            }
        }

        if($error == ""){
            foreach($data as $index => $values){
                if($index > 0){
                    $values[0] = strtolower($values[0]);
                    $values[1] = str_replace('/', '', $values[1]);
                    $values[1] = str_replace(' ', '', $values[1]);
                    $values[1] = strtolower($values[1]);
                    $values[9] = strtolower($values[9]);

                    $questionData = [
                        'subject_id' => $subject_id,
                        'question' => $values[2],
                        'question_type' => $values[1],
                        'difficulty_level' => $values[0],
                        'marks' => $values[7],
                        'negative_marks' => (intval($values[8]) > 0) ? $values[8] : 0,
                        'status' => 1
                    ];

                    $this->db->insert('questions', $questionData);
                    $ques_id = $this->db->insert_id();

                    $saveAns = [];
                    if(!empty($values[3])){
                        $tempAns1 = [
                            'answer' => $values[3],
                            'is_right' => ($values[9] == 'option1') ? 1 : 0,
                            'question_id' => $ques_id
                        ];
                        $saveAns[] = $tempAns1;
                    }
                    
                    if(!empty($values[4])){
                        $tempAns2 = [
                            'answer' => $values[4],
                            'is_right' => ($values[9] == 'option2') ? 1 : 0,
                            'question_id' => $ques_id
                        ];
                        $saveAns[] = $tempAns2;
                    }

                    if(!empty($values[5])){
                        $tempAns3 = [
                            'answer' => $values[5],
                            'is_right' => ($values[9] == 'option3') ? 1 : 0,
                            'question_id' => $ques_id
                        ];
                        $saveAns[] = $tempAns3;
                    }

                    if(!empty($values[6])){
                        $tempAns4 = [
                            'answer' => $values[6],
                            'is_right' => ($values[9] == 'option4') ? 1 : 0,
                            'question_id' => $ques_id
                        ];
                        $saveAns[] = $tempAns4;
                    }

                    if ($ques_id) {
                        if (count($saveAns) > 0){
                            $this->db->insert_batch('questions_answers', $saveAns);
                        }
                    }
                }
            }
            $this->response('status', true);
        } else {
            $this->response('error', $error);
            $this->response('status', false);
        }
    }

    function delete_question()
    {
        $this->db->where('id', $this->post('ques_id'))->delete('questions');
        $this->db->where('question_id',$this->post('ques_id'))->delete('questions_answers');
        $this->response('status', true);
    }

    function remove_answer()
    {
        $this->db->where($this->post())->delete('questions_answers');
        $this->response('status', true);
    }

    public function submit_request() {
        $data = $this->post();
        $submit_type = $data['submit_type'];
        $isEdit = isset($_POST['exam_id']) ? $_POST['exam_id'] : '';
        $students = isset($_POST['student_id']) ? $_POST['student_id'] : [];
        $subjects = isset($_POST['s_id']) ? $_POST['s_id'] : [];
        $papers = isset($_POST['p_id']) ? $_POST['p_id'] : [];
        $s_p = isset($_POST['s_p']) ? json_decode($_POST['s_p'], true) : [];
    
        unset($data['submit_type'], $data['exam_id'], $data['s_id'], $data['p_id'], $data['s_p'], $data['cduration_type'], $data['cduration']);
    
        if ($isEdit) {
            $data['student_id'] = json_encode($data['student_id']);
            if ($submit_type == 'A') {
                $data['start_date'] = date('Y-m-d', strtotime($data['start_date']));
                $data['end_date'] = date('Y-m-d', strtotime($data['end_date']));
                $data['is_admin_approved'] = 2;
            } elseif ($submit_type == 'R') {
                $data['is_admin_approved'] = 3;
            }
    
            $this->db->where('id', $isEdit)->update('exams_master', $data);
    
            if ($submit_type == 'A') {
                $student_rows = ''; // Initialize the string to collect all student rows

                foreach ($students as $stu_id) {
                    foreach ($subjects as $index => $sub_id) {
                        $addArray = [
                            'student_id' => $stu_id,
                            'subject_id' => $sub_id,
                            'question_paper_id' => $papers[$index],
                            'exam_master_id' => $isEdit,
                            'paper_total_marks' => $s_p[$sub_id][$papers[$index]],
                            'status' => 1
                        ];
                        $this->db->insert('exams_master_trans', $addArray);
                    }
    
                    $datan = [
                        'session_id' => $this->post("session_id"),
                        'student_id' => $stu_id,
                        'center_id' => $this->post("center_id"),
                        'duration_type' => $this->post("cduration_type"),
                        'course_id' => $this->post("course_id"),
                        'exam_date' => $data['start_date'],
                        'enrollment_no' => 'ENR' . date('His') . $stu_id
                    ];
    
                    $chk = $this->student_model->admit_card($datan);
                    if (!$chk->num_rows()) {
                        $datan['duration'] = $this->post("cduration");
                        $datan['added_by'] = $this->student_model->login_type();
                        $this->db->insert('admit_cards', $datan);
                        // Define variables used in email
$exam_title = $this->db->select('course_name')->from('course')->where('id', $datan['course_id'])->get()->row('course_name');
$admit_date = date('d M Y', strtotime($datan['exam_date']));
$center = $this->db->select('institute_name,email')->from('centers')->where('id', $datan['center_id'])->get()->row();

// Fallback check
$center_email = isset($center->email) ? $center->email : null;
$center_name = isset($center->institute_name) ? $center->institute_name : 'Your Institute';
$student = $this->db->select('name, id, email')->from('students')->where('id', $stu_id)->get()->row();
$student_email = isset($student->email) ? $student->email : null;
$enrollment_no = $datan['enrollment_no']; // needed for email template

// Email to Student
$subject_student = '✅ Your Admit Card is Ready – ISDM NxT';
$message_student = '
<table style="font-family: Arial; background: #f4f4f4; padding: 20px;">
  <tr>
    <td>
      <h2 style="color: #004aad;">ISDM NxT – Admit Card Issued</h2>
      <p>Hello <strong>' . $student->name . '</strong>,</p>
      <p>Your admit card for the upcoming exam has been successfully generated.</p>
      <p><strong>Exam:</strong> ' . $exam_title . '<br>
      <strong>Date:</strong> ' . $admit_date . '<br>
      <strong>Enrollment No.:</strong> ' . $enrollment_no . '</p>
      <p>You can access your admit card by logging into your ISDM NxT student portal.</p>
      <br><p>Regards,<br><strong>ISDM NxT Team</strong></p>
    </td>
  </tr>
</table>';

// Email to Admin/Center
$subject_admin = '📢 Admit Cards Generated – ISDM NxT';

$message_admin = '
<table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
  <tr>
    <td>
      <h2 style="color: #004aad;">Admit Cards Generated</h2>
      <p>Hello <strong>ISDM NxT Admin</strong>,</p>
      <p>The following students have had their admit cards generated:</p>
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
          <tr style="background-color: #e0e0e0;">
            <th style="padding: 8px; border: 1px solid #ccc;">Student Name</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Student ID</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Enrollment No.</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Course/Exam</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Exam Date</th>
          </tr>
        </thead>
        <tbody>' . $student_rows . '</tbody>
      </table>
      <br><p>Regards,<br><strong>ISDM NxT Team</strong></p>
    </td>
  </tr>
</table>';
if ($student_email) {
    $this->load->library('email');
    $this->email->from('info@isdmnext.in', 'ISDM NxT');
    $this->email->to($student_email);
    $this->email->subject($subject_student);
    $this->email->set_mailtype("html");
    $this->email->message($message_student);
    $this->email->send();
}

// Add to admin email table
$student_rows .= '
<tr>
    <td style="padding: 8px; border: 1px solid #ccc;">' . $student->name . '</td>
    <td style="padding: 8px; border: 1px solid #ccc;">' . $student->id . '</td>
    <td style="padding: 8px; border: 1px solid #ccc;">' . $enrollment_no . '</td>
    <td style="padding: 8px; border: 1px solid #ccc;">' . $exam_title . '</td>
    <td style="padding: 8px; border: 1px solid #ccc;">' . $admit_date . '</td>
</tr>';
}
}

// Send single consolidated email to Admin

if (!empty($student_rows) && $center_email) {
$subject_admin = '📢 Exam Request Approved & Admit Cards Generated – ISDM NxT';
$message_admin = '
<table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
<tr>
    <td>
        <h2 style="color: #004aad;">Exam Request Approved for '.$data["exam_title"].'</h2>
        <p>Hello <strong> '.$center->institute_name.' </strong>,</p>
        <p>The following students have had their admit cards generated:</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background-color: #e0e0e0;">
                    <th style="padding: 8px; border: 1px solid #ccc;">Student Name</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Student ID</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Enrollment No.</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Course/Exam</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Exam Date</th>
                </tr>
            </thead>
            <tbody>' . $student_rows . '</tbody>
        </table>
        <br><p>Regards,<br><strong>ISDM NxT System Notification</strong></p>
    </td>
</tr>
</table>';
$this->load->library('email');
$this->email->from('info@isdmnext.in', 'ISDM NxT');
$this->email->to($center_email);
$this->email->subject($subject_admin);
$this->email->set_mailtype("html");
$this->email->message($message_admin);
$this->email->send();

// Send Emails
// if ($center_email)  $this->send_email($center_email, $subject_admin, $message_admin);

          

                    
                }
            }
        } else {
            $data['student_id'] = json_encode($data['student_id']);
            $data['request_time'] = date('Y-m-d H:i:s');
            $data['status'] = 1;
            $data['is_admin_approved'] = 1;
            $data['isDeleted'] = 0;
            $this->db->insert('exams_master', $data);
    
            // Fetch details
            $center_id = $this->post('center_id');
            $center = $this->db->select('email, institute_name')->from('centers')->where('id', $center_id)->get()->row();
            $student_id = $students[0];
            $student = $this->db->select('name, id')->from('students')->where('id', $student_id)->get()->row();
            $exam_name = $this->db->select('course_name')->from('course')->where('id', $data['course_id'])->get()->row();
            $request_date = date('d M, Y h:i A', strtotime($data['request_time']));
            $logo_url = base_url('upload/logo.png');


            $student_rows = ''; // initialize student rows

            foreach ($students as $student_id) {
                $student = $this->db->select('name, id')->from('students')->where('id', $student_id)->get()->row();
                $exam_name = $this->db->select('course_name')->from('course')->where('id', $data['course_id'])->get()->row();
                $request_date = date('d M, Y h:i A', strtotime($data['request_time']));

                $student_rows .= '
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ccc;">' . $student->name . '</td>
                        <td style="padding: 8px; border: 1px solid #ccc;">' . $student->id . '</td>
                        <td style="padding: 8px; border: 1px solid #ccc;">' . $exam_name->course_name . '</td>
                        <td style="padding: 8px; border: 1px solid #ccc;">' . $request_date . '</td>
                    </tr>';
            }

    
            // Build HTML email
            $admin_message = '
            <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $logo_url . '" alt="ISDM NxT Logo" height="120px" />
                        <h2 style="color: #004aad;">New Student Exam Requests – Action Required</h2>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
                        <p>Hello <strong>ISDM NxT Admin</strong>,</p>
                        <p>The following exam requests have been submitted by <strong>' . $center->institute_name . '</strong>.</p>
                        <h3 style="color: #004aad;">📋 Request Details:</h3>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #e0e0e0;">
                                    <th style="padding: 8px; border: 1px solid #ccc;">Student Name</th>
                                    <th style="padding: 8px; border: 1px solid #ccc;">Student ID</th>
                                    <th style="padding: 8px; border: 1px solid #ccc;">Exam</th>
                                    <th style="padding: 8px; border: 1px solid #ccc;">Request Date</th>
                                </tr>
                            </thead>
                            <tbody>' . $student_rows . '</tbody>
                        </table>
                        <br>
                        <p>Kindly log in and verify the requests:</p>
                        <p>➡️ <a href="https://isdmnext.in/admin-login" style="color: #007bff;">Access the Admin Portal</a></p>
                        <p style="margin-top: 20px;"><strong>Contact Support:</strong><br>
                        ✉️ info@isdmnext.in<br>
                        📞 8320181598 / 8320876233</p>
                        <p>Thank you for maintaining the quality of ISDM NxT examinations.</p>
                        <br>
                        <!--<p>Best regards,<br><strong>ISDM NxT – Admin Portal</strong><br>-->
                        <a href="https://www.isdmnext.in">www.isdmnext.in</a></p>
                    </td>
                </tr>
            </table>';

    
            // Send email
            $this->load->library('email');
            $this->email->from('info@isdmnext.in', 'ISDM NxT');
            $this->email->to('keyurpatel3063@gmail.com'); // or fetch dynamically
            $this->email->subject('New Student Exam Request Submitted – Action Required');
            $this->email->set_mailtype("html");
            $this->email->message($admin_message);
            $this->email->send();
    
            exit; // prevent further processing
        }
    
        $this->response('status', true);
    }
    

    function student_exams_list()
    {
        $postData = $_GET;
        $this->response('data', $this->center_model->student_exams_list($postData)->result());
        $this->response("status",true);
    }    

    function reset_exam(){
        $this->db->where('id', $this->post('id'))->update('exams_master_trans', [
                        'student_total_marks' => NULL,
                        'percentage' => NULL,
                        'data' => NULL,
                        'ttl_right_answers' => NULL,
                        'attempt_time' => NULL,
                        'status' => 1
                    ]);
        $this->response("status",true);
    }

    function reset_marks(){
        $this->db->where('id', $this->post('id'))->update('exams_master_trans', [
                        'student_total_marks' => NULL,
                        'percentage' => NULL,
                        'data' => NULL,
                        'attempt_time' => NULL,
                        'status' => 1
                    ]);
        $this->response("status",true);
    }

    function set_practical_marks()
    {
        if(empty($_POST['description']) || empty($_POST['marks'])){
            $errors = [];
            if(empty($_POST['description'])){
                $errors[] = 'Description is required!';
            }

            if(empty($_POST['marks'])){
                $errors[] = 'Marks is required!';
            }
            $this->response('status', false);
            $this->response('errors', $errors);
        } else {
            $data = $this->db->select('*')
                ->from('exams_master_trans')
                ->where('id', $this->post('id'))
                ->get()->result_array();

            if(isset($data[0]['id']) && intval($data[0]['id']) > 0){

                if(intval($this->post('marks')) <= intval($data[0]['paper_total_marks'])){
                    $percentage = (intval($this->post('marks')) / intval($data[0]['paper_total_marks'])) * 100;
                    $this->db->where('id', $this->post('id'))->update('exams_master_trans', [
                        'student_total_marks' => $this->post('marks'),
                        'percentage' => round($percentage),
                        'data' => $this->post('description'),
                        'attempt_time' => date('Y-m-d H:i:s'),
                        'status' => 2
                    ]);
                    $this->response('status', true);
                } else {
                    $error = 'Marks Should be less or equal to '.$data[0]['paper_total_marks'].'!';
                    $this->response('status', false);
                    $this->response('error', $error);
                }
            } else {
                $this->response('status', false);
                $this->response('error', 'Something wnet wrong!');
            }
        }
    }

    function list_requests(){
        $this->response('data', $this->center_model->list_requests()->result_array());
    }

    function list_assign_students(){
        $students = $this->student_model->get_switch('assign_exam_student_list',[
            'course_id' => $this->post("course_id"),
            'exam_id' => $this->post('exam_id')
        ]);
        $this->set_data('exam_id',$this->post('exam_id'));
        $this->set_data('students', $students->result_array());
        $this->response('status',($students->num_rows() > 0));
        $this->response('html', $this->template('list-assign-students'));
    }

    function assign_to_student()
    {
        $data = [
            'center_id' => $this->post("center_id"),
            'student_id' => $this->post("student_id"),
            'exam_id' => $this->post("exam_id")
        ];
        // $this->response($this->post());
        if($this->post('check_status') == 'true'){
            $data['assign_time'] = time();
            $data['added_by'] = $this->student_model->login_type();
            $this->db->insert('exam_students', $data);  
            $this->response("status",true);
        }
        else{
            $this->db->delete('exam_students',$data);
            $this->response("status",true);
        }
    }    

    private function send_email($to, $subject, $message) {
        $this->load->library('email');
    
        $this->email->from('isdmnxt@gmail.com', 'ISDM Team');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
    
        $sent = $this->email->send();
        $this->email->clear(); // clear settings for next email
        return $sent;
    }
    
}
