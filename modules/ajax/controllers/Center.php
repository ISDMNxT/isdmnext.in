<?php
class Center extends Ajax_Controller
{
    function delete_docs(){
        if(file_exists('upload/'.$this->post('file')))
            @unlink('upload/'.$this->post('file'));
        $this->db->where('id',json_decode($this->post('id')))
                ->set($this->post('field'),null)
                ->update('centers');
        $this->response('status',true);
    }
    public function add()
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


// private function send_center_emails($data, $plainPassword)
// {
//     $admin_email = 'keyurpatel3063@gmail.com';
//     $center_email = $data['email'];

//     $admin_subject = 'New Franchise Registration Notification – ISDM NxT';
//     $center_subject = 'Congratulations! Your Franchise Registration with ISDM NxT is Successful';

//     $admin_message = $this->compose_admin_email($data);
//     $center_message = $this->compose_center_email($data, $plainPassword);

//     $this->send_email($admin_email, $admin_subject, $admin_message);
//     $this->send_email($center_email, $center_subject, $center_message);
//     $this->send_email($center_email, $profile_subject, $profile_message); // ✅ third email

// }

private function send_center_emails($data, $plainPassword)
{
    $admin_email = 'keyurpatel3063@gmail.com';
    $center_email = $data['email'];

    $admin_subject = 'New Franchise Registration Notification – ISDM NxT';
    $center_subject = 'Congratulations! Your Franchise Registration with ISDM NxT is Successful';
    $profile_subject = 'Action Required: Complete Your Franchise Profile – ISDM NxT'; // ✅ define here

    $admin_message = $this->compose_admin_email($data);
    $center_message = $this->compose_center_email($data, $plainPassword);
    $profile_message = $this->compose_profile_completion_email($data);

    // Send all 3 emails
    $this->send_email($admin_email, $admin_subject, $admin_message);
    $this->send_email($center_email, $center_subject, $center_message);
    $this->send_email($center_email, $profile_subject, $profile_message); // ✅ now safe to use
}



private function compose_admin_email($data)
{
    $logo_url = base_url('upload/5a83cc0489_2.png');

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
    return '
    <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
      <tr>
        <td style="text-align: center;">
          <img src="upload\5a83cc0489_2.png" alt="ISDM NxT Logo" style="height: 60px;" />
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

private function compose_profile_completion_email($data)
{
    $logo_url = base_url('upload/5a83cc0489_2.png'); // optional, if you want to add logo

    return '
    <table style="width: 100%; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
      <tr>
        <td style="text-align: center;">
          <img src="' . $logo_url . '" alt="ISDM NxT Logo" style="height: 60px;" />
          <h2 style="color: #004aad;">Action Required: Complete Your Franchise Profile – ISDM NxT</h2>
        </td>
      </tr>
      <tr>
        <td style="background-color: #ffffff; padding: 20px; border-radius: 6px;">
          <p>Dear <strong>' . $data['institute_name'] . '</strong>,</p>
          <p>🎉 Welcome to ISDM NxT! 🎉</p>
          <p>Thank you for registering on our new portal — we’re thrilled to have you as part of the ISDM NxT family! 🚀</p>

          <h3>✅ Verification Update:</h3>
          <p>Your institute has been successfully verified by the ISDM NxT team. 🙌</p>

          <h3>📝 Next Steps – Complete Your Franchise Profile:</h3>
          <p>Kindly follow the steps below to complete your registration process:</p>

          <ul>
            <li>🔑 <strong>Login:</strong> <a href="https://isdmnxt.in/admin">https://isdmnxt.in/admin</a></li>
            <li>📁 <strong>Access Profile:</strong> Click on "PROFILE" in the menu</li>
            <li>📝 <strong>Update Required Details:</strong>
              <ul>
                <li>📸 Avatar: Upload your profile photo</li>
                <li>👋 Full Name</li>
                <li>📧 Email Address</li>
                <li>📞 Contact Number</li>
                <li>📍 Address</li>
                <li>🖋️ Signature</li>
                <li>📛 Institute Logo</li>
                <li>📸 Image Gallery</li>
              </ul>
            </li>
          </ul>

          <p>🙏 Your cooperation in completing your profile is highly appreciated!</p>

          <p><strong>📞 Need Help?</strong><br>
          ✉️ Email: info@isdmnext.in<br>
          📞 Phone: 8320181598 / 8320876233</p>

          <br>
          <p>Best regards,<br>
          <strong>Team ISDM NxT</strong><br>
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

    $sent = $this->email->send();
    $this->email->clear(); // Clear config for next use

    return $sent;
}


// private function send_center_emails($data, $plainPassword)
// {
//     $this->load->library('email');

//     $this->email->initialize([
//         'protocol' => 'smtp',
//         'smtp_host' => 'ssl://smtp.gmail.com',
//         'smtp_port' => 465,
//         'smtp_user' => 'isdmnxt@gmail.com',
//         'smtp_pass' => 'zpeh ivui sqdt fvkz',
//         'mailtype'  => 'html',
//         'charset'   => 'utf-8',
//         'newline'   => "\r\n"
//     ]);

//     $registrationDate = date('d-m-Y');

//     // ✉️ Admin Email
//     $admin_message = "
//         Dear ISDM NxT Admin,<br><br>
//         A new client has registered.<br><br>
//         📋 <b>Registration Details:</b><br>
//         Institute Name: {$data['institute_name']}<br>
//         Client Name: {$data['name']}<br>
//         Mobile Number: {$data['contact_number']}<br>
//         Email Address: {$data['email']}<br>
//         Location: {$data['center_full_address']}<br>
//         Registration Date: {$registrationDate}<br><br>
//         ➡️ Access Admin Portal: <a href='https://www.isdmnxt.in'>www.isdmnxt.in</a><br><br>
//         For queries, contact: info@isdmnext.in / 8320181598
//     ";

//     $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
//     $this->email->to('info@isdmnext.in');
//     $this->email->subject('New Franchise Registration Notification – ISDM NxT');
//     $this->email->message($admin_message);
//     $this->email->send();

//     // ✉️ Center Email
//     $center_message = "
//         Dear {$data['name']},<br><br>
//         Welcome to ISDM NxT!<br><br>
//         Your franchise registration is successful.<br><br>
//         ➡️ <b>Login Details:</b><br>
//         Website: <a href='https://www.isdmnxt.in'>www.isdmnxt.in</a><br>
//         Username: {$data['email']}<br>
//         Password: {$plainPassword}<br><br>
//         For support, contact info@isdmnext.in / 8320181598<br><br>
//         Regards,<br>Team ISDM NxT
//     ";

//     $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
//     $this->email->to($data['email']);
//     $this->email->subject('Congratulations! Your Franchise Registration with ISDM NxT is Successful');
//     $this->email->message($center_message);
//     $this->email->send();
// }

    function list_staff()
    {
        if($this->center_model->isCenter()) {
            $this->response('data', $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where('c.parent_center_id', $this->center_model->loginId())->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array());
        } else {
            
                if(!empty($_GET['center']) && $_GET['center'] != 'undefined'){
                   $this->response('data', $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where('c.parent_center_id', $_GET['center'])->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array());
                } else {
                    if($this->center_model->isMaster()){
                       $this->response('data', $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where_in('c.parent_center_id',$this->session->userdata('permission'))->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array());
                    } else {
                        $this->response('data', $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array());
                    }
                }
            
        }
    }
    function list_trainer()
    {
        $data = [];
        if(!empty($_GET['center']) && $_GET['center'] != 'undefined'){
           $data = $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where('c.parent_center_id', $_GET['center'])->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array();
        } else {
            if($this->center_model->isMaster()){
               $data = $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where_in('c.parent_center_id',$this->session->userdata('permission'))->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array();
            } else {
                $data = $this->db->select('c.*,em.institute_name as center_name')->from('centers as c')->where('c.type', 'staff')->where('c.role', 'Trainer')->where('c.isDeleted', 0)->join('centers as em', 'c.parent_center_id = em.id', 'left')->get()->result_array();
            }
        }
        $status = false;
        if(count($data) > 0){
            $status = true;
        }
        $this->response('data', $data);
        $this->response('status',$status);
    }
    public function add_staff()
{
    $data = $this->post();

    if (!empty($data['staff_id'])) {
        // Update existing staff
        $id = $data['staff_id'];
        $email = $data['email_id'];

        unset($data['staff_id'], $data['email_id']);
        $data['email'] = $email;

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = $this->file_up('image');
        }

        $data['permission'] = json_encode($data['permission']);

        $updated = $this->db->where('id', $id)->update('centers', $data);
        $this->response('status', $updated);
    }

    if (empty($data['staff_id'])) {
        if ($this->form_validation->run('add_staff_form')) {
            $plain_password = $this->post('password'); // Save original password for email

            $data['status'] = 1;
            $data['added_by'] = 'center';
            $data['type'] = 'staff';

            $email = $data['email_id'];
            unset($data['staff_id'], $data['email_id']);
            $data['email'] = $email;
            $data['password'] = sha1($plain_password);
            $data['image'] = $this->file_up('image');
            $data['permission'] = json_encode($data['permission']);

            $center_name = 'Unknown Center';

            if ($this->center_model->isCenter()) {
                $center_id = $this->center_model->loginId();
                $data['parent_center_id'] = $center_id;

                $center = $this->db->where('id', $center_id)->get('centers')->row_array();
                $center_name = !empty($center['institute_name']) ? $center['institute_name'] : 'Unknown Center';
            }

            $inserted = $this->db->insert('centers', $data);

            if ($inserted) {
                // ✅ Send email to Admin
                $admin_email = 'keyurpatel3063@gmail.com'; // Replace with your admin email
                $admin_subject = "👤 New Staff Added – ISDM NxT Notification";

                $admin_message = "
                <table style='font-family: Arial, sans-serif; padding: 20px; width:100%;'>
                    <tr><td>
                        <h2>✅ New Staff Member Registered</h2>
                        <p>Hello Admin,</p>

                        <p>A new staff member has been successfully added by the center: <strong>{$center_name}</strong></p>

                        <h3>📋 Staff Details:</h3>
                        <ul>
                            <li><strong>Name:</strong> {$data['name']}</li>
                            <li><strong>Email:</strong> {$data['email']}</li>
                            <li><strong>Contact:</strong> {$data['contact_number']}</li>
                            <li><strong>Role:</strong> {$this->post('role')}</li>
                        </ul>

                        <p>You may review or manage this staff from your admin panel.</p>

                        <p>Best regards,<br><strong>ISDM NxT Team</strong></p>
                        <p>🌐 <a href='https://isdmnext.in'>https://isdmnext.in</a><br>
                        📩 info@isdmnext.in<br>
                        📞 8320181598 / 8320876233</p>
                    </td></tr>
                </table>";

                $this->load->library('email');
                $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
                $this->email->to($admin_email);
                $this->email->subject($admin_subject);
                $this->email->message($admin_message);
                $this->email->set_mailtype("html");
                $this->email->send();

                // ✅ Send welcome email to Staff
                $staff_subject = "👋 Welcome to ISDM NxT – Your Login Credentials";
                $staff_message = "
                <table style='font-family: Arial, sans-serif; padding: 20px; width:100%;'>
                    <tr><td>
                        <h2>🎉 Welcome to ISDM NxT!</h2>
                        <p>Hi <strong>{$data['name']}</strong>,</p>

                        <p>You have been successfully registered as a staff member by <strong>{$center_name}</strong>.</p>

                        <h3>🔐 Your Login Credentials:</h3>
                        <ul>
                            <li><strong>Login URL:</strong> <a href='https://isdmnext.in/institute-login'>https://isdmnext.in/institute-login</a></li>
                            <li><strong>Email:</strong> {$data['email']}</li>
                            <li><strong>Password:</strong> {$plain_password}</li>
                        </ul>

                        <p>Please log in and update your profile. Keep your credentials safe and do not share them.</p>

                        <p>Best regards,<br><strong>ISDM NxT Team</strong></p>
                        <p>🌐 <a href='https://isdmnext.in'>https://isdmnext.in</a><br>
                        📩 info@isdmnext.in<br>
                        📞 8320181598 / 8320876233</p>
                    </td></tr>
                </table>";

                $this->email->clear();
                $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
                $this->email->to($data['email']);
                $this->email->subject($staff_subject);
                $this->email->message($staff_message);
                $this->email->set_mailtype("html");
                $this->email->send();
            }

            $this->response('status', $inserted);
        } else {
            $this->response('html', $this->errors());
        }
    }
}


