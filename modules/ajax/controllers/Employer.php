<?php
class Employer extends Ajax_Controller
{
    function add_group(){
        $data = $this->post();
        $this->response(
            'status',
            $this->db->insert('group', $data)
        );
        $this->response('html','Group Added successfully.');
    }

    function edit_group(){
        $this->db->where('id',$this->post('id'))->update('group',[
            'group' => $this->post('group'),
        ]);        
        $this->response('status',true);
    }

    function delete_group($cat_id = 0){
        if($cat_id){
            $this->response( 'status',
                $this->db->where('id',$cat_id)->delete('group')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
    } 

    function list_group(){
        $list = $this->db->
            select('*')
            ->from('group')
            ->where('status',1)
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function add_industry(){
        $data = $this->post();
        $this->response(
            'status',
            $this->db->insert('industry', $data)
        );
        $this->response('html','Industry Added successfully.');
    }

    function edit_industry(){
        $this->db->where('id',$this->post('id'))->update('industry',[
            'industry' => $this->post('industry'),
        ]);        
        $this->response('status',true);
    }

    function delete_industry($cat_id = 0){
        if($cat_id){
            $this->response( 'status',
                $this->db->where('id',$cat_id)->delete('industry')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
    } 

    function list_industry(){
        $list = $this->db->
            select('*')
            ->from('industry')
            ->where('status',1)
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function list_group_industry(){
        $data               = $this->post();
        $job_role_skill = $this->db->select('m.industry_id')
            ->from('industry_group as m')
            ->where('m.status',1)
            ->where('m.group_id',$data['group_id'])
            ->get()->result_array();
        $selected = [];
        if(count($job_role_skill) > 0){
            foreach($job_role_skill as $key => $val){
                $selected[] = $val['industry_id'];
            }
        }

        $job_skill          = $this->db->select('*')
            ->from('industry')
            ->where('status',1)
            ->get()->result_array();

        $html = '';
        foreach($job_skill as $key => $val){
            $chk = '';
            if(in_array($val['id'], $selected)){
                $chk = 'checked="checked"';
            }
            $html .='<div class="form-group col-md-6 mt-2">
                        <input type="checkbox" name="industry[]" id="industry_'.$val['id'].'" '.$chk.' class="form-check-input group_industry" value="'.$val['id'].'"  />
                        <label class="form-label">'.$val['industry'].'</label>
                    </div>';
        }
        $html .= '';
        $this->response('status',true);
        $this->response('html', $html);
    }

    function mapping_group_industry(){
        $data               = $this->post();
        $saveData = [];
        foreach($data['industry'] as $key => $industry_id){
            $saveData[] = [
                    'industry_id' => $industry_id,
                    'group_id' => $data['group_id']
                ];
        }
        if(count($saveData) > 0){
            $this->db->where('group_id',$data['group_id'])->delete('industry_group');
            $this->db->insert_batch('industry_group', $saveData);
            $this->response('status', true);
            $this->response('html', 'Mapping Saved successfully.');
        } else {
            $this->response('status', false);
        }
    }

    function add_department(){
        $data = $this->post();
        $this->response(
            'status',
            $this->db->insert('department', $data)
        );
        $this->response('html','Department Added successfully.');
    }

    function edit_department(){
        $this->db->where('id',$this->post('id'))->update('department',[
            'department' => $this->post('department'),
        ]);        
        $this->response('status',true);
    }

    function delete_department($cat_id = 0){
        if($cat_id){
            $this->response( 'status',
                $this->db->where('id',$cat_id)->delete('department')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
    } 

    function list_department(){
        $list = $this->db->
            select('*')
            ->from('department')
            ->where('status',1)
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function add_role(){
        $data = $this->post();
        $this->response(
            'status',
            $this->db->insert('job_role', $data)
        );
        $this->response('html','Job Role Added successfully.');
    }

    function edit_role(){
        $this->db->where('id',$this->post('id'))->update('job_role',[
            'role' => $this->post('role'),
        ]);        
        $this->response('status',true);
    }

    function delete_role($cat_id = 0){
        if($cat_id){
            $this->response( 'status',
                $this->db->where('id',$cat_id)->delete('job_role')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
    } 

    function list_role(){
        $list = $this->db->
            select('*')
            ->from('job_role')
            ->where('status',1)
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function list_department_role(){
        $data               = $this->post();
        $job_role_skill = $this->db->select('m.role_id')
            ->from('department_role as m')
            ->where('m.status',1)
            ->where('m.department_id',$data['department_id'])
            ->get()->result_array();
        $selected = [];
        if(count($job_role_skill) > 0){
            foreach($job_role_skill as $key => $val){
                $selected[] = $val['role_id'];
            }
        }

        $job_skill          = $this->db->select('*')
            ->from('job_role')
            ->where('status',1)
            ->get()->result_array();

        $html = '';
        foreach($job_skill as $key => $val){
            $chk = '';
            if(in_array($val['id'], $selected)){
                $chk = 'checked="checked"';
            }
            $html .='<div class="form-group col-md-6 mt-2">
                        <input type="checkbox" name="role[]" id="role_'.$val['id'].'" '.$chk.' class="form-check-input group_role" value="'.$val['id'].'"  />
                        <label class="form-label">'.$val['role'].'</label>
                    </div>';
        }
        $html .= '';
        $this->response('status',true);
        $this->response('html', $html);
    }

    function mapping_department_role(){
        $data               = $this->post();
        $saveData = [];
        foreach($data['role'] as $key => $role_id){
            $saveData[] = [
                    'role_id' => $role_id,
                    'department_id' => $data['department_id']
                ];
        }
        if(count($saveData) > 0){
            $this->db->where('department_id',$data['department_id'])->delete('department_role');
            $this->db->insert_batch('department_role', $saveData);
            $this->response('status', true);
            $this->response('html', 'Mapping Saved successfully.');
        } else {
            $this->response('status', false);
        }
    }

    function add_skill(){
        $data = $this->post();
        $this->response(
            'status',
            $this->db->insert('job_skill', $data)
        );
        $this->response('html','Job Skill Added successfully.');
    }

    function edit_skill(){
        $this->db->where('id',$this->post('id'))->update('job_skill',[
            'skill' => $this->post('skill'),
        ]);        
        $this->response('status',true);
    }

    function delete_skill($skil_id = 0){
        if($skil_id){
            $this->response( 'status',
                $this->db->where('id',$skil_id)->delete('job_skill')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
    } 

    function list_skill(){
        $list = $this->db->
            select('*')
            ->from('job_skill')
            ->where('status',1)
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function list_role_skill(){
        $data               = $this->post();
        $job_role_skill = $this->db->select('m.skill_id')
            ->from('job_role_skill as m')
            ->where('m.status',1)
            ->where('m.role_id',$data['role_id'])
            ->get()->result_array();
        $selected = [];
        if(count($job_role_skill) > 0){
            foreach($job_role_skill as $key => $val){
                $selected[] = $val['skill_id'];
            }
        }

        $job_skill          = $this->db->select('*')
            ->from('job_skill')
            ->where('status',1)
            ->get()->result_array();

        $html = '';
        foreach($job_skill as $key => $val){
            $chk = '';
            if(in_array($val['id'], $selected)){
                $chk = 'checked="checked"';
            }
            $html .='<div class="form-group col-md-3 mt-2">
                        <input type="checkbox" name="skill[]" id="skill_'.$val['id'].'" '.$chk.' class="form-check-input group_skill" value="'.$val['id'].'"  />
                        <label class="form-label">'.$val['skill'].'</label>
                    </div>';
        }
        $html .= '';
        $this->response('status',true);
        $this->response('html', $html);
    }

    function manage_role_skill(){
        $data               = $this->post();
        $saveData = [];
        foreach($data['skill'] as $key => $skill_id){
            $saveData[] = [
                    'skill_id' => $skill_id,
                    'role_id' => $data['role_id']
                ];
        }
        if(count($saveData) > 0){
            $this->db->where('role_id',$data['role_id'])->delete('job_role_skill');
            $this->db->insert_batch('job_role_skill', $saveData);
            $this->response('status', true);
            $this->response('html', 'Mapping Saved successfully.');
        } else {
            $this->response('status', false);
        }
    }

    function employer_mgmt(){
        if ($this->form_validation->run('add_employer')) {
            $data                   = $this->post();
            $email                  = $data['email_id'];
            $website                = $data['website'];
            $about_company          = $data['about_company'];
            unset($data['email_id']);
            unset($data['website']);
            unset($data['about_company']);

            $data['status']         = 1;
            $data['added_by']       = 'admin';
            $data['email']          = $email;
            $data['password']       = sha1($data['password']);
            $data['image']          = $this->file_up('image');
            $data['signature']      = $this->file_up('signature');
            $data['logo']           = $this->file_up('logo');
            $status                 = $this->db->insert('centers', $data);
            $employer_id            = $this->db->insert_id();
            if($employer_id){
                $this->db->insert('employer_info', ['employer_id' => $employer_id,'about_company' => $about_company, 'website' => $website]);
            }
            $this->response('status', $status);
        } else{
            $this->response('html', $this->errors());
        }
    }

    function list_employer(){
        $this->response('data', 
            $this->db->where('type', 'employer')
                    ->where('isPending', 0)
                    ->where('isDeleted', 0)
                    ->order_by('id','DESC')
                    ->get('centers')->result());
    }

    function edit_form(){
        $get = $this->db->select('c.*,ei.about_company, ei.website')->from('centers as c')->join('employer_info as ei', 'ei.employer_id = c.id', 'left')->where('c.id', $this->post('id'))->get();
        if ($get->num_rows()) {
            $this->response('url', 'employer/update');
            $this->response('status', true);
            $this->response('form', $this->parse('employer/edit-form', $get->row_array(), true));
        }
    }

    function update(){
        $id = $this->post('id');
        if ($this->validation('update_employer')) {
            $data                   = $this->post();
            $website                = $data['website'];
            $about_company          = $data['about_company'];
            unset($data['website']);
            unset($data['about_company']);
            $this->db->update('centers', $data, ['id' => $id]);
            if($id){
                $this->db->where('employer_id',$id)->delete('employer_info');
                $this->db->insert('employer_info', ['employer_id' => $id,'about_company' => $about_company, 'website' => $website]);
            }
            $this->response('status', true);
        }
    }

    function employer_wallet_load(){
        $post = $this->post();
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
            $this->response('status', true);
        } else {
            $this->response('status', false);
        }
    }

    function get_roles(){
        $data               = $this->post();
        $get                = $this->db->select('jr.*')
        ->from('job_role as jr')
        ->join('department_role as dr', 'dr.role_id = jr.id AND dr.status = 1')
        ->where('dr.department_id',$data['department_id'])->where('jr.status', 1)->get();

        if ($get->num_rows()) {
            $this->response('roles', $get->result_array());
            $this->response("status", true);
        }
    }

    function list_role_skillll(){
        $data               = $this->post();
        $job_role_skill = $this->db->select('m.skill_id')
            ->from('job_role_skill as m')
            ->where('m.status',1)
            ->where('m.role_id',$data['role_id'])
            ->get()->result_array();
        $selected = [];
        if(count($job_role_skill) > 0){
            foreach($job_role_skill as $key => $val){
                $selected[] = $val['skill_id'];
            }
        }

        $job_skill          = $this->db->select('*')
            ->from('job_skill')
            ->where('status',1)
            ->get()->result_array();

        $temp_key_skills = [];
        if(!empty($data['temp_key_skills'])){
            $temp_key_skills = explode(",",$data['temp_key_skills']);
        }

        $html = '';
        foreach($job_skill as $key => $val){
            if(in_array($val['id'], $selected)){
                $chk = "";
                if(in_array($val['id'], $temp_key_skills)){
                    $chk = "checked='checked'";
                }
               $html .='<div class="form-group col-md-3 mt-2">
                        <input type="checkbox" name="key_skills[]" id="skill_'.$val['id'].'" '.$chk.' class="form-check-input group_skill" value="'.$val['id'].'"  />
                        <label class="form-label">'.$val['skill'].'</label>
                    </div>';
            }
        }
        $html .= '';
        $this->response('status',true);
        $this->response('html', $html);
    }

    function job_mgmt() {
        $data = $this->post();
    
        $job_id = isset($data['job_id']) ? $data['job_id'] : 0;
        unset($data['job_id']);
    
        $key_skills = isset($data['key_skills']) ? $data['key_skills'] : [];
        $data['key_skills'] = json_encode($key_skills);
        $data['key_skills_text'] = json_encode($key_skills);
    
        if (!empty($job_id)) {
            $this->db->update('jobs', $data, ['id' => $job_id]);
            $this->response('msg', 'Job Updated Successfully.');
        } else {
            $data['status'] = 'open';
            $data['added_by'] = 'admin';
            $this->db->insert('jobs', $data);
            $job_id = $this->db->insert_id();
            $this->response('msg', 'Job Saved Successfully.');
    
            $employer = [];
            if (!empty($data['employer_id'])) {
                $employer = $this->db->where('id', $data['employer_id'])->get('centers')->row_array();
            }
            if (!$employer) {
                $this->response('status', false);
            }
    
            $job_title = isset($data['job_title']) ? $data['job_title'] : 'N/A';
            $employment_type = isset($data['employment_type']) ? $data['employment_type'] : 'N/A';
            $openings = isset($data['openings']) ? $data['openings'] : '1';
            $education_id = isset($data['education_id']) ? $data['education_id'] : 'N/A';
            $highlights = isset($data['job_highlights']) ? $data['job_highlights'] : '';
    
            $education_name = 'N/A';
            if (!empty($education_id)) {
                $eduRow = $this->db->where('id', $education_id)->get('isdm_education')->row_array();
                if ($eduRow) {
                    $education_name = $eduRow['qualification'];
                }
            }
    
            $this->load->library('email');
    
            // ✅ Fetch & match student profiles
            $students = $this->db->select('s.name, s.email, sp.key_skills, sp.experience, sp.fluancy_in_english')
                ->from('isdm_students as s')
                ->join('isdm_student_profile as sp', 'sp.student_id = s.id', 'left')
                ->where('s.status', 1)
                ->get()->result_array();
    
            $matched_students = [];
            $job_skills = json_decode($data['key_skills_text'], true);
            foreach ($students as $s) {
                $skill_match = false;
                $exp_match = ($s['experience'] == $data['experience']);
                $lang_match = ($s['fluancy_in_english'] == $data['fluancy_in_english']);
                $student_skills = json_decode($s['key_skills'], true);
    
                if (is_array($job_skills) && is_array($student_skills)) {
                    foreach ($job_skills as $jk) {
                        if (in_array($jk, $student_skills)) {
                            $skill_match = true;
                            break;
                        }
                    }
                }
    
                if ($skill_match || $exp_match || $lang_match) {
                    $matched_students[] = $s;
                }
            }
    
            // ✅ Admin Email
            $admin_subject = "🚀 New Job Posting Submitted by Corporate – Action Required | ISDM NxT";
            $admin_message = "<table style='font-family: Arial, sans-serif; padding: 20px; width:100%;'><tr><td>
                <h2>📢 New Job Vacancy Posted by Corporate</h2>
                <p><strong>Company:</strong> {$employer['institute_name']}<br>
                <strong>Contact:</strong> {$employer['name']} | {$employer['email']}</p>
                <p><strong>Job Title:</strong> {$job_title}<br>
                <strong>Type:</strong> {$employment_type}<br>
                <strong>Openings:</strong> {$openings}<br>
                <strong>Eligibility:</strong> {$education_name}<br>
                <strong>Description:</strong> {$highlights}</p>
                <p><strong>Job ID:</strong> {$job_id} | <strong>Date:</strong> " . date('d-m-Y H:i:s') . "</p>
                <p>Regards, <br>ISDM NxT</p></td></tr></table>";
    
            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to('keyurpatel3063@gmail.com');
            $this->email->subject($admin_subject);
            $this->email->message($admin_message);
            $this->email->send();
    
            // ✅ Corporate Email with matched student names
            $matched_list = "";
            foreach ($matched_students as $m) {
                $matched_list .= "<li>{$m['name']}</li>";
            }
    
            $corporate_subject = "🎉 Your Job Posting Has Been Successfully Added – ISDM NxT";
            $corporate_message = "<table style='font-family: Arial, sans-serif; padding: 20px; width:100%;'><tr><td>
                <h2>🎉 Your Job Posting is Live!</h2>
                <p>Dear {$employer['name']},</p>
                <p>Your job has been posted. Below is the summary:</p>
                <ul>
                    <li><strong>Job Title:</strong> {$job_title}</li>
                    <li><strong>Employment Type:</strong> {$employment_type}</li>
                    <li><strong>Openings:</strong> {$openings}</li>
                    <li><strong>Posted On:</strong> " . date('d-m-Y H:i:s') . "</li>
                </ul>
                <h3>👥 Matched Candidates:</h3>
                <ul>{$matched_list}</ul>
                <p><a href='https://isdmnext.in/employer/index'>Access Your Portal</a></p>
                <p>Best regards,<br><strong>Team ISDM NxT</strong></p>
            </td></tr></table>";
    
            $this->email->clear();
            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to($employer['email']);
            $this->email->subject($corporate_subject);
            $this->email->message($corporate_message);
            $this->email->send();
    
            // ✅ Student Email
            $student_emails = array_column($students, 'email');
            $student_subject = "🚀 New Job Opportunity Available on ISDM NxT – Apply Now";
            $student_message = "<table style='font-family: Arial, sans-serif; padding: 20px; width:100%;'><tr><td>
                <h2>🚀 New Job Opportunity Just Posted!</h2>
                <p><strong>Job Title:</strong> {$job_title}<br>
                <strong>Company:</strong> {$employer['institute_name']}<br>
                <strong>Type:</strong> {$employment_type}<br>
                <strong>Eligibility:</strong> {$education_name}<br>
                <strong>Job Description:</strong> {$highlights}</p>
                <p><a href='https://isdmnext.in/student-login-'>Apply Now on ISDM NxT Portal</a></p>
                <p>Best of luck!<br>Team ISDM NxT</p>
            </td></tr></table>";
    
            $this->email->clear();
            $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
            $this->email->to('isdmnxt@gmail.com'); // dummy TO
            $this->email->bcc($student_emails);
            $this->email->subject($student_subject);
            $this->email->message($student_message);
            $this->email->send();
        }
    
        $this->response('status', (bool)$job_id);
    }
    
    
    
    
    
    
    

    function list_jobs(){
        if ($this->center_model->isAdmin() OR $this->center_model->isMaster()){
            $list = $this->db->select('j.*,c.institute_name as center_name,i.industry,e.qualification,jr.role,state.STATE_NAME,district.DISTRICT_NAME')
                    ->from('jobs as j')
                    ->join('centers as c', 'c.id = j.employer_id', 'left')
                    ->join('industry as i', 'i.id = j.industry_id', 'left')
                    ->join('isdm_education as e', 'e.id = j.education_id', 'left')
                    ->join('job_role as jr', 'jr.id = j.role_id', 'left')
                    ->join('state', 'state.STATE_ID = j.state_id', 'left')
                    ->join('district', 'district.DISTRICT_ID = j.city_id and district.STATE_ID = state.STATE_ID', 'left')
                    ->where('j.isDeleted',0)
                    ->order_by('j.id','DESC')
                    ->get()->result();
        }

        if ($this->center_model->isEmployer()){
            $list = $this->db->select('j.*,c.institute_name as center_name,i.industry,e.qualification,jr.role,state.STATE_NAME,district.DISTRICT_NAME')
                    ->from('jobs as j')
                    ->join('centers as c', 'c.id = j.employer_id', 'left')
                    ->join('industry as i', 'i.id = j.industry_id', 'left')
                    ->join('isdm_education as e', 'e.id = j.education_id', 'left')
                    ->join('job_role as jr', 'jr.id = j.role_id', 'left')
                    ->join('state', 'state.STATE_ID = j.state_id', 'left')
                    ->join('district', 'district.DISTRICT_ID = j.city_id and district.STATE_ID = state.STATE_ID', 'left')
                    ->where('j.isDeleted',0)
                    ->where('j.employer_id',$this->center_model->loginId())
                    ->order_by('j.id','DESC')
                    ->get()->result();
        }
        
        $this->response('data', $list);
    }

    function manage_packages(){
        $data                           = $this->post();
        $packages_id                    = $data['packages_id'];
        unset($data['packages_id']);
        if(!empty($packages_id)){
            $this->db->update('emp_packages', $data, ['id' => $packages_id]);
            $this->response('msg', 'Package Updated Successfully.');
        } else {
            $status                     = $this->db->insert('emp_packages', $data);
            $packages_id                = $this->db->insert_id();
            $this->response('msg', 'Package Saved Successfully.');
        }
        
        if($packages_id){
            $this->response('status', true);
        } else {
            $this->response('status', false);
        }
    }

    function packages_list(){
        $this->response('data', 
            $this->db->select('*')
                    ->from('emp_packages')
                    ->where('status', '1')
                    ->where('isDeleted', '0')
                    ->order_by('id','DESC')
                    ->get()->result());
    }

    function subscribe(){
        $data                           = $this->post();
        $plan['package_id']             = $data['planId'];
        $plan['employer_id']            = $this->center_model->loginId();
        $plan['status']                 = '1';
        $this->db->where('employer_id',$this->center_model->loginId())->delete('emp_packages_trans');
        $status                     = $this->db->insert('emp_packages_trans', $plan);
        $packages_id                = $this->db->insert_id();
        if($packages_id){
            $this->response('status', true);
        } else {
            $this->response('status', false);
        }
    }

    function get_db_matched_students(){ 
        $data                           = $this->post();
        $studentIds                     = explode(",",$data['studentIds']);
        $jobid                          = $data['jobid'];

        $studentList                    = [];
        foreach($studentIds as $k => $student_id){
            $get = $this->student_model->get_student_via_id($student_id)->result_array();
            $tempStudent = [];
            if(!empty($get[0]['student_id'])){

                $tempStudent = $get[0];
                $tempStudent['job_id']                  = $jobid;
                $geta                                   = $this->db->select('*')->where('student_id', $get[0]['student_id'])->get('student_profile');
                $tempStudent['resume_headline']       = $geta->row('resume_headline');
                $tempStudent['resume']                = $geta->row('resume');
                $tempStudent['experience']            = $geta->row('experience');
                if(!empty($geta->row('key_skills'))){
                    $tempStudent['key_skills']            = displayKeySkill($geta->row('key_skills'));
                } else {
                    $tempStudent['key_skills']            = '';
                }

                if(!empty($geta->row('industries'))){
                    $tempStudent['industries']            = displayIndustry($geta->row('industries'));
                } else {
                    $tempStudent['industries']            = '';
                }

                if(!empty($geta->row('pan_languages'))){
                    $tempStudent['pan_languages']            = displayLanguages($geta->row('pan_languages'));
                } else {
                    $tempStudent['pan_languages']            = '';
                }
                
                $tempStudent['profile_summary']       = $geta->row('profile_summary');
                $tempStudent['fluancy_in_english']    = $geta->row('fluancy_in_english');

                $studentList[] = $tempStudent;
            }
        }
        $this->set_data('studentList', $studentList);
        $this->response('status', count($studentList) ? true : false);
        $this->response(
            'html',
            count($studentList) ?
            $this->parse('employer/student_list', [], true)
            : alert('No Student found.', 'danger mb-10')
            . '<img class="mx-auto h-150px h-lg-200px" src="' . base_url('assets/media/illustrations/sigma-1/13.png') . '">'
        );
    }
    
    
    

    public function send_interview_request()
{
    $data = $this->post();
    $student_id = $data['student_id'] ?? 0;
    $job_id     = $data['job_id'] ?? 0;

    // ✅ Fetch student
    $student = $this->db->where('id', $student_id)->get('isdm_students')->row_array();

    // ✅ Fetch job
    $job = $this->db->where('id', $job_id)->get('jobs')->row_array();

    // ✅ Fetch corporate
    $corporate = $this->db->where('id', $job['employer_id'])->get('centers')->row_array();

    // ✅ Fetch education
    $education = $this->db->where('id', $job['education_id'])->get('isdm_education')->row_array();

    if (!$student || !$job || !$corporate) {
        $this->response('status', false);
        $this->response('html', 'Missing data. Please retry.');
        return;
    }

    // Values
    $student_email   = $student['email'];
    $student_name    = $student['name'];
    $job_title       = $job['job_title'] ?? 'N/A';
    $corporate_name  = $corporate['institute_name'] ?? 'N/A';
    $job_location    = $job['work_location'] ?? 'N/A';
    $employment_type = $job['employment_type'] ?? 'N/A';
    $education_name  = $education['qualification'] ?? 'N/A';

    // ✅ Insert into DB (remove unnecessary keys)
    unset(
        $data['student_name'],
        $data['student_email'],
        $data['corporate_name'],
        $data['job_title'],
        $data['job_location'],
        $data['employment_type'],
        $data['education_name']
    );
    $this->db->insert('send_interview_request', $data);

    // ✅ Email Content
    $subject = "✉️ Great News! A Company is Interested in You – ISDM NxT";
    $message = "
        <p>Dear <strong>$student_name</strong>,</p>
        <p>🎉 <strong>Congratulations!</strong></p>
        <p>A corporate company has shown interest in your profile through the <strong>ISDM NxT Portal</strong>! 🚀</p>

        <h3>🏢 Corporate Details:</h3>
        <p><strong>Company Name:</strong> $corporate_name</p>
        <p><strong>Job Title:</strong> $job_title</p>
        <p><strong>Eligibility:</strong> $education_name</p>
        <p><strong>Location:</strong> $job_location</p>
        <p><strong>Employment Type:</strong> $employment_type</p>

        <h3>🔗 Action Required:</h3>
        <p>👉 Please login to your ISDM NxT Student Portal to view more details and respond to the opportunity promptly.</p>
        <p><a href='https://isdmnext.in/student-login-' style='background: #007bff; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Login Now</a></p>

        <h4>📢 Important:</h4>
        <ul>
            <li>Check the corporate message or interview instructions (if any).</li>
            <li>Respond quickly to secure your chance.</li>
            <li>Prepare your documents and profile if needed.</li>
        </ul>

        <h4>🛠 Need Assistance?</h4>
        <p>✉️ Email: info@isdmnext.in<br>
        📞 Phone: 8320181598 / 8320876233<br>
        🌐 Website: <a href='https://www.isdmnext.in'>www.isdmnext.in</a></p>

        <p>Wishing you success and growth in your career journey! 🌟</p>
        <p>Best regards,<br><strong>Team ISDM NxT</strong></p>
    ";

    // ✅ Send Email
    $this->load->library('email');
    $this->email->from('isdmnxt@gmail.com', 'ISDM NxT');
    $this->email->to($student_email);
    $this->email->subject($subject);
    $this->email->message($message);
    $this->email->set_mailtype('html');

    if (!$this->email->send()) {
        log_message('error', 'Failed to send interview email: ' . $this->email->print_debugger());
        $this->response('status', false);
        return;
    }

    $this->response('status', true);
}





    function job_request_report(){
        $list = $this->db->
            select('ss.id,ss.job_id,DATE_FORMAT(ss.created_at,"%d-%m-%Y") as job_date,ss.student_status as status,c.institute_name,s.name as student_name,j.job_title,j.experience,j.job_type')
            ->from('send_interview_request ss')
            ->join('centers as c', "c.id = ss.center_id")
            ->join("students as s", "s.id = ss.student_id ", 'left')
            ->join("jobs as j", "j.id = ss.job_id", 'left')
            ->where('ss.center_id',$this->center_model->loginId())
            ->get();

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function get_applied_students(){
        $data                           = $this->post();
        $studentIds                     = explode(",",$data['studentIds']);
        $jobid                          = $data['jobid'];

        $studentList                    = [];
        foreach($studentIds as $k => $student_id){
            $get = $this->student_model->get_student_via_id($student_id)->result_array();
            $tempStudent = [];
            if(!empty($get[0]['student_id'])){

                $tempStudent = $get[0];
                $tempStudent['job_id']                  = $jobid;
                $geta                                   = $this->db->select('*')->where('student_id', $get[0]['student_id'])->get('student_profile');
                $tempStudent['resume_headline']       = $geta->row('resume_headline');
                $tempStudent['resume']                = $geta->row('resume');
                $tempStudent['experience']            = $geta->row('experience');
                if(!empty($geta->row('key_skills'))){
                    $tempStudent['key_skills']            = displayKeySkill($geta->row('key_skills'));
                } else {
                    $tempStudent['key_skills']            = '';
                }

                if(!empty($geta->row('industries'))){
                    $tempStudent['industries']            = displayIndustry($geta->row('industries'));
                } else {
                    $tempStudent['industries']            = '';
                }

                if(!empty($geta->row('pan_languages'))){
                    $tempStudent['pan_languages']            = displayLanguages($geta->row('pan_languages'));
                } else {
                    $tempStudent['pan_languages']            = '';
                }
                
                $tempStudent['profile_summary']       = $geta->row('profile_summary');
                $tempStudent['fluancy_in_english']    = $geta->row('fluancy_in_english');

                $studentList[] = $tempStudent;
            }
        }
        $this->set_data('studentList', $studentList);
        $this->response('status', count($studentList) ? true : false);
        $this->response(
            'html',
            count($studentList) ?
            $this->parse('employer/student_list_new', [], true)
            : alert('No Student found.', 'danger mb-10')
            . '<img class="mx-auto h-150px h-lg-200px" src="' . base_url('assets/media/illustrations/sigma-1/13.png') . '">'
        );
    }

    function recive_interview_request(){
        $data = $this->post();
        $id = $data['id'];
        unset($data['id']);
        unset($data['student_id']);
        unset($data['job_id']);

        if($id && $data['status'] == 'shortlisted'){

            $job_list = $this->db->select('*')
                    ->from('received_interview_request as sir')
                    ->join('emp_packages_trans as e', "e.employer_id = sir.employer_id and e.status = '1'", 'left')
                    ->join('emp_packages as m', 'm.id = e.package_id', 'left')
                    ->join('centers as c', 'c.id = sir.employer_id', 'left')
                    ->where('sir.id',$id)
                    ->get()->result_array();

            $close_balance = $job_list[0]['wallet'];
            $deduction_amount = $job_list[0]['apply_charges'];

            if(intval($close_balance) == 0){
                $this->response('html', "Please recharge your wallet $deduction_amount.");
                exit;
            }

            if(intval($deduction_amount) == 0){
                $this->response('html', 'Charges is Low.');
                exit;
            }

            $close_balance = $close_balance - $deduction_amount;
            if ($close_balance < 0) {
                $this->response('html', "Wallet Balance is should be minimum $deduction_amount.");
                exit;
            }

            $dataq = [
                'center_id' => $job_list[0]['employer_id'],
                'amount' => $deduction_amount,
                'o_balance' => $job_list[0]['wallet'],
                'c_balance' => $close_balance,
                'type' => 'recive interview request',
                'description' => 'Interview Request Shortlisted By Employer',
                'type_id' => $job_list[0]['student_id'],
                'added_by' => 'center',
                'order_id' => strtolower(generateCouponCode(12)),
                'status' => 1,
                'wallet_status' => 'debit'
            ];
            $this->db->insert('wallet_transcations', $dataq);
            if($this->db->insert_id()){
                $this->center_model->update_wallet($job_list[0]['employer_id'], $close_balance);
                $this->db->update('received_interview_request', $data, ['id' => $id]);
            }
        } else {
            $this->db->update('received_interview_request', $data, ['id' => $id]);
        }
        
        $this->response('status', true);
    }
    
}
?>