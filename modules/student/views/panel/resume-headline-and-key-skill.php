<div class="row">
    <form action="" class="set-resume-data" id="set_resume_data">
        <input type="hidden" name="student_id" value="{student_id}">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resume Headline & Key Skill's</h3>
                    <div class="card-toolbar">
                        {publish_button}
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        $languages = [
                                    "English",
                                    "Assamese",
                                    "Bengali",
                                    "Bodo",
                                    "Dogri",
                                    "Gujarati",
                                    "Hindi",
                                    "Kannada",
                                    "Kashmiri",
                                    "Konkani",
                                    "Maithili",
                                    "Malayalam",
                                    "Manipuri",
                                    "Marathi",
                                    "Nepali",
                                    "Odia",
                                    "Punjabi",
                                    "Sanskrit",
                                    "Santali",
                                    "Sindhi",
                                    "Tamil",
                                    "Telugu",
                                    "Urdu"
                                ];
                    ?>
                    <div class="row">
                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Resume Headline</label>
                            <input type="text" name="resume_headline" value="{resume_headline}" class="form-control" placeholder="Enter Resume Headline">
                        </div>
                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Profile Summary</label>
                            <textarea name="profile_summary" class="aryaeditor" placeholder="Enter Profile Summary"><?php echo $profile_summary; ?></textarea>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                            <label class="form-label required">Experience</label>
                            <select name="experience" class="form-control">
                                <option value="">Select Experience</option>
                                <option value="0-1" <?php if(!empty($experience) && $experience == "0-1"){ echo "selected='selected'"; } ?> >0 to 1 year</option>
                                <option value="1-2" <?php if(!empty($experience) && $experience == "1-2"){ echo "selected='selected'"; } ?> >1 to 2 years</option>
                                <option value="2-5" <?php if(!empty($experience) && $experience == "2-5"){ echo "selected='selected'"; } ?> >2 to 5 years</option>
                                <option value="5+" <?php if(!empty($experience) && $experience == "5+"){ echo "selected='selected'"; } ?>>5+ years</option>
                            </select>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                            <label class="form-label required">Fluancy In English</label>
                            <select name="fluancy_in_english" class="form-control">
                                <option value="">Select Work Location</option>
                                <option value="Beginner" <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Beginner"){ echo "selected='selected'"; } ?> >Beginner</option>
                                <option value="Intermediate" <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Intermediate"){ echo "selected='selected'"; } ?> >Intermediate</option>
                                <option value="Fluent" <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Fluent"){ echo "selected='selected'"; } ?>>Fluent</option>
                            </select>
                        </div>
                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Industry</label>
                            <div id="wwlist_job_role" class="row">
                            <?php
                                $job_skill1 = $this->db->select('i.id, i.industry')
                                                ->from('industry as i')
                                                ->where('i.status',1)->get()->result_array();
                                $html = '';
                                foreach($job_skill1 as $key => $val){
                                    $chk = "";
                                    if(in_array($val['id'], $industries)){
                                        $chk = "checked='checked'";
                                    }
                                    $html .='<div class="form-group col-md-6 mt-2">
                                                <input type="checkbox" name="industries[]" id="industri_'.$val['id'].'" class="form-check-input group_industri" value="'.$val['id'].'" '.$chk.'  />
                                                <label class="form-label">'.$val['industry'].'</label>
                                            </div>';
                                }
                                echo $html;
                            ?>
                            </div>
                        </div>
                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Key Skill's</label>
                            <div id="list_job_role" class="row">
                            <?php
                                $job_skill = $this->db->select('*')->from('job_skill')->where('status',1)->get()->result_array();
                                $html = '';
                                foreach($job_skill as $key => $val){
                                    $chk = "";
                                    if(in_array($val['id'], $key_skills)){
                                        $chk = "checked='checked'";
                                    }
                                    $html .='<div class="form-group col-md-3 mt-2">
                                                <input type="checkbox" name="key_skills[]" id="skill_'.$val['id'].'" class="form-check-input group_skill" value="'.$val['id'].'" '.$chk.'  />
                                                <label class="form-label">'.$val['skill'].'</label>
                                            </div>';
                                }
                                echo $html;
                            ?>
                            </div>
                        </div>

                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Languages Known</label>
                            <div id="list_job_role" class="row">
                            <?php
                                $html = '';
                                foreach($languages  as $key => $val){
                                    $chk = "";
                                    if(in_array($val, $pan_languages)){
                                        $chk = "checked='checked'";
                                    }
                                    $html .='<div class="form-group col-md-3 mt-2">
                                                <input type="checkbox" name="pan_languages[]" id="pan_languages_'.$val.'" class="form-check-input group_pan_languages" value="'.$val.'" '.$chk.'  />
                                                <label class="form-label">'.$val.'</label>
                                            </div>';
                                }
                                echo $html;
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>