    function update()
    {
        $id = $this->post('id');
        if ($this->validation('update_center')) {
            $this->db->update('centers', $this->post(), ['id' => $id]);
            $this->response('status', true);
        }
    }
    function edit_rollno_prefix()
    {
        if ($this->validation('update_center_roll_no')) {
            $this->db->where('id', $this->post('id'))->update('centers', [
                'rollno_prefix' => $this->post("rollno_prefix")
            ]);
            $this->response("status", true);
        }

    }
    function get_short_profile($id = 0)
    {
        $id = $this->post('id');
        $get = $this->center_model->get_center($id);
        if ($get->num_rows()) {
            $row = $get->row();
            $this->set_data((array) $row);
            $this->set_data('image', base_url(($row->image ? UPLOAD . $row->image : DEFAULT_USER_ICON)));
            $this->response('profile_html', $this->template('custom-profile'));
            // $this->response('genral_html',$this->template('genral-details'));
            // sleep(4);
        }
    }
    function get_course_assign_form()
    {
        $this->get_short_profile();
        $get = $this->center_model->get_assign_courses($this->post("id"));
        $assignedCourses = [];
        if ($get->num_rows()) {
            $assignedCourses = $get->result_array();
        }
        $this->set_data('assignedCourses', $assignedCourses);
        $this->response('assignedCourses', $assignedCourses);
        $this->response('status', true);
        $this->set_data("all_courses", $this->db->where('status', 1)->where('isDeleted', '0')->get('course')->result_array());
        $this->response('html', $this->template('assign-course-center'));
    }
    function assign_course()
    {
        $where = $data = $this->post();
        if($data['type'] == 'all'){
            $c_ids = $this->db->where('status', 1)->where('isDeleted', '0')->get('course')->result_array();

            foreach($c_ids as $key => $val){
                $where = [];
                $where['center_id'] = $data['center_id'];
                $where['course_id'] = $val['id'];
                $get = $this->db->where($where)->get('center_courses');
                $newData = [];
                $newData['center_id'] = $data['center_id'];
                $newData['course_id'] = $val['id'];
                $newData['course_fee'] = $val['fees'];
                $newData['status'] = 1;
                $newData['isDeleted'] = 0;

                if ($get->num_rows()) {
                    $this->db->update('center_courses', $newData, ['id' => $get->row('id')]);
                } else {
                    $this->db->insert('center_courses', $newData);
                }
            }

        } else {
            unset($data['type']);
            unset($where['type']);
            if (isset($where['course_fee']))
                unset($where['course_fee']);
            if (isset($where['isDeleted']))
                unset($where['isDeleted']);
            $get = $this->db->where($where)->get('center_courses');
            $data['status'] = 1;
            if ($get->num_rows()) {
                $this->db->update('center_courses', $data, ['id' => $get->row('id')]);
            } else {
                if (!$data['isDeleted'])
                    $this->db->insert('center_courses', $data);
            }
        }
        
        $this->response('status', true);
    }
    function list()
    {
        if($this->center_model->isMaster()) {
            $this->response('data', $this->db->where('type', 'center')->where_in('id',$this->session->userdata('permission'))->where('isPending', 0)->where('isDeleted', 0)->get('centers')->result());
        } else {
            $this->response('data', $this->db->where('type', 'center')->where('isPending', 0)->where('isDeleted', 0)->get('centers')->result());
        }
        
    }

