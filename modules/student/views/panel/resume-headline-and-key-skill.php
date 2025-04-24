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
                            <input type="text" name="resume_headline" value="{resume_headline}" class="form-control"
                                placeholder="Enter Resume Headline">
                        </div>
                        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                            <label class="form-label required">Profile Summary</label>
                            <textarea name="profile_summary" class="aryaeditor"
                                placeholder="Enter Profile Summary"><?php echo $profile_summary; ?></textarea>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                            <label class="form-label required">Experience</label>
                            <select name="experience" class="form-control">
                                <option value="">Select Experience</option>
                                <option value="0-1"
                                    <?php if(!empty($experience) && $experience == "0-1"){ echo "selected='selected'"; } ?>>
                                    0 to 1 year</option>
                                <option value="1-2"
                                    <?php if(!empty($experience) && $experience == "1-2"){ echo "selected='selected'"; } ?>>
                                    1 to 2 years</option>
                                <option value="2-5"
                                    <?php if(!empty($experience) && $experience == "2-5"){ echo "selected='selected'"; } ?>>
                                    2 to 5 years</option>
                                <option value="5+"
                                    <?php if(!empty($experience) && $experience == "5+"){ echo "selected='selected'"; } ?>>
                                    5+ years</option>
                            </select>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                            <label class="form-label required">Fluancy In English</label>
                            <select name="fluancy_in_english" class="form-control">
                                <option value="">Select Fluancy In English</option>
                                <option value="Beginner"
                                    <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Beginner"){ echo "selected='selected'"; } ?>>
                                    Beginner</option>
                                <option value="Intermediate"
                                    <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Intermediate"){ echo "selected='selected'"; } ?>>
                                    Intermediate</option>
                                <option value="Fluent"
                                    <?php if(!empty($fluancy_in_english) && $fluancy_in_english == "Fluent"){ echo "selected='selected'"; } ?>>
                                    Fluent</option>
                            </select>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
    <label class="form-label required">Industry</label>
    
    <div class="form-control p-0 border-0">
        <div class="dropdown">
            <button class="btn dropdown-toggle text-left w-100 py-2 px-3 border form-control" type="button"
                id="industryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="text-align: left;">
                <span id="industryDropdownText">Select Industry</span>
            </button>
            <div class="dropdown-menu w-100 p-3" aria-labelledby="industryDropdown" style="max-height: 300px; overflow-y: auto;">
                <?php
                $industries = $this->db->select('id, industry')->from('industry')->where('status',1)->order_by('industry','ASC')->get()->result_array();
                foreach($industries as $val){
                    $chk = (!empty($selected_industries) && in_array($val['id'], $selected_industries)) ? "checked='checked'" : "";
                    echo '<div class="form-check ml-2">
                            <input class="form-check-input industry-checkbox "  type="checkbox" name="industries[]" value="'.$val['id'].'" id="industry_'.$val['id'].'" '.$chk.'>
                            <label class="form-check-label my-1"  for="industry_'.$val['id'].'">'.$val['industry'].'</label>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div id="selectedIndustries" class="mt-2"></div>
</div>

                        
<div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
    <label class="form-label required">Role</label>

    <div class="form-control p-0 border-0">
        <div class="dropdown">
            <button class="btn dropdown-toggle text-left w-100 py-2 px-3 border form-control" type="button"
                id="roleDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="text-align: left;">
                <span id="roleDropdownText">Select Role</span>
            </button>

            <div class="dropdown-menu w-100 p-3" aria-labelledby="roleDropdown" id="roleDropdownList"
                style="max-height: 300px; overflow-y: auto;">
                <!-- Roles will be loaded here via JS -->
            </div>
        </div>
    </div>

    <div id="selectedRoles" class="mt-2"></div>
</div>


<div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
    <label class="form-label required">Key Skill's</label>

    <div class="form-control p-0 border-0">
        <div class="dropdown">
            <button class="btn dropdown-toggle text-left w-100 py-2 px-3 border form-control" type="button"
                id="keySkillsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="text-align: left;">
                <span id="keySkillsDropdownText">Select Key Skills</span>
            </button>
            <div class="dropdown-menu w-100 p-3" aria-labelledby="keySkillsDropdown" style="max-height: 300px; overflow-y: auto;">
                
            </div>
        </div>
    </div>

    <div id="selectedKeySkills" class="mt-2"></div>
</div>


                        <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
    <label class="form-label required">Languages Known</label>

    <div class="form-control p-0 border-0">
        <div class="dropdown">
            <button class="btn dropdown-toggle text-left w-100 py-2 px-3 border form-control" type="button"
                id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                style="text-align: left;">
                <span id="languageDropdownText">Select Languages</span>
            </button>
            <div class="dropdown-menu w-100 p-3" aria-labelledby="languageDropdown" style="max-height: 300px; overflow-y: auto;">
                <?php
                $languages = [
                    "English", "Assamese", "Bengali", "Bodo", "Dogri", "Gujarati", "Hindi",
                    "Kannada", "Kashmiri", "Konkani", "Maithili", "Malayalam", "Manipuri", "Marathi",
                    "Nepali", "Odia", "Punjabi", "Sanskrit", "Santali", "Sindhi", "Tamil", "Telugu", "Urdu"
                ];

                foreach($languages as $val){
                    $chk = (isset($pan_languages) && in_array($val, $pan_languages)) ? "checked='checked'" : "";
                    echo '<div class="form-check ml-2">
                            <input class="form-check-input language-checkbox" type="checkbox" name="pan_languages[]" value="'.$val.'" id="language_'.$val.'" '.$chk.'>
                            <label class="form-check-label my-1" for="language_'.$val.'">'.$val.'</label>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div id="selectedLanguages" class="mt-2"></div>
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
    var base_url = "<?= base_url(); ?>";

    $(document).on('change', '.industry-checkbox', function () {
        let selectedIndustries = $('.industry-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

    $.ajax({
    url: base_url + 'student/get_job_roles_for_industries',
    type: 'POST',
    data: { industry_ids: selectedIndustries },
    dataType: 'json',
    success: function (response) {
        if (response.status) {
            let html = '';
            response.roles.forEach(role => {
                html += `<div class="form-check ml-2">
                            <input class="form-check-input role-checkbox" type="checkbox" name="role_id[]" value="${role.id}" id="role_${role.id}">
                            <label class="form-check-label my-1" for="role_${role.id}">${role.role}</label>
                         </div>`;
            });
            $('#roleDropdownList').html(html);
            $('#roleDropdownText').text('Select Role');
            $('#selectedRoles').html('');
        } else {
            $('#roleDropdownList').html('<span class="text-danger">No roles found.</span>');
        }
    },
    error: function () {
        alert('Failed to load roles. Check server or controller.');
    }
});

    });

    $(document).on('change', '.role-checkbox', function () {
    let selectedRoles = $('.role-checkbox:checked').map(function () {
        return $(this).val();
    }).get();

    $.ajax({
        url: base_url + 'student/get_skills_for_roles',
        type: 'POST',
        data: { role_ids: selectedRoles },
        dataType: 'json',
        success: function (response) {
            if (response.status) {
                let html = '';
                response.skills.forEach(skill => {
                    html += `<div class="form-check ml-2">
                                <input class="form-check-input skill-checkbox" type="checkbox" name="key_skills[]" value="${skill.id}" id="skill_${skill.id}">
                                <label class="form-check-label my-1" for="skill_${skill.id}">${skill.skill}</label>
                             </div>`;
                });
                $('#keySkillsDropdownText').text('Select Key Skills');
                $('#selectedKeySkills').html('');
                $('.dropdown-menu[aria-labelledby="keySkillsDropdown"]').html(html);
            }
        },
        error: function () {
            alert('Failed to load skills. Check server.');
        }
    });
});

</script>
