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
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle w-100 text-left" type="button" id="industryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span id="industryDropdownText">Select Industry</span>
        </button>
        <div class="dropdown-menu w-100 p-3" aria-labelledby="industryDropdown" style="max-height: 300px; overflow-y: auto;">
            <?php
            $industries = $this->db->select('id, industry')->from('industry')->where('status',1)->order_by('industry','ASC')->get()->result_array();
            foreach($industries as $val){
                $chk = (!empty($selected_industries) && in_array($val['id'], $selected_industries)) ? "checked='checked'" : "";
                echo '<div class="form-check">
                        <input class="form-check-input industry-checkbox" type="checkbox" name="industries[]" value="'.$val['id'].'" id="industry_'.$val['id'].'" '.$chk.'>
                        <label class="form-check-label" for="industry_'.$val['id'].'">'.$val['industry'].'</label>
                      </div>';
            }
            ?>
        </div>
    </div>
    <div id="selectedIndustries" class="mt-2"></div>
</div>

<div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
    <label class="form-label required">Key Skill's</label>
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle w-100 text-left" type="button" id="keySkillsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span id="keySkillsDropdownText">Select Key Skills</span>
        </button>
        <div class="dropdown-menu w-100 p-3" aria-labelledby="keySkillsDropdown" style="max-height: 300px; overflow-y: auto;">
            <?php
            $job_skill = $this->db->select('id, skill')->from('job_skill')->where('status',1)->order_by('skill','ASC')->get()->result_array();
            foreach($job_skill as $val){
                $chk = (isset($key_skills) && in_array($val['id'], $key_skills)) ? "checked='checked'" : "";
                echo '<div class="form-check">
                        <input class="form-check-input skill-checkbox" type="checkbox" name="key_skills[]" value="'.$val['id'].'" id="skill_'.$val['id'].'" '.$chk.'>
                        <label class="form-check-label" for="skill_'.$val['id'].'">'.$val['skill'].'</label>
                      </div>';
            }
            ?>
        </div>
    </div>
    <div id="selectedKeySkills" class="mt-2"></div>
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

<!-- Your existing form and HTML stays exactly the same above -->

<!-- Place this block at the very end of your file -->
<!-- ✅ Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ✅ Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Industry & Key Skill Dropdown Functionality -->
<script>
$(document).ready(function () {
    // Update the text in the dropdown button and show badges
    function updateDropdownText(checkboxClass, displayId, buttonTextId, defaultText) {
        let selected = [];
        $(checkboxClass + ':checked').each(function () {
            selected.push($(this).next('label').text());
        });
        $(buttonTextId).text(selected.length > 0 ? selected.length + ' selected' : defaultText);

        let badges = selected.map(item => `<span class="badge badge-info mr-1">${item}</span>`);
        $(displayId).html(badges.join(''));
    }

    // Events
    $(document).on('change', '.industry-checkbox', function () {
        updateDropdownText('.industry-checkbox', '#selectedIndustries', '#industryDropdownText', 'Select Industry');
    });

    // If you later convert Key Skills to dropdown, reuse this:
    // $(document).on('change', '.skill-checkbox', function () {
    //     updateDropdownText('.skill-checkbox', '#selectedKeySkills', '#keySkillsDropdownText', 'Select Key Skills');
    // });

    // Initialize on page load
    updateDropdownText('.industry-checkbox', '#selectedIndustries', '#industryDropdownText', 'Select Industry');
});
</script>