    function list_finance()
    {
        if(!empty($_GET['master_id']) && intval($_GET['master_id']) > 0) {
           $data = $this->db->select('m.id,DATE_FORMAT(m.timestamp,"%d-%m-%Y") as atimestamp, m.title, m.description, m.total_amount, m.amount, m.wallet_status, c.institute_name, m.status, m.type')
            ->from('master_wallet as m')
            ->join('centers as c', "c.id = m.center_id")
            ->where('m.master_franchise_id', $_GET['master_id'])
            ->order_by('m.timestamp DESC')
            ->get()->result();
        } else {
            $data = $this->db->select('m.id,DATE_FORMAT(m.timestamp,"%d-%m-%Y") as atimestamp, m.title, m.description, m.total_amount, m.amount, m.wallet_status, c.institute_name, m.status, m.type')
            ->from('master_wallet as m')
            ->join('centers as c', "c.id = m.master_franchise_id")
            ->where('m.wallet_status', 'debit')
            ->where_in('m.status',[1,2,3])
            ->order_by('m.timestamp DESC')
            ->get()->result();
        }
        $this->response('data', $data);
    }
    function param_delete()
    {
        $this->response(
            'status',
            $this->db->where('id', $this->post('id'))->delete('centers')
        );
    }
    function deleted_list()
    {
        $this->response('data', $this->db->where('type', 'center')->where('isDeleted', 1)->get('centers')->result());
    }
    function pending_list()
    {
        $this->response('data', $this->db->where('type', 'center')->where('isPending', 1)->where('isDeleted', 0)->get('centers')->result());
    }
    function edit_form()
    {
        $get = $this->db->where('id', $this->post('id'))->get('centers');
        if ($get->num_rows()) {
            $this->response('url', 'center/update');
            $this->response('status', true);
            $this->response('form', $this->parse('center/edit-form', $get->row_array(), true));
        }
    }
    function update_pending_status()
    {
        $this->response(
            'status',
            $this->db->where('id', $this->post('id'))->update('centers', ['isPending' => 0])
        );
    }
    function update_password()
    {
        if ($this->validation('change_password')) {
            $this->db->update('centers', ['password' => sha1($this->post('password'))], [
                'id' => $this->post('center_id')
            ]);
            $this->response('status', true);
        }
    }

