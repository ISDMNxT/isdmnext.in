<?php if(empty($type)) { ?>
<div class="row">
    <div class="col-md-12">
        <form id="add_job_form" action="" method="POST" autocomplete="off">
            <div class="card">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#job_post_form">
                    <h3 class="card-title">Job Posting</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <?php
                    $center_id      = 0;
                    $boxClass       = '';
                    if ($this->center_model->isEmployer()) {
                        $center_id = $this->center_model->loginId();
                        $this->db->where('id', $center_id);
                        $boxClass = 'd-none';
                    } else {
                        if(!empty($form['employer_id'])){
                            $center_id  = $form['employer_id'];
                        }
                    }
                ?>
                <div id="job_post_form" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="job_id" name="job_id" value="<?php if(!empty($form['id'])){ echo $form['id']; } else { echo '0'; } ?>">
                            <input type="hidden" id="temp_role_id" value="<?php if(!empty($form['role_id'])){ echo $form['role_id']; } else { echo '0'; } ?>">
                            <input type="hidden" id="temp_key_skills" value="<?php if(!empty($form['key_skills'])){ echo implode(",",json_decode($form['key_skills'],true)); } else { echo '0'; } ?>">
                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12 <?= $boxClass ?>">
                                <label class="form-label required">Employer</label>
                                <select class="form-select" name="employer_id" id="employer_id" data-control="select2"
                                    data-placeholder="Select a Employer"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->where('type', 'employer')
                                            ->where('isPending', 0)
                                            ->where('isDeleted', 0)
                                            ->order_by('id','DESC')
                                            ->get('centers')->result();
                                    foreach ($list as $row) {
                                        $selected = $center_id == $row->id ? 'selected' : '';
                                        echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->institute_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Job Title</label>
                                <input type="text" name="job_title" class="form-control" placeholder="Enter Job Title" value="<?php if(!empty($form['job_title'])){ echo $form['job_title']; } ?>">
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Job Highlights</label>
                                <textarea name="job_highlights" class="aryaeditor" placeholder="Enter Job Highlights"><?php if(!empty($form['job_highlights'])){ echo $form['job_highlights']; } ?></textarea>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Industry</label>
                                <select name="industry_id" class="form-control">
                                <?php
                                echo '<option value="">Select Industry</option>';
                                $industrys = $this->db->select('i.id, i.industry')
                                    ->from('industry as i')
                                    ->join('industry_group as ig', 'ig.industry_id = i.id and ig.status = 1', 'left')
                                    ->where('i.status', 1)
                                    ->order_by('i.industry', 'ASC')
                                    ->get();

                                if ($industrys->num_rows()) {
                                    foreach ($industrys->result() as $row) {
                                        $sel = (!empty($form['industry_id']) && $form['industry_id'] == $row->id) ? "selected='selected'" : "";
                                        echo '<option value="' . $row->id . '" ' . $sel . '>' . $row->industry . '</option>';
                                    }
                                }
                                ?>

                                    
                                </select>
                            </div>
                            
                            <!-- <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Department</label>
                                <select id="department_id" name="department_id" class="form-control">
                                    <?php
                                    // echo '<option value="">Select Department</option>';
                                    // $departments = $this->db->where('status',1)->order_by('department', 'ASC')->get('department');
                                    
                                    // if ($departments->num_rows()) {
                                    //     foreach ($departments->result() as $rows){
                                    //         $sel = "";
                                    //                 if(!empty($form['department_id']) && $form['department_id'] == $rows->id){ $sel = "selected='selected'"; } 
                                    //         echo '<option value="' . $rows->id . '" '.$sel.'>' . $rows->department . '</option>';
                                    //     }
                                    // }
                                    ?>
                                </select>
                            </div> -->
                            
                            <!-- <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Role</label>
                                <select name="role_id" id="role_id" class="form-control">
                                </select>
                            </div> -->

                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Role</label>
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="">Select Role</option>
                                    <?php
                                    $roles = $this->db->where('status', 1)->order_by('role', 'ASC')->get('job_role')->result();
                                    foreach ($roles as $row) {
                                        $sel = (!empty($form['role_id']) && $form['role_id'] == $row->id) ? "selected='selected'" : "";
                                        echo "<option value='{$row->id}' {$sel}>{$row->role}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Experience</label>
                                <select name="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option value="0-1" <?php if(!empty($form['experience']) && $form['experience'] == "0-1"){ echo "selected='selected'"; } ?> >0 to 1 year</option>
                                    <option value="1-2" <?php if(!empty($form['experience']) && $form['experience'] == "1-2"){ echo "selected='selected'"; } ?> >1 to 2 years</option>
                                    <option value="2-5" <?php if(!empty($form['experience']) && $form['experience'] == "2-5"){ echo "selected='selected'"; } ?> >2 to 5 years</option>
                                    <option value="5+" <?php if(!empty($form['experience']) && $form['experience'] == "5+"){ echo "selected='selected'"; } ?>>5+ years</option>
                                </select>
                            </div>
                            
                            <!-- <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Eduction</label>
                                <select id="" name="" class="form-control">
                                    <?php
                                    // echo '<option value="">Select Eduction</option>';
                                    // $eduction = $this->db->where('status',1)->order_by('', 'ASC')->get('eduction');
                                    
                                    // if ($eduction->num_rows()) {
                                    //     foreach ($eduction->result() as $rows){
                                    //         $sel = "";
                                    //                 if(!empty($form['eduction_id']) && $form['eduction_id'] == $rows->id){ $sel = "selected='selected'"; } 
                                    //         echo '<option value="' . $rows->id . '" '.$sel.'>' . $rows->eduction . '</option>';
                                    //     }
                                    // }
                                    ?>
                                </select> 
                            </div> -->

                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Education</label>
                                <select id="education_id" name="education_id" class="form-control">
                                    <option value="">Select Education</option>
                                    <?php
                                    $education = $this->db->where('status', 1)->order_by('qualification', 'ASC')->get('isdm_education');
                                    if ($education->num_rows()) {
                                        foreach ($education->result() as $row) {
                                            $sel = (!empty($form['education_id']) && $form['education_id'] == $row->id) ? "selected='selected'" : "";
                                            echo '<option value="' . $row->id . '" ' . $sel . '>' . $row->qualification . '</option>';
                                        }
                                    }
                                    ?>
                                </select> 
                            </div>


                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Salary</label>
                                <select name="salary" class="form-control">
                                    <option value="">Select Salary</option>
                                    <option value="1-3L" <?php if(!empty($form['salary']) && $form['salary'] == "1-3L"){ echo "selected='selected'"; } ?> >1 L to 3 L</option>
                                    <option value="3-5L" <?php if(!empty($form['salary']) && $form['salary'] == "3-5L"){ echo "selected='selected'"; } ?> >3 L to 5 L</option>
                                    <option value="5-10L" <?php if(!empty($form['salary']) && $form['salary'] == "5-10L"){ echo "selected='selected'"; } ?> >5 L to 10 L</option>
                                    <option value="10+L" <?php if(!empty($form['salary']) && $form['salary'] == "10+L"){ echo "selected='selected'"; } ?> >10+ L</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Openings</label>
                                <input type="number" name="openings" class="form-control" min="1" placeholder="Enter Openings" value="<?php if(!empty($form['openings'])){ echo $form['openings']; } ?>">
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Job Type</label>
                                <select name="job_type" class="form-control">
                                    <option value="">Select Job Type</option>
                                    <option value="Permanent" <?php if(!empty($form['job_type']) && $form['job_type'] == "Permanent"){ echo "selected='selected'"; } ?> >Permanent</option>
                                    <option value="Contractual" <?php if(!empty($form['job_type']) && $form['job_type'] == "Contractual"){ echo "selected='selected'"; } ?> >Contractual</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Employment Type</label>
                                <select name="employment_type" class="form-control">
                                    <option value="">Select Employment Type</option>
                                    <option value="Full-time" <?php if(!empty($form['employment_type']) && $form['employment_type'] == "Full-time"){ echo "selected='selected'"; } ?> >Full-time</option>
                                    <option value="Part-time" <?php if(!empty($form['employment_type']) && $form['employment_type'] == "Part-time"){ echo "selected='selected'"; } ?> >Part-time</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Preferred Shift</label>
                                <select name="preferred_shift" class="form-control">
                                    <option value="">Select Preferred Shift</option>
                                    <option value="Day" <?php if(!empty($form['preferred_shift']) && $form['preferred_shift'] == "Day"){ echo "selected='selected'"; } ?> >Day</option>
                                    <option value="Night" <?php if(!empty($form['preferred_shift']) && $form['preferred_shift'] == "Night"){ echo "selected='selected'"; } ?>>Night</option>
                                    <option value="Flexible" <?php if(!empty($form['preferred_shift']) && $form['preferred_shift'] == "Flexible"){ echo "selected='selected'"; } ?>>Flexible</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Work Location</label>
                                <select name="work_location" class="form-control">
                                    <option value="">Select Work Location</option>
                                    <option value="Office" <?php if(!empty($form['work_location']) && $form['work_location'] == "Office"){ echo "selected='selected'"; } ?> >Office</option>
                                    <option value="Remote" <?php if(!empty($form['work_location']) && $form['work_location'] == "Remote"){ echo "selected='selected'"; } ?> >Remote</option>
                                    <option value="Hybrid" <?php if(!empty($form['work_location']) && $form['work_location'] == "Hybrid"){ echo "selected='selected'"; } ?>>Hybrid</option>
                                </select>
                            </div>

                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Fluancy In English</label>
                                <select name="fluancy_in_english" class="form-control">
                                    <option value="">Select Work Location</option>
                                    <option value="Beginner" <?php if(!empty($form['fluancy_in_english']) && $form['fluancy_in_english'] == "Beginner"){ echo "selected='selected'"; } ?> >Beginner</option>
                                    <option value="Intermediate" <?php if(!empty($form['fluancy_in_english']) && $form['fluancy_in_english'] == "Intermediate"){ echo "selected='selected'"; } ?> >Intermediate</option>
                                    <option value="Fluent" <?php if(!empty($form['fluancy_in_english']) && $form['fluancy_in_english'] == "Fluent"){ echo "selected='selected'"; } ?>>Fluent</option>
                                </select>
                            </div>

                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label">Select State</label>
                                <select class="form-select get_city" name="state_id" data-control="select2"
                                    data-placeholder="Select a State">
                                    <option value="">--Select--</option>
                                    <option></option>
                                    <?php
                                    $state = $this->db->order_by('STATE_NAME', 'ASC')->get('state');
                                    if ($state->num_rows()) {
                                        foreach ($state->result() as $row){
                                            $sel = "";
                                                    if(!empty($form['state_id']) && $form['state_id'] == $row->STATE_ID){ $sel = "selected='selected'"; } 
                                            echo '<option value="' . $row->STATE_ID . '" '.$sel.'>' . $row->STATE_NAME . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12 form-group-city">
                                <label class="form-label">Select District<span id="load"></span></label>
                                <select class="form-select list-cities" name="city_id" data-control="select2"
                                    data-placeholder="Select a Distric">
                                    <option></option>
                                    <?php
                                    if(!empty($form['state_id'])){
                                        $getCities = $this->SiteModel->get_city(['STATE_ID' => $form['state_id']]);
                                        if ($getCities->num_rows()) {
                                            foreach ($getCities->result() as $city) {
                                                echo '<option value="' . $city->DISTRICT_ID . '"  ' . ($city->DISTRICT_ID == $form['city_id'] ? 'selected' : '') . '>' . $city->DISTRICT_NAME . '</option>';
                                            }
                                        }
                                    }
                                    
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12 row">
                                <label class="form-label required">Select Key Skill's</label>
                                <div id="list_job_role" class="row"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    {publish_button}
                </div>
            </div>
        </form>
    </div>
</div>
<?php } else { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Job Details</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label class="form-label">Job Title:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                        <p style="margin: 0;"><?php echo !empty($form['job_title']) ? $form['job_title'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label">Job Highlights:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['job_highlights']) ? $form['job_highlights'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Industry:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                            <?php
                            echo '<option value="">Select Industry</option>';
                            $industrys = $this->db->select('i.id, i.industry')
                                ->from('industry as i')
                                ->join('industry_group as ig', 'ig.industry_id = i.id and ig.status = 1', 'left')
                                ->where('i.status', 1)
                                ->order_by('i.industry', 'ASC')
                                ->get();

                            if ($industrys->num_rows()) {
                                foreach ($industrys->result() as $row) {
                                    $sel = (!empty($form['industry_id']) && $form['industry_id'] == $row->id) ? "selected='selected'" : "";
                                    echo '<option value="' . $row->id . '" ' . $sel . '>' . $row->industry . '</option>';
                                }
                            }
                            ?>

                            </p>
                        </div>
                    </div>

                    <!-- <div class="col-lg-6">
                        <label class="form-label">Department:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                                <?php
                                    // $departments = $this->db->where('status',1)->order_by('department', 'ASC')->get('department');
                                    
                                    // if ($departments->num_rows()) {
                                    //     foreach ($departments->result() as $rows){
                                    //         $sel = "";
                                    //                 if(!empty($form['department_id']) && $form['department_id'] == $rows->id){ echo $rows->department; } 
                                    //     }
                                    // }
                                    ?>
                            </p>
                        </div>
                    </div> -->

                    <div class="col-lg-6">
                        <label class="form-label">Role:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                            <label class="form-label required">Role</label>
                            <select name="role_id" id="role_id" class="form-control">
                                <option value="">Select Role</option>
                                <?php
                                $roles = $this->db->where('status', 1)->order_by('role', 'ASC')->get('job_role')->result();
                                foreach ($roles as $row) {
                                    $sel = (!empty($form['role_id']) && $form['role_id'] == $row->id) ? "selected='selected'" : "";
                                    echo "<option value='{$row->id}' {$sel}>{$row->role}</option>";
                                }
                                ?>
                            </select>
                        </div>

                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Experience:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['experience']) ? $form['experience'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                        <label class="form-label required">Education</label>
                        <select id="education_id" name="education_id" class="form-control">
                            <option value="">Select Education</option>
                            <?php
                            $education = $this->db->where('status', 1)->order_by('qualification', 'ASC')->get('isdm_education');
                            if ($education->num_rows()) {
                                foreach ($education->result() as $row) {
                                    $sel = (!empty($form['education_id']) && $form['education_id'] == $row->id) ? "selected='selected'" : "";
                                    echo '<option value="' . $row->id . '" ' . $sel . '>' . $row->qualification . '</option>';
                                }
                            }
                            ?>
                        </select> 
                    </div>


                    <div class="col-lg-6">
                        <label class="form-label">Salary:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['salary']) ? $form['salary'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Openings:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['openings']) ? $form['openings'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Job Type:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['job_type']) ? $form['job_type'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Employment Type:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['employment_type']) ? $form['employment_type'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Preferred Shift:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['preferred_shift']) ? $form['preferred_shift'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Work Location:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['work_location']) ? $form['work_location'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Fluancy In English:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;"><?php echo !empty($form['fluancy_in_english']) ? $form['fluancy_in_english'] : "N/A"; ?></p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">State:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                                <?php
                                    $state = $this->db->order_by('STATE_NAME', 'ASC')->get('state');
                                    if ($state->num_rows()) {
                                        foreach ($state->result() as $row){
                                                    if(!empty($form['state_id']) && $form['state_id'] == $row->STATE_ID){ echo $row->STATE_NAME; } 
                                        }
                                    }
                                    ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">District:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                                <?php
                                    if(!empty($form['state_id'])){
                                        $getCities = $this->SiteModel->get_city(['STATE_ID' => $form['state_id']]);
                                        if ($getCities->num_rows()) {
                                            foreach ($getCities->result() as $city) {
                                                if(!empty($form['city_id']) && $form['city_id'] == $city->DISTRICT_ID){ echo $city->DISTRICT_NAME; } 
                                               
                                            }
                                        }
                                    }
                                    
                                    ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label">Key Skills:</label>
                        <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">
                                <?php 
                                $key_skills = json_decode($form['key_skills'], true);
                                $job_skill          = $this->db->select('*')
                                ->from('job_skill')
                                ->where('status',1)
                                ->get()->result_array(); 
                                foreach($job_skill as $key => $val){
                                    if(in_array($val['id'], $key_skills)){
                                        ?><label class="badge badge-info"><?php echo $val['skill']; ?></label>&nbsp;<?php
                                    }
                                }
                                ?>
                                    
                                </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="job_mgmt?id=<?php echo $form['id']; ?>&type=E" class="btn btn-primary">Edit Job</a>
                <a href="list-jobs" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>

<?php } ?>

