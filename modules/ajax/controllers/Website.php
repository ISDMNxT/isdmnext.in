<?php
// 9996763445
class Website extends Ajax_Controller
{
    private function _update_logo($table)
    {
        $this->response($_FILES);
        if ($file = $this->file_up('file')) {
            $this->response('status', true);
            $this->response('file', base_url('upload/' . $file));
            $this->db->update($table, [
                'logo' => $file
            ], [
                'id' => $this->post('id')
            ]);
        }
    }
    function update_center_logo()
    {
        $this->_update_logo('centers');
    }

    private function _update_signature($table)
    {
        $this->response($_FILES);
        if ($file = $this->file_up('file')) {
            $this->response('status', true);
            $this->response('file', base_url('upload/' . $file));
            $this->db->update($table, [
                'signature' => $file
            ], [
                'id' => $this->post('id')
            ]);
        }
    }
    function update_center_signature()
    {
        $this->_update_signature('centers');
    }

    function update_student_profile_image()
    {
        $this->_update_profile('students');
    }
    private function _update_profile($table)
    {
        $this->response($_FILES);
        if ($file = $this->file_up('file')) {
            $this->response('status', true);
            $this->response('file', base_url('upload/' . $file));
            $this->db->update($table, [
                'image' => $file
            ], [
                'id' => $this->post('id')
            ]);
        }
    }
    function update_center_profile_image()
    {
        $this->_update_profile('centers');
    }
    function update_center_profile()
    {
        // $this->response('data',$this->session->userdata());
        $this->db->update('centers', [
            'name' => $this->post('name'),
            'center_full_address' => $this->post('address'),
            'contact_number' => $this->post('phone'),
            'email' => $this->post('email'),
        ], [
            'id' => $this->session->userdata('admin_id')
        ]);
        $this->response('status', true);
    }
    function student_verification()
    {
        if ($this->validation('website_student_verification')) {
            $this->response($this->post());
            $roll_no = $this->post('roll_no');
            $dob = $this->post("dob");
            $status = 1;
            $get = $this->student_model->student_verification([
                'roll_no' => $roll_no,
                'dob' => date('d-m-Y', strtotime($dob)),
                'status' => $status
            ]);
            if ($get->num_rows()) {
                // $this->response("get_student",$get->num_rows());
                $this->response('status', true);
                $data = $get->row_array();
                $this->set_data($data);
                $this->set_data('contact_number', maskMobileNumber($data['contact_number']));

                $this->set_data('admission_status', $data['admission_status'] ? label($this->ki_theme->keen_icon('verify text-white') . ' Verified Student') : label('Un-verified Student', 'danger'));
                $this->set_data('student_profile', $data['image'] ? base_url('upload/' . $data['image']) : base_url('assets/media/student.png'));
                $this->response('html', $this->template('student-profile-card'));
            } else {
                $this->response('error', '<div class="alert alert-danger">Student Not Found.</div>');
            }
        }
    }
    function seach_study_center_list()
    {
        $where = [
            'status' => 1,
            'state_id' => $this->post('state_id'),
            'city_id' => $this->post('city_id')
        ];
        $get = $this->center_model->get_verified($where);
        if ($get->num_rows()) {
            $this->response('status', true);
            $this->set_data('list', $get->result_array());
            $this->response('html', $this->template('study-center-list'));
        }
    }
    function center_verification()
    {
        $get = $this->center_model->get_verified($this->post());
        if ($get->num_rows()) {
            $row = $get->row();
            if ($row->status) {
                $data = $get->row_array();

                $this->response('status', 'yes');
                $this->response('center_number', $row->center_number);

                $this->set_data('center_status', $data['status'] ? label($this->ki_theme->keen_icon('verify text-white') . ' Verified Center') : label('Un-verified Center', 'danger'));
                $this->set_data('owner_profile', $data['image'] ? base_url('upload/' . $data['image']) : base_url('assets/media/student.png'));
                // unset($data['status']);
                $this->set_data($data);
                $this->set_data('contact_number', maskMobileNumber($data['contact_number']));
                $this->set_data('email', maskEmail($data['email']));

                $this->response('html', $this->template('center-details'));
            } else
                $this->response('status', 'no');
        }
    }
    function student_result_verification()
    {
        if ($this->validation('website_student_verification')) {
            $this->response($this->post());
            $roll_no = $this->post('roll_no');
            $dob = $this->post("dob");
            $status = 1;
            $get = $this->student_model->student_result_verification([
                'roll_no' => $roll_no,
                'dob' => date('d-m-Y', strtotime($dob)),
                'status' => $status
            ]);
            if ($get->num_rows()) {
                // $this->response("get_student",$get->num_rows());
                $this->response('status', true);
                $this->response('ttl_record', $get->num_rows());
                if ($get->num_rows() == 1) {
                    $data = $get->row_array();
                    $this->response('data', $data);
                } else {
                    $this->response('data', $get->result_array());
                }
            } else {
                $this->response('error', '<div class="alert alert-danger">Marksheet Not Found.</div>');
            }
        }
    }
    function genrate_a_new_rollno()
    {
        $rollNo = $this->gen_roll_no($this->post('center_id'));
        if ($rollNo) {
            $this->response("status", true);
            $this->response('roll_no', $rollNo);
        }
        return $rollNo;
    }
    function get_center_courses()
    {
        $get = $this->center_model->get_assign_courses($this->post('center_id'));
        if ($get->num_rows()) {
            $this->response('courses', $get->result_array());
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
    function genrate_rollno_for_admission()
    {
        $this->genrate_a_new_rollno();
        $this->get_center_courses();
    }
    function student_admission()
    {
        if ($this->validation('student_admission')) {
            // $this->response('status', true);
            $roll_no = $this->genrate_a_new_rollno();
            $this->response('roll_no', $roll_no);
            // $this->response($this->post());
            $data = $this->post();
            $data['status'] = 0;
            $data['roll_no'] = $roll_no;
            $data['added_by'] = isset($data['added_by']) ? $data['added_by'] : 'web';
            $data['admission_type'] = isset($data['admission_type']) ? $data['admission_type'] : 'offline';
            // $data['type'] = 'center';
            unset($data['upload_docs']);
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
            $chk = $this->db->insert('students', $data);
            $this->response('status', $chk);
            $this->session->set_userdata([
                'student_login' => true,
                'student_id' => $this->db->insert_id()
            ]);
        }
    }
    function get_city($type = 'array')
    {
        $state_id = $this->input->post('state_id');
        if(!empty($_POST['dis_id'])){
            $dis_id = $this->input->post('dis_id');
        } else {
            $dis_id = '';
        }
        
        $cities = $this->db->order_by('DISTRICT_NAME', 'ASC')->get_where('district', ['STATE_ID' => $state_id]);
        $returnCity = [];
        $options = '<option></option>';
        if ($cities->num_rows()) {
            $this->response('status', true);
            foreach ($cities->result() as $row) {
                $returnCity[$row->DISTRICT_ID] = $row->DISTRICT_NAME;
                $sel = '';
                if($dis_id == $row->DISTRICT_ID){
                    $sel = "selected='selected'";
                }
                $options .= '<option value="' . $row->DISTRICT_ID . '" '.$sel.'>' . $row->DISTRICT_NAME . '</option>';
            }
        }
        $this->response('html', $type == 'array' ? $returnCity : $options);
    }
    function test()
    {
        $this->response('ok', true);
    }

    function contact_us_action()
    {
        $this->response(
            'status',
            $this->db->insert('contact_us_action', $this->input->post())
        );

    }
    // function add_center()
    // {
    //     if ($this->validation('add_center')) {
    //         $data = $this->post();
    //         $data['status'] = 0;
    //         $data['added_by'] = 'admin';
    //         $data['type'] = 'center';
    //         $email = $data['email_id'];
    //         unset($data['email_id']);
    //         $data['email'] = $email;
    //         $data['password'] = sha1($data['password']);
    //         ///upload docs
    //         $data['adhar'] = $this->file_up('adhar');
    //         // $data['adhar_back'] = $this->file_up('adhar_back');
    //         $data['image'] = $this->file_up('image');
    //         $data['agreement'] = $this->file_up('agreement');
    //         $data['address_proof'] = $this->file_up('address_proof');
    //         $data['signature'] = $this->file_up('signature');
    //         if (CHECK_PERMISSION('CENTRE_LOGO'))
    //             $data['logo'] = $this->file_up('logo');
    //         $data['isPending'] = 1;
    //         $this->db->insert('centers', $data);
    //         $this->response('status', true);
    //     }
    // }

    public function add_center()
{
    if ($this->form_validation->run('add_center')) {
        $data = $this->post();

        $data['status'] = 1;
        $data['added_by'] = 'admin';
        $data['type'] = 'center';

        $email = $data['email_id'];
        unset($data['email_id']);
        $data['email'] = $email;

        $plainPassword = $data['password']; // Store plain password for email
        $data['password'] = sha1($plainPassword); // Save hashed password in database

        // Upload documents
        $data['adhar'] = $this->file_up('adhar');
        $data['image'] = $this->file_up('image');
        $data['agreement'] = $this->file_up('agreement');
        $data['address_proof'] = $this->file_up('address_proof');
        $data['signature'] = $this->file_up('signature');
        if (CHECK_PERMISSION('CENTRE_LOGO')) {
            $data['logo'] = $this->file_up('logo');
        }

        // Insert into database
        $this->db->insert('centers', $data);

        // ✅ Send Admin and Center Emails
        $this->send_center_emails($data, $plainPassword);

        // ✅ Send success response
        $this->response('status', true);

    } else {
        // ❌ Validation failed, send errors
        $this->response('html', $this->errors());
    }
}


private function send_center_emails($data, $plainPassword)
{
    $admin_email = 'isdmnxt@gmail.com';
    $center_email = $data['email'];

    $admin_subject = 'New Franchise Registration Notification – ISDM NxT';
    $center_subject = 'Congratulations! Your Franchise Registration with ISDM NxT is Successful';

    $admin_message = $this->compose_admin_email($data);
    $center_message = $this->compose_center_email($data, $plainPassword);

    $this->send_email($admin_email, $admin_subject, $admin_message);
    $this->send_email($center_email, $center_subject, $center_message);
}


private function compose_admin_email($data)
{
    $logo_url = base_url('upload/logo.png');

    $location = $this->db->select('STATE_NAME')->from('state')->where('STATE_ID', $data['state_id'])->get()->row()->STATE_NAME ?? 'N/A';
    $registration_date = date('d-m-Y');

    return '
    <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
      <tr>
        <td style="text-align: center;">
          <img src="'. $logo_url.' " alt="ISDM NxT Logo" style="height: 60px;" />
          <h2 style="color: #004aad;">New Franchise Registration Notification – ISDM NxT</h2>
        </td>
      </tr>
      <tr>
        <td style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
          <p>Hello <strong>ISDM NxT Admin</strong>,</p>
          <p>A new client has successfully registered as a franchise with ISDM NxT.</p>
          <h3 style="color: #004aad;">📋 Registration Details:</h3>
          <table style="width: 100%; margin-top: 10px;">
            <tr><td><strong>Institute Name:</strong></td><td>' . $data['institute_name'] . '</td></tr>
            <tr><td><strong>Client Name:</strong></td><td>' . $data['name'] . '</td></tr>
            <tr><td><strong>Mobile Number:</strong></td><td>' . $data['contact_number'] . '</td></tr>
            <tr><td><strong>Email Address:</strong></td><td>' . $data['email'] . '</td></tr>
            <tr><td><strong>Location:</strong></td><td>' . $location . '</td></tr>
            <tr><td><strong>Registration Date:</strong></td><td>' . $registration_date . '</td></tr>
          </table>
          <br>
          <p>Kindly review their details and initiate the next steps if required.</p>
          <p>➡️ <a href="https://www.isdmnxt.in" style="color: #007bff;">Access the Admin Portal</a></p>
          <p style="margin-top: 20px;"><strong>Contact Support:</strong><br>
          ✉️ info@isdmnext.in<br>
          📞 8320181598 / 8320876233</p>
          <p>Thank you for keeping ISDM NxT’s franchise operations strong and successfull!</p>
          <br>
          <p>Best regards,<br><strong>ISDM NxT – Admin Portal</strong><br>
          <a href="https://www.isdmnxt.in">www.isdmnxt.in</a></p>
        </td>
      </tr>
    </table>';
}

private function compose_center_email($data, $plain_password)
{
    $logo_url = base_url('upload/logo.png');
    return '
    <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
      <tr>
        <td style="text-align: center;">
          <img src="'.$logo_url.'" alt="ISDM NxT Logo" style="height: 60px;" />
          <h2 style="color: #004aad;">🎉 Congratulations! Franchise Registration Successfull</h2>
        </td>
      </tr>
      <tr>
        <td style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
          <p>Hello <strong>' . $data['name'] . '</strong>,</p>
          <p>Greetings from ISDM NxT!</p>
          <p>We are thrilled to inform you that your Franchise Registration has been successfully completed.</p>
          <p>Welcome to ISDM NxT\'s growing network of Premier Computer Institutes!</p>
          <h3 style="color: #004aad;">🔐 Your Login Credentials:</h3>
          <ul>
            <li><strong>Website:</strong> <a href="https://www.isdmnxt.in">www.isdmnxt.in</a></li>
            <li><strong>Username:</strong> ' . $data['email'] . '</li>
            <li><strong>Password:</strong> ' . $plain_password . '</li>
          </ul>
          <p>You can now manage your institute\'s profile, admissions, and course activities using your login.</p>
          <p style="margin-top: 20px;"><strong>Need Help?</strong><br>
          ✉️ info@isdmnext.in<br>
          📞 8320181598 / 8320876233</p>
          <p>We look forward to a strong and successful journey together!</p>
          <br>
          <p>Best regards,<br><strong>Team ISDM NxT</strong><br>
          <a href="https://www.isdmnxt.in">www.isdmnxt.in</a></p>
        </td>
      </tr>
    </table>';
}

private function send_email($to, $subject, $message)
{
    $this->load->library('email');

    $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);

    if ($this->email->send()) {
                 echo '<script>location.reload();</script>'; 
            }
    $this->email->clear(); // Clear config for next use

    return $sent;
}

function update_stuednt_basic_details()
{
    $student_id = $this->post('student_id');

    // Fetch current student data
    $student = $this->student_model->get_student_via_id($student_id)->row_array();

    if (!$student) {
        return $this->response(['status' => false, 'message' => 'Student not found.']);
    }

    // Prepare update data from POST
    $data = [
        'name'               => $this->post('name'),
        'gender'             => $this->post('gender'),
        'dob'                => $this->post('dob'),
        'status'             => $this->post('status'),
        'contact_number'     => $this->post('contact_number'),
        'contact_no_type'    => $this->post('contact_no_type'),
        'alternative_mobile' => $this->post('alternative_mobile'),
        'alt_mobile_type'    => $this->post('alt_mobile_type'),
        'email'              => $this->post('email'),
        'father_name'        => $this->post('father_name'),
        'mother_name'        => $this->post('mother_name'),
        'family_id'          => $this->post('family_id'),
        'address'            => $this->post('address'),
    ];

    $changed_fields = [];
    foreach ($data as $key => $value) {
        if (!isset($student[$key]) || $student[$key] != $value) {
            $changed_fields[] = $key;
        }
    }

    // Update the database
    $this->db->where('id', $student_id)->update('isdm_students', $data);

    // Email logic moved here
    $student = array_merge($student, $data); // merge for fresh values
    $this->send_publish_email($student, $changed_fields);

    $this->response('status', true);
    $this->response('student_data', $data);
    $this->response('message', 'Profile updated and email sent.');
}


private function send_publish_email($student, $fields_updated = [])
{
    if (empty($student) || empty($student['email'])) {
        log_message('error', 'Student not found or missing email');
        return false;
    }

    $this->load->library('email');
    // $this->email->initialize([
    //     'protocol'  => 'smtp',
    //     'smtp_host' => 'ssl://smtp.gmail.com',
    //     'smtp_port' => 465,
    //     'smtp_user' => 'isdmnxt@gmail.com',
    //     'smtp_pass' => 'zpeh ivui sqdt fvkz',
    //     'mailtype'  => 'html',
    //     'charset'   => 'utf-8',
    //     'newline'   => "\r\n"
    // ]);

    $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
    $this->email->to($student['email']);
    $this->email->subject('✅ Your Profile Has Been Updated');

    $logo_url = base_url('upload/logo.png'); // adjust path as needed

    $fields_html = !empty($fields_updated)
        ? "<ul style='padding-left:20px;'>" . implode('', array_map(fn($f) => "<li>" . ucwords(str_replace('_', ' ', $f)) . "</li>", $fields_updated)) . "</ul>"
        : "<p>(No specific changes detected)</p>";

    $message = '
    <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td style="text-align: center;">
                <img src="' . $logo_url . '" alt="ISDM NxT Logo" height="80px" style="margin-bottom: 10px;" />
                <h2 style="color: #004aad; margin: 0;">Profile Update Confirmation</h2>
            </td>
        </tr>
        <tr>
            <td style="background-color: #ffffff; padding: 20px; border-radius: 8px;">
                <p>Hello <strong>' . htmlspecialchars($student['name']) . '</strong>,</p>
                <p>Your profile has been <strong>successfully updated</strong> by your training institute.</p>
                <h3 style="color: #004aad; margin-bottom: 5px;">🔄 Updated Fields:</h3>
                ' . $fields_html . '
                <p><strong>Updated On:</strong> ' . date('d M Y, h:i A') . '</p>
                <br>
                <p>If you did not request these changes or notice incorrect information, please contact your center immediately.</p>
                <p style="margin-top: 20px;"><strong>Need Help?</strong><br>
                ✉ support@isdmnext.in<br>
                📞 +91-8320181598 | +91-8320876233</p>
                <br>
                <p>Regards,<br><strong>ISDM NxT</strong><br>
                <a href="https://www.isdmnext.in" style="color: #007bff;">www.isdmnext.in</a></p>
            </td>
        </tr>
    </table>';

    $this->email->message($message);

    if (!$this->email->send()) {
        log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
        return false;
    }

    return true;
}







    function student_login_form()
    {
        // sleep(5);
        $rollno = $this->post('roll_no');
        $password = $this->post('password');
        if ($this->validation('student_login_form')) {

            $this->response($this->post());
            $get = $this->student_model->get_student_via_roll_no($rollno);
            if ($get->num_rows()) {
                $row = $get->row();
                if ($row->student_profile_status) {
                    if (!($stdPassword = $row->password)) {
                        $name = $row->student_name;
                        $dobYear = date('Y', strtotime($row->dob));
                        $stdPassword = strtoupper(substr($name, 0, 2) . $dobYear);
                        $stdPassword = sha1($stdPassword);
                    }
                    if ($stdPassword == sha1($password)) {
                        $this->session->set_userdata([
                            'student_login' => true,
                            'student_id' => $row->student_id
                        ]);
                        $this->response('student_name', $row->student_name);
                        $this->response('status', true);
                    } else
                        $this->response('error', alert('Wrong Password.', 'danger'));
                } else {
                    $this->response('error', alert('Your Account is In-active. Please Contact Your Admin', 'danger'));
                }
            } else {
                $this->response('error', alert('Wrong Roll Number or Password.', 'danger'));
            }
        }
    }

    function update_stuednt_password()
    {
        if ($this->validation('change_password')) {
            $this->db->update('students', ['password' => sha1($this->post('password'))], [
                'id' => $this->post('student_id')
            ]);
            $this->db->update('student_change', ['status' => 4], ['student_id' => $this->post('student_id')]);
            $this->session->unset_userdata('student_login');
            $this->response('status', true);
        }
    }
    function admit_card()
    {
        if ($this->validation('roll_no')) {
            // $this->response($this->post());
            $get = $this->student_model->admit_card($this->post());
            if ($get->num_rows()) {
                $this->response('status', true);
                $this->response('url', base_url('admit-card/' . $this->encode($get->row('admit_card_id'))));
            } else
                $this->response('error', 'Admit Card not found..');
        }
    }
    function certificate()
    {
        if ($this->validation('roll_no')) {
            // $this->response($this->post());
            $get = $this->student_model->student_certificates($this->post());
            if ($get->num_rows()) {
                $this->response('status', true);
                $this->response('url', base_url('certificate/' . $this->encode($get->row('certiticate_id'))));
            } else
                $this->response('error', 'Certificate not found..');
        }
    }
    function update_student_batch_and_roll_no()
    {
        $get = $this->db
            ->where('id!=', $this->post('student_id'))
            ->where('roll_no', $this->post("roll_no"))
            ->get('students');
        if ($get->num_rows()) {
            $this->response('error', 'This Roll Number already exists.');
        } else {
            $this->db->where('id', $this->post('student_id'))
                ->update('students', [
                    'roll_no' => $this->post('roll_no'),
                    'batch_id' => $this->post('batch_id'),
                    'course_id' => $this->post('course_id'),
                    'admission_date' => $this->post('admission_date')
                ]);
            $this->db->update('student_change', ['status' => 4], ['student_id' => $this->post('student_id')]);
            $this->response("status", true);
        }
    }
    function edit_fee_record()
    {
        $get = $this->db->select('        
                s.id,
                s.fees_type,
                s.fees_period,
                s.payment_type,
                s.total_amount,
                s.paid_amount,
                s.pending_amount,
                c.id as trans_id,
                c.payment_id,
                c.payment_date,
                c.amount,
                c.description'
            )
            ->from('student_fees as s')
            ->join("student_fees_trans as c", "s.id = c.student_fees_id", "left")
            ->where('s.id', $this->post('fee_id'))->get();
        $data = $get->result_array();
        $this->set_data('data', $get->result_array());
        $this->response('html', $this->template('edit-fee-record'));
    }

    function delete_fee_record()
    {
        $this->response('status', $this->db->where('id', $this->post('fee_id'))->delete('student_fees'));
    }

    function update_fee_record()
    {
        if(intval($this->post('total_amount')) < intval($this->post('paid_amount'))){
           $this->response('error', 'Fee Amount should be greater then or equalto Paid Amount.');
        } else {

             $pending_amount = intval($this->post('total_amount')) - intval($this->post('paid_amount'));
             $data = [
                'fees_type' => $this->post('fees_type'),
                'fees_period' => $this->post('fees_period'),
                'payment_type' => $this->post('payment_type'),
                'total_amount' => $this->post('total_amount'),
                'pending_amount' => $pending_amount
            ];
            $this->db->where('id', $this->post('id'))->update('student_fees', $data);
            //$this->db->where('student_fees_id', $this->post('id'))->delete('student_fees_trans');
            $this->response('status', true);
            $this->response('html', "Record Updated Successfully...");
        }
    }

    function print_fee_record()
    {
        $this->init_setting();
        $record = $this->student_model->get_fee_transcations($this->post());
        /*echo "<pre>";
        print_r($record->row_array());*/
        $this->set_data($record->row_array());
        $this->set_data('record', $record->result_array());
        $this->response('html', $this->template('print-fee-record'));
    }

    function add_installment()
    {
        $get = $this->db->where('id', $this->post('main_fee_id'))->get('student_fees');
        $data = $get->row_array();
        $this->set_data($data);
        $this->response('html', $this->template('add-installment'));
    }

    function edit_installment_record()
    {
        $get = $this->db->select('        
                s.id,
                s.fees_type,
                s.fees_period,
                s.payment_type,
                s.total_amount,
                s.paid_amount,
                s.pending_amount,
                c.id as trans_id,
                c.payment_id,
                c.payment_date,
                c.amount,
                c.description'
            )
            ->from('student_fees_trans as c')
            ->join("student_fees as s", "s.id = c.student_fees_id", "left")
            ->where('c.id', $this->post('fee_id'))->get();
        $data = $get->row_array();

        $this->set_data('data', $data);
        $this->response('html', $this->template('edit-installment-record'));
    }

    function delete_installment_record()
    {
        $get = $this->db->select('        
                s.id,
                s.fees_type,
                s.fees_period,
                s.payment_type,
                s.total_amount,
                s.paid_amount,
                s.pending_amount,
                c.id as trans_id,
                c.payment_id,
                c.payment_date,
                c.amount,
                c.description'
            )
            ->from('student_fees_trans as c')
            ->join("student_fees as s", "s.id = c.student_fees_id", "left")
            ->where('c.id', $this->post('fee_id'))->get();
        $data = $get->row_array();

        $paid_amount = intval($data['paid_amount']) - intval($data['amount']);

        $pending_amount = intval($data['total_amount']) - intval($paid_amount);
        $mdata = [
            'paid_amount' => $paid_amount,
            'pending_amount' => $pending_amount
        ];
        $this->db->where('id', $data['id'])->update('student_fees', $mdata);

        $this->response('status', $this->db->where('id', $data['trans_id'])->delete('student_fees_trans'));
    }

    function update_installment_record()
    {
        $data = [
            'payment_date' => $this->post('payment_date'),
            'amount' => $this->post('amount'),
            'description' => $this->post('description')
        ];
        $this->db->where('id', $this->post('student_trans_id'))->update('student_fees_trans', $data);

        $paid_amount = intval($this->post('paid_amount')) + intval($this->post('amount'));

        $pending_amount = intval($this->post('total_amount')) - intval($paid_amount);
        $mdata = [
            'paid_amount' => $paid_amount,
            'pending_amount' => $pending_amount
        ];
       $this->db->where('id', $this->post('student_fees_id'))->update('student_fees', $mdata);
        $this->response('status', true);
        $this->response('html', "Installment Updated Successfully...");
    }

    function save_installment()
    {
        $paid_amount        = intval($this->post('paid_amount')) + intval($this->post('amount'));
        $pending_amount     = intval($this->post('pending_amount')) - intval($this->post('amount'));

        if(intval($paid_amount) > intval($this->post('total_amount'))){
            $this->response('status', false);
            $this->response('error', "Amount should be less then pending amount.");
        } else {
            $data = [
                'paid_amount' => $paid_amount,
                'pending_amount' => $pending_amount
            ];
            $this->db->where('id', $this->post('student_fees_id'))->update('student_fees', $data);

            $payment_id = date('hdYHis').rand(100,999);

            $where = [
                'student_fees_id' =>  $this->post('student_fees_id'),
                'payment_date' => $this->post('payment_date'),
                'amount' => $this->post('amount'),
                'description' => $this->post('description')
            ];

            $get = $this->db->where($where)->get('student_fees_trans')->num_rows();
            if($get == 0){
                $transData = [
                    'student_fees_id' =>  $this->post('student_fees_id'),
                    'payment_id' => $payment_id,
                    'payment_date' => $this->post('payment_date'),
                    'amount' => $this->post('amount'),
                    'description' => $this->post('description'),
                    'added_by' => $this->ki_theme->loginUser()
                ];

                $this->db->insert('student_fees_trans', $transData);
            }
            
            $this->response('status', true);
        }
    }

    function list_paper()
    {
        $data = $this->exam_model->student_exam($this->post());
        $row = $data->row();
        //pre($row);
        $option_suffle = ($row->option_shuffle == 1) ? 'Y' : 'N';
        $this->set_data('option_suffle', $option_suffle);
        $this->set_data($data->row_array());
        if($row->random_question == '1'){
            $this->set_data('questions', $this->exam_model->get_shuffled_questions($row->question_paper_id));
        } else {
            $this->set_data('questions', $this->exam_model->get_nonshuffled_questions($row->question_paper_id));
        }

        $examTitle = "<h3>Subject: ".$row->subject_name."</h3><h4>Total Marks: ".$row->paper_total_marks."</h4><h4>Duration: ".$row->paper_duration." Minuts</h4><h4 id='remainingTime' class='text-danger'>Remaining Time: ".$row->paper_duration." Minuts</h4>";

        $this->response([
            'title' => $examTitle,
            'negativemarking' => ($row->negative_marking == 1) ? 'Y' : 'N',
            'content' => $this->template('list-papers-questions')
        ]);
    }
    function submit_exam()
    {
        $mydata = $this->post('submitList') ? $this->post('submitList') : [];
        $data = [
            'attempt_time' => date('Y-m-d H:i:s'),
            'percentage' => $this->post('percentage'),
            'data' => json_encode($mydata),
            'ttl_right_answers' => $this->post('ttl_right_answers'),
            'student_total_marks' => $this->post('ttl_right_answers_marks'),
            'status' => 2
        ];

        // $this->response($data);
        $this->db->where('id', $this->post('student_exam_id'))
            ->update('exams_master_trans', $data);
        $this->response('status', 'OK');

    }

    function update_center_docs()
    {
        $this->db->where('id', $this->post('center_id'))
            ->update('centers', [
                $this->post('name') => $this->file_up('file')
            ]);
        $this->response('query', $this->db->last_query());
        $this->response('status', true);
    }
    function update_student_docs()
    {
        $data = [];
        if ($this->post('name') == 'resume') {
            $data['resume']         = $this->file_up('file');
            $data['student_id']     = $this->post('student_id');
            $get                    = $this->db->select('id')->where('student_id', $this->post('student_id'))->get('student_profile');
            if(empty($get->row('id'))){
                $this->db->insert('student_profile', $data);
            } else {
                $pro_id             = $get->row('id');
                $this->db->update('student_profile', $data, ['id' => $pro_id]);
            }
        } else {
            if ($this->post('name') == 'adhar_card') {
                $data['adhar_front'] = $this->file_up('file');
            } else {
                $get = $this->db->select('upload_docs')->where('id', $this->post('student_id'))->get('students');
                $uploads_docs = $get->row('upload_docs');
                $uploads_docs = $uploads_docs == NULL ? [] : json_decode($uploads_docs, true);
                $uploads_docs[$this->post('name')] = $this->file_up('file');
                $data['upload_docs'] = json_encode($uploads_docs);
            }
            // $this->response('data',$data);
            $this->db->where('id', $this->post('student_id'))->update('students', $data);
            $this->db->update('student_change', ['status' => 4], ['student_id' => $this->post('student_id')]);
        }
        
        $this->response('status', true);
    }
    function list_syllabus()
    {
        $this->response('data', $this->db->order_by('id', 'DESC')->get('syllabus')->result_array());
    }
    function verify_student_phone_for_reset_password()
    {
        $get = $this->student_model->get_student([
            'contact_number' => $this->post('phoneNumber')
        ]);
        if ($get->num_rows()) {
            $row = $get->row();
            $templates = $this->load->config('api/sms', true);
            // pre($templates);
            if (isset($templates['forgot_password'])) {
                $message = $templates['forgot_password']['content'];
                $otp = random_int(100000, 999999);
                $this->session->set_userdata('forgot_password_otp', $otp);
                $message = str_replace('{#var#}', $otp, $message);
                // echo $message;
                $this->load->module('api/whatsapp');
                $res = $this->whatsapp->send('91' . $row->contact_number, $message);
                $this->response(json_decode($res, true));
                // $this->response(['status' => 'success','otp' => $otp]);
            }
        }
    }
    function generate_new_password_link()
    {
        if ($this->session->has_userdata('forgot_password_otp')) {
            if ($this->post('otp') == $this->session->userdata('forgot_password_otp')) {
                $get = $this->student_model->get_student([
                    'contact_number' => $this->post('phoneNumber')
                ]);
                if ($get->num_rows()) {
                    $row = $get->row();
                    // $this->session->set_userdata('student_id',$row->id);
                    $this->session->unset_userdata('forgot_password_otp');
                    $this->load->library('common/token');
                    $this->response([
                        'status' => 'success',
                        'url' => base_url('student/create-new-password/' . $this->token->encode([
                            'student_id' => $row->student_id
                        ]))
                    ]);
                }
            }
        }
        // $this->response($this->session->userdata());
    }

    function verify_student_phone()
    {
        $get = $this->student_model->get_student([
            'contact_number' => $this->post('phoneNumber')
        ]);
        if ($get->num_rows()) {
            $row = $get->row();
            $templates = $this->load->config('api/sms', true);
            // pre($templates);
            if (isset($templates['login_with_otp'])) {
                $message = $templates['login_with_otp']['content'];
                $otp = random_int(100000, 999999);
                $this->session->set_userdata('login_otp', $otp);
                $message = str_replace('{#var#}', $otp, $message);
                // echo $message;
                $this->load->module('api/whatsapp');
                $res = $this->whatsapp->send('91' . $row->contact_number, $message);
                $this->response(json_decode($res, true));
                // $this->response(['status' => 'success']);
            }
        }
    }
    function verify_login_otp()
    {
        if ($this->session->has_userdata('login_otp')) {
            if ($this->post('otp') == $this->session->userdata('login_otp')) {
                $get = $this->student_model->get_student([
                    'contact_number' => $this->post('phoneNumber')
                ]);
                if ($get->num_rows()) {
                    $row = $get->row();
                    // $this->session->set_userdata('student_id',$row->id);
                    $this->session->unset_userdata('login_otp');
                    $this->session->set_userdata([
                        'student_login' => true,
                        'student_id' => $row->student_id
                    ]);
                    $this->response(['status' => 'success']);
                }
            }
        }
        // $this->response($this->session->userdata());
    }
    function send_notification()
    {
        if($this->center_model->isAdmin()){
            if($this->post('receiver_user') == 'center'){
                if($this->post('center_type') == 'selected'){
                    $receiverArray = $this->db->where('id', $this->post('center_id'))->get('centers')->result();
                } else if($this->post('center_type') == 'all'){
                    $receiverArray = $this->db->where('type', 'center')->get('centers')->result();
                } else if($this->post('center_type') == '0' || $this->post('center_type') == '1'){
                    $receiverArray = $this->db->where('type', 'center')->where('isDeleted', $this->post("center_type"))->get('centers')->result();
                } 
            } else if($this->post('receiver_user') == 'student'){
                if($this->post('student_type') == 'selected'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.id', $this->post('student_id'))->get()->result();
                } else if($this->post('student_type') == 'all'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->get()->result();
                } else if($this->post('student_type') == '0' || $this->post('student_type') == '1' || $this->post('student_type') == '2'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.admission_status', $this->post("student_type"))->get()->result();
                } else if($this->post('student_type') == 'center'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $this->post("center_id"))->get()->result();
                } else if($this->post('student_type') == 'course'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.course_id', $this->post("course_id"))->get()->result();
                } else if($this->post('student_type') == 'batch'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.batch_id', $this->post("batch_id"))->get()->result();
                }
            } else if($this->post('receiver_user') == 'staff'){
                if($this->post('staff_type') == 'selected'){
                    $receiverArray = $this->db->where('id', 'staff_id')->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'all'){
                    $receiverArray = $this->db->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == '0' || $this->post('staff_type') == '1'){
                    $receiverArray =$this->db->where('isDeleted', $this->post("staff_type"))->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'center'){
                    $receiverArray = $this->db->where('parent_center_id', $this->post("center_id"))->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'role'){
                    $receiverArray = $this->db->where('role', $this->post("role"))->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'center_role'){
                    $receiverArray = $this->db->where('parent_center_id', $this->post("center_id"))->where('role', $this->post("role"))->where('type', 'staff')->get('centers')->result();
                }
            }
        }

        if($this->center_model->isCenter()){
            $center_id = 0;
            if ($this->center_model->isCenter()) {
                $center_id = $this->center_model->loginId();
            }
            if($this->post('receiver_user') == 'student'){
                if($this->post('student_type') == 'selected'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->where('s.id', $this->post('student_id'))->get()->result();
                } else if($this->post('student_type') == 'all'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->get()->result();
                } else if($this->post('student_type') == '0' || $this->post('student_type') == '1' || $this->post('student_type') == '2'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->where('s.admission_status', $this->post("student_type"))->get()->result();
                } else if($this->post('student_type') == 'course'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->where('s.course_id', $this->post("course_id"))->get()->result();
                } else if($this->post('student_type') == 'batch'){
                    $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->where('s.batch_id', $this->post("batch_id"))->get()->result();
                }
            } else if($this->post('receiver_user') == 'staff'){
                if($this->post('staff_type') == 'selected'){
                    $receiverArray = $this->db->where('parent_center_id', $center_id)->where('id', 'staff_id')->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'all'){
                    $receiverArray = $this->db->where('parent_center_id', $center_id)->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == '0' || $this->post('staff_type') == '1'){
                    $receiverArray =$this->db->where('parent_center_id', $center_id)->where('isDeleted', $this->post("staff_type"))->where('type', 'staff')->get('centers')->result();
                } else if($this->post('staff_type') == 'role'){
                    $receiverArray = $this->db->where('parent_center_id', $center_id)->where('role', $this->post("role"))->where('type', 'staff')->get('centers')->result();
                } 
            }
        }

        foreach($receiverArray as $key => $receiver){
            $data   = [
                'send_time' => time(),
                'notify_type' => $this->post('notify_type'),
                'title' => $this->post('title'),
                'content' => $_POST['message'],
                'receiver_user' => $this->post('receiver_user'),
                'receiver_id' => $receiver->id,
                'seen' => 0,
                'send_by' => $this->session->userdata('admin_type'),
                'sender_id' => $this->session->userdata('admin_id')
            ];
            $this->db->insert('manual_notifications', $data);
        }

        $this->response('status', true);
    }
    function view_notification_dash()
    {
        $html = '';
        $get = $this->db->get_where('manual_notifications', ['id' => $this->post('id')]);
        if ($get->num_rows()) {
            $row = $get->row();
            $this->db->set('seen', 'seen + 1', false)->where('id', $row->id)->update('manual_notifications');

            $html .= '<div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title">' . $row->title . '</h3>
                        </div>
                        <div class="card-body">
                            ' . $row->content . '
                        </div>    
                        <div class="card-footer">
                            <div class="d-fl    ex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5">
                                <div class="d-flex align-items-center me-3">
                                    
                                </div>
                                <div class="d-flex align-items-center text-primary">
                                    <i class="ki-duotone ki-time text-primary fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i> &nbsp;' . date('d-m-Y h:i A', $row->send_time) . '
                                </div>
                            </div>
                        </div>
            </div>';
            $this->response('status',true);
        } else
            $html .= alert('Something went wrong, can\'t view this message.', 'danger');
        $this->response('html', $html);
    }
    function view_notification()
    {
        $html = '';
        $get = $this->db->get_where('manual_notifications', ['id' => $this->post('id')]);
        if ($get->num_rows()) {
            $row = $get->row();
            if ( ($this->student_model->isStudent() && $row->receiver_user == 'student'))
                $this->db->set('seen', 'seen + 1', false)->where('id', $row->id)->update('manual_notifications');

            $html .= '<div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title">' . $row->title . '</h3>
                        </div>
                        <div class="card-body">
                            ' . $row->content . '
                        </div>    
                        <div class="card-footer">
                            <div class="d-fl    ex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5">
                                <div class="d-flex align-items-center me-3">
                                    
                                </div>
                                <div class="d-flex align-items-center text-primary">
                                    <i class="ki-duotone ki-time text-primary fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i> &nbsp;' . date('d-m-Y h:i A', $row->send_time) . '
                                </div>
                            </div>
                        </div>
            </div>';
            $this->response('status',true);
        } else
            $html .= alert('Something went wrong, can\'t view this message.', 'danger');
        $this->response('html', $html);
    }