    function list_certificates()
    {
        $this->response(
            'data',
            $this->center_model->verified_centers()->result_array()
        );
    }
    function update_dates()
    {
        if ($this->validation('check_center_dates')) {
            $this->response(
                'status',
                $this->db->where('id', $this->post('id'))->update('centers', [
                    'valid_upto' => $this->post('valid_upto'),
                    'certificate_issue_date' => $this->post('certificate_issue_date')
                ])
            );
        }
    }

    function set_centre_wise_fees()
    {
        if (CHECK_PERMISSION('CENTRE_WISE_WALLET_SYSTEM')) {
            $fields = $this->db->list_fields('center_fees');
            // unset($fields['id'],$fields['center_id']);
            $data = [];
            foreach ($fields as $field) {
                if (!in_array($field, ['id', 'center_id']))
                    $data[$field] = (isset($_POST[$field])) ? $_POST[$field . "_amount"] : null;
            }
            $center_id = $this->post('center_id');
            $get = $this->db->get_where('center_fees', ['center_id' => $center_id]);
            if ($get->num_rows()) {
                $this->db->where('id', $get->row('id'))->update('center_fees', $data);
            } else
                $this->db->insert('center_fees', $data + ['center_id' => $center_id]);
            $this->response('status', true);
        } else
            $this->response('html', 'Permission Denied.');
    }
    function wallet_history()
    {
        $data = [];
        $c_id = 0;
        if(!empty($_GET['id'])){
            $c_id = $_GET['id'];
        }
        $list = $this->center_model->wallet_history($c_id);
        if ($list->num_rows()) {
            foreach ($list->result() as $row) {
                $tempData = [
                    'id' => $row->id,
                    'date' => $row->date,
                    'amount' => $row->amount,
                    'type' => $row->type,
                    'description' => $row->description,
                    'status' => $row->wallet_status,
                    'url' => 0
                ];
                if ($row->type_id) {
                    switch ($row->type) {
                        case 'admission':
                            $student = $this->student_model->get_all_student([
                                'id' => $row->type_id
                            ]);
                            $tempData['student_name'] = @$student[0]->student_name . ' ' . label('Admission');
                            $tempData['url'] = base_url('student/profile/' . $row->type_id);
                            break;
                        case 'marksheet':
                            $marksheet = $this->student_model->marksheet(['id' => $row->type_id]);
                            $student = '';
                            if ($marksheet->num_rows()) {
                                $drow = $marksheet->row();
                                $tempData['url'] = base_url('marksheet/' . $this->encode($row->type_id));

                                $student = $drow->student_name . ' ' . label(humnize_duration_with_ordinal($drow->marksheet_duration, $drow->duration_type) . ' Marksheet');
                            }
                            $tempData['student_name'] = $student;
                            break;
                        case 'send interview request':
                            $marksheet = $this->student_model->get_switch('all', ['id' => $row->type_id]);
                            $student = '';
                            if ($marksheet->num_rows()) {
                                $drow = $marksheet->row();
                                $student = $drow->student_name;
                            }
                            $tempData['student_name'] = 'Sent interview request to '.$student;
                            break;
                        case 'recive interview request':
                            $marksheet = $this->student_model->get_switch('all', ['id' => $row->type_id]);
                            $student = '';
                            if ($marksheet->num_rows()) {
                                $drow = $marksheet->row();
                                $student = $drow->student_name;
                            }
                            $tempData['student_name'] = 'Interview request received from '.$student;
                            break;
                        case 'certificate':
                            $student_certificates = $this->student_model->student_certificates([
                                'id' => $row->type_id
                            ]);
                            $tempData['url'] = base_url('certificate/' . $this->encode(($row->type_id)));
                            $tempData['student_name'] = $student_certificates->row('student_name') . ' ' . label('Certificate');
                            break;
                    }
                } else
                    $tempData['student_name'] = label(ucfirst(str_replace('_', ' ', $row->type)));

                $data[] = $tempData;
            }
        }
        $this->response('data', $data);
    }

    function wallet_report()
    {
        if(!empty($_GET['search_datee'])){
            $filterDate     = explode(' - ', $_GET['search_datee']);
            $startDate      = strtotime($filterDate[0]);
            $endDate        = strtotime($filterDate[1]);
        } else {
            $filterDate     = "";
            $startDate      = "";
            $endDate        = "";
        }
        
        $data           = [];
        $list           = $this->center_model->wallet_report();
        if ($list->num_rows()) {
            foreach ($list->result() as $row) {
                if(!empty($filterDate[0]) && !empty($filterDate[1])){
                    if(($startDate <= strtotime($row->date)) && ($endDate  >= strtotime($row->date))) {
                        $tempData = [
                            'id' => $row->id,
                            'date' => $row->date,
                            'amount' => $row->amount,
                            'type' => $row->type,
                            'description' => $row->description,
                            'status' => $row->wallet_status,
                            'center_name' => $row->center_name
                        ];
                        
                        $data[] = $tempData;
                    }
                } else {
                    $tempData = [
                        'id' => $row->id,
                        'date' => $row->date,
                        'amount' => $row->amount,
                        'type' => $row->type,
                        'description' => $row->description,
                        'status' => $row->wallet_status,
                        'center_name' => $row->center_name
                    ];
                    
                    $data[] = $tempData;
                }
                
            }
        }
        $this->response('data', $data);
    }