    function upload_gallery_image()
    {
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // 2 MB max file size
        if ($this->center_model->isCenter()) {
            $center_id = $this->center_model->loginId();
        } else {
            $center_id = 1;
        }
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
            // File uploaded successfully
            $this->db->insert('center_gallery_images', ['image' => $this->upload->data('file_name'),'center_id' => $center_id]);
            $this->response([
                'success' => true,
                'filename' => $this->upload->data('file_name'),
                'url' => base_url('upload/' . $this->upload->data('file_name')),
            ]);
        } else {
            http_response_code(500);
            // Handle upload failure
            $this->response('error', $this->upload->display_errors('', ''));
        }
    }

    function list_gallery_images()
    {
        if ($this->center_model->isCenter()) {
            $center_id = $this->center_model->loginId();
        } else {
            $center_id = 1;
        }
        $this->response('data', $this->db->where(['center_id' => $center_id])->get('center_gallery_images')->result());
    }
    function delete_gallery_image($id)
    {
        $this->db->where(['id' => $id])->delete('center_gallery_images');
        $this->response('status', true);
    }

    function calculateAttendancePercentages($totalClasses, $attendedClasses) {
        // Check to avoid division by zero
        if ($totalClasses == 0) {
            return 0;
        }
        
        // Calculate the attendance percentage
        $percentage = ($attendedClasses / $totalClasses) * 100;
        
        // Round off the percentage to two decimal places
        return round($percentage, 2);
    }

    function reportstudentforattendance()
    {
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
                if($this->post('student_id') == $row->student_id){
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
                $html .= '<td>'. $this->calculateAttendancePercentages($totalDays,$totalPresent) .'% </td>';
                $html .= $htmlColumn;
                $html .= '</tr>';
                }
            }
            $html .= '</tbody></table></div></div>';
            $this->response('html', $html);
        } else {
            $this->response('html', 'Students are not found.');
        }
    }

    function list_master_class(){

        //pre($this->student_model->isStudent());
        $studentArray = [];
        if(!empty($this->session->userdata('student_id'))){
            $studentArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id,s.batch_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.id', $this->session->userdata('student_id'))->get()->result_array();
            //$this->response('studentArray',$studentArray);
        }

        $this->db->select(' 
                ce.institute_name as center_name,
                c.course_name,
                c.duration,
                c.duration_type,
                b.batch_name,
                ss.subject_name,
                ces.name as staff_name,
                s.id,
                s.title,
                s.description,
                s.type,
                s.plan_date,
                s.notes,
                cpr.rating,
                cpr.description as r_description'
        )
        ->from('class_plan as s')
        ->join('centers as ce', 'ce.id = s.center_id', 'left')
        ->join("course as c", "s.course_id = c.id ", 'left')
        ->join('batch as b', "b.id = s.batch_id", 'left')
        ->join('subject_master as ss', "ss.id = s.subject_id", 'left')
        ->join('centers as ces', 'ces.id = s.staff_id','left')
        ->join('class_plan_rating as cpr', 'cpr.class_plan_id = s.id AND cpr.student_id = '.$this->session->userdata('student_id'),'left')
        ->where('s.isDeleted', 0);

        if(!empty($studentArray[0]['id'])){
            $this->db->where('s.center_id',$studentArray[0]['center_id']);
            $this->db->where('s.course_id',$studentArray[0]['course_id']);
            $this->db->where('s.batch_id',$studentArray[0]['batch_id']);
        }

        $list = $this->db->get();
        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function class_feedback()
    {
        $data = $this->post();
        $get = $this->db
            ->where('student_id=', $this->session->userdata('student_id'))
            ->where('class_plan_id', $this->post("class_plan_id"))
            ->get('class_plan_rating');
        if ($get->num_rows()) {
            $this->response('error', 'Already exists.');
            $this->response('status', false);
        } else {
            $data['student_id'] = $this->session->userdata('student_id');
            $this->db->insert('class_plan_rating', $data);
            $this->response('status', true);
        }
        
        
        $this->response('status', true);
    }

    function add_downloads(){
        $data = $this->post();
        $download_id = $data['download_id'];
        $upload_files = $data['upload_files'];
        unset($data['download_id']);
        unset($data['list-images_length']);
        unset($data['upload_files']);

        if(!empty($download_id)){

            $this->db->update('downloads',$data,['id' => $download_id]);
            if(!empty($upload_files)){
                $upload_filess = explode("##",$upload_files);
                foreach($upload_filess as $key => $val){
                    if(trim($val) != ''){

                        $extention = explode(".",$val);
                        $dataa = [];
                        $dataa['download_id'] = $download_id;
                        $dataa['file'] = $val;
                        $dataa['extention'] = $extention[1];
                        $this->db->insert('downloads_files', $dataa);
                    }
                    
                }
            }

        } else {
            $data['status'] = 1;
            $this->db->insert('downloads', $data);
            $download_id = $this->db->insert_id();

            if(!empty($upload_files)){
                $upload_filess = explode("##",$upload_files);
                $this->db->where(['download_id' => $download_id])->delete('downloads_files');
                foreach($upload_filess as $key => $val){
                    if(trim($val) != ''){

                        $extention = explode(".",$val);
                        $dataa = [];
                        $dataa['download_id'] = $download_id;
                        $dataa['file'] = $val;
                        $dataa['extention'] = $extention[1];
                        $this->db->insert('downloads_files', $dataa);
                    }
                    
                }
            }
        }

        $this->response('status', true);
    }

    function list_downloads()
    {
        $download_for = '';
        if ($this->center_model->isCenter()) {
            $download_for = 'center';
        } else if ($this->student_model->isStudent()) {
            $download_for = 'student';
        } else if ($this->center_model->isMaster()) {
            $download_for = 'center';
        }

        if($download_for == ""){
         $this->response('data', $this->db->select("d.*,(SELECT GROUP_CONCAT(file SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS concatenated_files,(SELECT GROUP_CONCAT(extention SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS files_extention")->from('downloads as d')->where('d.status', 1)->order_by('d.id','DESC')->get()->result());
        } else {
            $this->response('data', $this->db->select("d.*,(SELECT GROUP_CONCAT(file SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS concatenated_files,(SELECT GROUP_CONCAT(extention SEPARATOR '##') FROM isdm_downloads_files where download_id = d.id) AS files_extention")->from('downloads as d')->where('d.download_for', $download_for)->where('d.status', 1)->order_by('d.id','DESC')->get()->result());
        }
    }

    function list_gallery_downloads()
    {
        $download_id = $_GET['download_id'];
        if(!empty($download_id))
        $this->response('data', $this->db->where(['download_id' => $download_id])->get('downloads_files')->result());
    }

    function upload_downloads_file()
    {
        $config['upload_path']      = './upload/downloads/';
        $config['allowed_types']    = 'gif|jpg|jpeg|png|pdf|doc|xls|docx|mp4|xlsx';
        $config['max_size']         = 2048; // 2 MB max file size
        
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
            $this->response('status', true);
            $this->response('filename', $this->upload->data('file_name'));
            $this->response('url', base_url('upload/downloads/' . $this->upload->data('file_name')));
            
        } else {
            http_response_code(500);
            // Handle upload failure
            $this->response('error', $this->upload->display_errors('', ''));
        }
    }

    function delete_downloads_file($id)
    {
        $this->db->where(['id' => $id])->delete('downloads_files');
        $this->response('status', true);
    }

    function delete_downloads($id)
    {
        $this->db->where(['id' => $id])->delete('downloads');
        $this->db->where(['download_id' => $id])->delete('downloads_files');
        $this->response('status', true);
    }

    function set_resume_data()
    {
        $data                           = [];
        $data['resume_headline']        = $this->post('resume_headline');
        $data['key_skills']             = json_encode($this->post('key_skills'));
        $data['industries']             = json_encode($this->post('industries'));
        $data['pan_languages']             = json_encode($this->post('pan_languages'));
        $data['profile_summary']        = $this->post('profile_summary');
        $data['experience']             = $this->post('experience');
        $data['fluancy_in_english']             = $this->post('fluancy_in_english');
        $data['student_id']             = $this->post('student_id');
        $get                    = $this->db->select('id')->where('student_id', $this->post('student_id'))->get('student_profile');
        if(empty($get->row('id'))){
            $this->db->insert('student_profile', $data);
        } else {
            $pro_id             = $get->row('id');
            $this->db->update('student_profile', $data, ['id' => $pro_id]);
        }
        $this->response('status', true);
    }

    function send_interview_request()
{
    $data = $this->post();
    $id = $data['id'];
    unset($data['id']);

    if ($id && $data['student_status'] == 'approved') {

        // ✅ Get full details including student's center
        $job_list = $this->db->select('
                sir.*, j.job_title, j.work_location,
                s.name as student_name, s.email as student_email, s.contact_number, s.center_id as student_center_id,
                sc.institute_name as student_center_name,
                c.institute_name, c.name as center_name, c.email as center_email, c.wallet,
                m.interview_charges
            ')
            ->from('send_interview_request as sir')
            ->join('jobs as j', 'j.id = sir.job_id', 'left')
            ->join('isdm_students as s', 's.id = sir.student_id', 'left')
            ->join('centers as sc', 'sc.id = s.center_id', 'left') // student's center
            ->join('centers as c', 'c.id = sir.employer_id', 'left') // employer center
            ->join('emp_packages_trans as e', "e.employer_id = sir.employer_id and e.status = '1'", 'left')
            ->join('emp_packages as m', 'm.id = e.package_id', 'left')
            ->where('sir.id', $id)
            ->get()
            ->result_array();

        if (empty($job_list)) {
            $this->response('status', false);
            $this->response('html', 'Interview request not found.');
            return;
        }

        $job = $job_list[0];
        $close_balance = $job['wallet'];
        $deduction_amount = $job['interview_charges'];

        if (intval($close_balance) == 0) {
            $this->response('html', "Please recharge your wallet ₹$deduction_amount.");
            return;
        }

        if (intval($deduction_amount) == 0) {
            $this->response('html', 'Interview charges are not configured.');
            return;
        }

        $close_balance -= $deduction_amount;
        if ($close_balance < 0) {
            $this->response('html', "Insufficient wallet balance. Minimum ₹$deduction_amount required.");
            return;
        }

        // ✅ Wallet transaction
        $txn_data = [
            'center_id'     => $job['employer_id'],
            'amount'        => $deduction_amount,
            'o_balance'     => $job['wallet'],
            'c_balance'     => $close_balance,
            'type'          => 'send interview request',
            'description'   => 'Interview Request Accepted By Student',
            'type_id'       => $job['student_id'],
            'added_by'      => 'center',
            'order_id'      => strtolower(generateCouponCode(12)),
            'status'        => 1,
            'wallet_status' => 'debit'
        ];
        $this->db->insert('wallet_transcations', $txn_data);

        $this->center_model->update_wallet($job['employer_id'], $close_balance);
        $this->db->update('send_interview_request', ['center_status' => 'approved', 'student_status' => 'approved'], ['id' => $id]);
        $logo_url = base_url('upload/logo.png');

        // ✅ Email content
        $subject = "🎉 Student Has Accepted Your Request – ISDM NxT";
        $message = '
           <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
              <tr>
                <td style="text-align: center;">
                  <img src="' . $logo_url . '" alt="ISDM NxT Logo" style="height: 60px;" />
                  <h2 style="color: #004aad;">🎉 Great News! Student Accepted Your Job Offer</h2>
                </td>
              </tr>
              <tr>
                <td style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
                  <p>Hello <strong>' . $job['center_name'] . '</strong>,</p>
                  <p>We are pleased to inform you that <strong>' . $job['student_name'] . '</strong> has accepted your request/offer through the ISDM NxT Portal! 🚀</p>
            
                  <h3 style="color: #004aad;">👤 Student Details:</h3>
                  <ul>
                    <li><strong>Name:</strong> ' . $job['student_name'] . '</li>
                    <li><strong>Registered Institute:</strong> ' . $job['student_center_name'] . '</li>
                    <li><strong>Contact Email:</strong> ' . $job['student_email'] . '</li>
                    <li><strong>Phone Number:</strong> ' . $job['contact_number'] . '</li>
                  </ul>
            
                  <h3 style="color: #004aad;">📄 Job Details:</h3>
                  <ul>
                    <li><strong>Job Title:</strong> ' . $job['job_title'] . '</li>
                    <li><strong>Location:</strong> ' . $job['work_location'] . '</li>
                  </ul>
            
                  <h3 style="color: #004aad;">🔗 Next Steps:</h3>
                  <p>You can now schedule interviews, discussions, or onboarding procedures directly with the student.</p>
                  <p>Manage everything securely via your ISDM NxT Corporate Portal:</p>
                  <p><a href="https://isdmnext.in/corporate-login" style="background: #006699; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Login to Portal</a></p>
            
                  <p style="margin-top: 20px;"><strong>Need Help?</strong><br>
                  ✉️ info@isdmnext.in<br>
                  📞 8320181598 / 8320876233</p>
            
                  <p>Thank you for trusting ISDM NxT to support your recruitment needs! 🎯</p>
                  <br>
                  <p>Best regards,<br><strong>Team ISDM NxT</strong><br>
                  <a href="https://www.isdmnext.in">www.isdmnext.in</a></p>
                </td>
              </tr>
            </table>';

        // ✅ Send email
        $this->load->library('email');
        $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
        $this->email->to($job['center_email']);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype('html');
         if ($this->email->send()) {
                 echo '<script>location.reload();</script>'; 
            }

    } else {
        // Update for other status like reject
        $this->db->update('send_interview_request', $data, ['id' => $id]);
    }

    $this->response('status', true);
}



    function received_interview_request(){
        $data = $this->post();
        if(!empty($data['job_id']) && !empty($data['student_id'])){
            $job      = $this->db->select('*')->from('jobs')->where('id',$data['job_id'])->get()->result_array();
            $student  = $this->db->select('*')->from('students')->where('id',$data['student_id'])->get()->result_array();

            $data['employer_id']        = $job[0]['employer_id'];
            $data['center_id']          = $student[0]['center_id'];
            $this->db->insert('received_interview_request', $data);
            $this->response('status', true);
        } else {
            $this->response('status', false);
        }
    }

    function get_job_roles_for_industries() {
        $industry_ids = $this->input->post('industry_ids');
        $this->load->model('Employer_model');
        $roles = $this->Employer_model->get_job_roles_by_industries($industry_ids);
    
        header('Content-Type: application/json');
        echo json_encode([
            'status' => true,
            'ids_received' => $industry_ids,
            'roles_count' => count($roles),
            'roles' => $roles
        ]);
    }

    public function get_skills_for_roles() {
        $role_ids = $this->input->post('role_ids');
        $this->load->model('Employer_model');
        $skills = $this->Employer_model->get_skills_by_roles($role_ids);
    
        echo json_encode(['status' => true, 'skills' => $skills]);
    }
    
  public function employer_mgmt()
{
    if ($this->form_validation->run('add_employer')) {
        $data           = $this->post();
        $email          = $data['email_id'];
        $website        = $data['website'];
        $about_company  = $data['about_company'];
        $password_raw   = $data['password']; // For welcome email

        unset($data['email_id'], $data['website'], $data['about_company']);

        $data['status']     = 1;
        $data['added_by']   = 'admin';
        $data['email']      = $email;
        $data['password']   = sha1($password_raw);
        $data['image']      = $this->file_up('image');
        $data['signature']  = $this->file_up('signature');
        $data['logo']       = $this->file_up('logo');

        $status = $this->db->insert('centers', $data);
        $employer_id = $this->db->insert_id();

        if ($employer_id) {
            $this->db->insert('employer_info', [
                'employer_id'   => $employer_id,
                'about_company' => $about_company,
                'website'       => $website
            ]);

            // ✅ Load Email Library
            $this->load->library('email');

            // ✅ Location Details
            $state_name = $this->db->select('STATE_NAME')->where('STATE_ID', $data['state_id'])->get('state')->row('STATE_NAME');
            $city_name  = $this->db->select('DISTRICT_NAME')->where('DISTRICT_ID', $data['city_id'])->get('district')->row('DISTRICT_NAME');
            $registered_on = date('d-m-Y H:i:s');

            // ✅ ADMIN EMAIL
            $admin_subject = 'New Corporate Registration Received – ISDM NxT Portal Alert';
            $admin_body = "
                <p>Dear Admin Team,</p>
                <p>📢 A new corporate client has successfully completed their registration on the ISDM NxT Portal.</p>
                <h3>🏢 Corporate Details:</h3>
                <ul>
                    <li><strong>Company Name:</strong> {$data['institute_name']}</li>
                    <li><strong>Registered Email:</strong> {$email}</li>
                    <li><strong>Contact Person:</strong> {$data['name']}</li>
                    <li><strong>Phone Number:</strong> {$data['contact_number']}</li>
                    <li><strong>Registration Date:</strong> {$registered_on}</li>
                    <li><strong>Location:</strong> {$city_name}, {$state_name}</li>
                </ul>
                <h3>🛠 Next Actions:</h3>
                <ol>
                    <li>Verify corporate registration details (if required)</li>
                    <li>Ensure portal login access is active</li>
                    <li>Assist with onboarding</li>
                    <li>Monitor job post activity</li>
                </ol>
                <p>🔔 Corporate ID: <strong>{$employer_id}</strong></p>
                <p>📞 Support: info@isdmnext.in | 8320181598 / 8320876233</p>
                <p>Best regards,<br>ISDM NxT – Admin Team</p>
            ";

            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to('isdmnxt@gmail.com');
            $this->email->subject($admin_subject);
            $this->email->message($admin_body);
            $this->email->set_mailtype('html');
            $this->email->send();

            // ✅ CORPORATE EMAIL
            $corporate_subject = 'Welcome to ISDM NxT – Your Gateway to Skilled Talent! 🎓💼';
            $corporate_body = "
                <p>Dear {$data['name']},</p>
                <p>🎉 Welcome to the ISDM NxT Talent Network!</p>
                <p>Your corporate registration has been completed successfully! 🚀</p>

                <h3>🔑 Portal Login Details:</h3>
                <ul>
                    <li><strong>Portal:</strong> <a href='https://isdmnext.in/employer/index'>Click Here</a></li>
                    <li><strong>Username:</strong> {$email}</li>
                    <li><strong>Password:</strong> {$password_raw}</li>
                </ul>

                <h3>🎯 Key Features for You:</h3>
                <ul>
                    <li>Access Student Profiles</li>
                    <li>Post Job Openings</li>
                    <li>Schedule Campus Interviews</li>
                    <li>Track Applications</li>
                    <li>Skill Matchmaking</li>
                </ul>

                <p>🛠 Need Help Getting Started?</p>
                <p>✉ Email: info@isdmnext.in<br>
                   📞 Phone: 8320181598 / 8320876233<br>
                   🌐 Website: <a href='https://www.isdmnext.in'>www.isdmnext.in</a></p>

                <p>Thank you for partnering with ISDM NxT!</p>
                <p>Best regards,<br><strong>Team ISDM NxT</strong></p>
            ";

            $this->email->clear();
            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to($email);
            $this->email->subject($corporate_subject);
            $this->email->message($corporate_body);
            $this->email->set_mailtype('html');
            if($this->email->send())
            {
                echo"<script>location.reload();</script>";
            }
            
        }

        $this->response('status', true);
    } else {
        $this->response('html', $this->errors());
    }
}
    
    
}
?>