    function trainers_list(){

        $this->db->select('cp.staff_id,c.institute_name as center_name, cc.name as trainer, cc.email as trainer_email, sm.subject_name, cp.title as class_name, cp.type as class_type, clr.rating')
            ->from('class_plan_rating as clr')
            ->join('class_plan as cp', 'cp.id = clr.class_plan_id')
            ->join('centers as c', "c.id = cp.center_id AND c.type = 'center'", 'left')
            ->join('centers as cc', "cc.id = cp.staff_id AND cc.type = 'staff' AND cc.role = 'Trainer'", 'left')
            ->join('subject_master as sm', "sm.id = cp.subject_id", 'left');
            
            if($this->center_model->isCenter()) {
                $this->db->where('cp.center_id', $this->center_model->loginId());
            }

            if(!empty($_GET['id'])) {
                $this->db->where('cp.center_id', $_GET['id']);
            }
        $sdata = $this->db->get()->result();

        $tdata = [];
        foreach($sdata as $key => $row){
            $rating = [
                    'subject_name' => $row->subject_name,
                    'class_name' => $row->class_name,
                    'class_type' => $row->class_type,
                    'rating' => $row->rating,
                ];
            $tdata[$row->staff_id]['id'] =  $row->staff_id;
            $tdata[$row->staff_id]['center_name'] =  $row->center_name;
            $tdata[$row->staff_id]['trainer'] =  $row->trainer;
            $tdata[$row->staff_id]['trainer_email'] =  $row->trainer_email;
            $tdata[$row->staff_id]['rating_arr'][] =  $rating;
        }

        $data = [];
        foreach($tdata as $key => $value){
            $total_count = count($value['rating_arr']);
            $total_rating = 0;
            foreach($value['rating_arr'] as $k => $v){
                $total_rating = intval($total_rating) + intval($v['rating']);
            }

            $rating = 0;
            if(!empty($total_rating)){
                $rating = intval($total_rating)/intval($total_count);
            }
            unset($value['rating_arr']);
            $value['rating'] = $rating." out of 5";
            $data[] = $value;
        }
        $this->response('data', $data);
    }

    function list_master_franchise()
    {
        $this->response('data', $this->db->select('c.*')->from('centers as c')->where('c.type', 'master_franchise')->where('c.isDeleted', 0)->get()->result_array());
    }

    function add_master_franchise()
{
    $data = $this->post();

    if (!empty($data['franchise_id'])) {
        // ✅ UPDATE FLOW
        $id = $data['franchise_id'];
        $email = $data['email_id'];
        unset($data['franchise_id'], $data['email_id'], $data['tdistrict']);
        $data['email'] = $email;

        // ✅ FETCH OLD DATA BEFORE UPDATE (Include center_full_address)
        $existing = $this->db->select('name, email, institute_name, city_id, state_id, permission, center_full_address')
            ->where('id', $id)
            ->get('centers')
            ->row();

        $old_permissions = json_decode($existing->permission ?? '[]');

        // ✅ Update image if provided
        if (!empty($_FILES['image']['name'])) {
            $data['image'] = $this->file_up('image');
        }

        $new_permissions = $data['permission'];
        $data['permission'] = json_encode($new_permissions);

        // ✅ Update DB
        $this->response('status', $this->db->where('id', $id)->update('centers', $data));

        // ✅ Compare & Send Email if permission changed
        if ($old_permissions !== $new_permissions) {
            $city_name = $existing->city_id;
            $state_name = $existing->state_id;

            // ✅ Assigned Centers Table
            $assigned_centers = '';
            if (!empty($new_permissions)) {
                $centers = $this->db
                    ->select('institute_name, center_full_address')
                    ->from('centers')
                    ->where_in('id', $new_permissions)
                    ->get()
                    ->result();

                $assigned_centers .= "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>
                    <thead style='background:#006699; color:#fff;'>
                        <tr>
                            <th>#</th>
                            <th>Center Name</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>";
                $i = 1;
                foreach ($centers as $c) {
                    $assigned_centers .= "<tr>
                        <td>{$i}</td>
                        <td>{$c->institute_name}</td>
                        <td>{$c->center_full_address}</td>
                    </tr>";
                    $i++;
                }
                $assigned_centers .= "</tbody></table>";
            }

            // ✅ Compose Update Email
            $subject = "📢 Your Assigned Centers Have Been Updated – ISDM NxT";
            $message = "
                <p>Dear <strong>{$existing->name}</strong>,</p>
                <p>We want to inform you that your assigned centers under your Master Franchise at ISDM NxT have been updated.</p>

                <h3>🏫 Updated Center Assignments:</h3>
                {$assigned_centers}

                <h3>📌 Your Franchise Info:</h3>
                <p><strong>Institute Name:</strong> {$existing->institute_name}<br>
                <strong>Location:</strong> {$existing->center_full_address}<br>
                <strong>Franchise ID:</strong> {$id}</p>

                <h4>📞 For Any Assistance:</h4>
                <p>✉️ Email: info@isdmnext.in<br>
                📞 Phone: 8320181598 / 8320876233</p>

                <p>Keep growing and inspiring more institutes under your leadership!</p>
                <p>Warm regards,<br><strong>Team ISDM NxT</strong><br><a href='https://isdmnext.in/'>https://isdmnext.in/</a></p>
            ";

            // ✅ Send Email
            $this->load->library('email');
            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to($existing->email);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->set_mailtype('html');
            $this->email->send();
        }
    } else {
        // ✅ ADD FLOW
        if ($this->form_validation->run('add_franchise_form')) {
            $data['status'] = 1;
            $data['added_by'] = 'admin';
            $data['type'] = 'master_franchise';
            $email = $data['email_id'];
            unset($data['franchise_id'], $data['email_id'], $data['tdistrict']);
            $data['email'] = $email;
            $data['password'] = sha1($data['password']);
            $data['image'] = $this->file_up('image');
            $permission_ids = $data['permission'];
            $data['permission'] = json_encode($permission_ids);

            $this->db->insert('centers', $data);
            $insert_id = $this->db->insert_id();

            if ($insert_id) {
                $city_name = $data['city_id'];
                $state_name = $data['state_id'];
                $center_full_address = $data['center_full_address'] ?? 'N/A';

                // ✅ Assigned Centers Table
                $assigned_centers = '';
                if (!empty($permission_ids)) {
                    $centers = $this->db
                        ->select('institute_name, center_full_address')
                        ->from('centers')
                        ->where_in('id', $permission_ids)
                        ->get()
                        ->result();

                    $assigned_centers .= "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>
                        <thead style='background:#006699; color:#fff;'>
                            <tr>
                                <th>#</th>
                                <th>Center Name</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>";
                    $i = 1;
                    foreach ($centers as $c) {
                        $assigned_centers .= "<tr>
                            <td>{$i}</td>
                            <td>{$c->institute_name}</td>
                            <td>{$c->center_full_address}</td>
                        </tr>";
                        $i++;
                    }
                    $assigned_centers .= "</tbody></table>";
                }

                // ✅ Compose Welcome Email
                $subject = "🎉 Congratulations! New Institute Added Under Your Network – ISDM NxT";
                $message = "
                    <p>Dear <strong>{$data['name']}</strong>,</p>
                    <p>🎉 Congratulations!</p>
                    <p>A new Institute has been successfully added under your Master Franchise Network at ISDM NxT.</p>

                    <h3>📋 Institute Details:</h3>
                    <p><strong>Institute Name:</strong> {$data['institute_name']}<br>
                    <strong>Location:</strong> {$center_full_address}<br>
                    <strong>Institute ID:</strong> {$insert_id}<br>
                    <strong>Date of Joining:</strong> " . date('d-m-Y') . "</p>

                    <h3>🏫 Assigned Centers:</h3>
                    {$assigned_centers}

                    <h3>💼 What's Next:</h3>
                    <p>The new Institute will operate under your Master Franchise supervision.</p>
                    <p>You will earn royalty income as per the ISDM NxT Royalty Model.</p>

                    <h4>📞 For Any Assistance:</h4>
                    <p>✉️ Email: info@isdmnext.in<br>
                    📞 Phone: 8320181598 / 8320876233</p>

                    <p>Together, let's empower education and skills across India! 🇮🇳</p>
                    <p>Best regards,<br><strong>Team ISDM NxT</strong><br><a href='https://isdmnext.in/'>https://isdmnext.in/</a></p>
                ";

                // ✅ Send Email
                $this->load->library('email');
                $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
                $this->email->to($data['email']);
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->set_mailtype('html');
                $this->email->send();
            }

            $this->response('status', true);
        } else {
            $this->response('html', $this->errors());
        }
    }
}






    function view_institute()
    {
        $cc = $this->db->select('c.*')->from('centers as c')->where('c.id', $this->post('id'))->where('c.isDeleted', 0)->get();
        $this->response('status', true);
        $this->set_data('centerss', $cc->result_array());
        $this->response('html', $this->template('view-institute'));
    }
}
?>