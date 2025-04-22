<div class="row">
    <div class="col-md-12">
        <form id="form" action="" method="POST">
            
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Student Enquiry Form</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Enquiry Date</label>
                                <input type="text" name="enquiry_date" class="form-control current-date"
                                    placeholder="Select Enquiry Date" value="<?= $this->ki_theme->date() ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-4 col-xs-12 col-sm-12">
                                <label class="form-label required required">Student Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Student Name">
                            </div>
                            <div class="form-group mb-4 col-lg-2 col-xs-12 col-sm-12">
                                <label class="form-label required">Gender</label>
                                <select name="gender" class="form-select" data-control="select2"
                                    data-placeholder="Select Gender">
                                    <option></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Date of birth</label>
                                <input type="date" name="dob" class="form-control" placeholder="Select date of birth">
                            </div>



                            <div class="form-group mb-4 col-lg-4 col-xs-12 col-sm-12">
                                <label class="form-label required">Center</label>
                                <?php
                                $center_id = 0;
                                if ($this->center_model->isCenter()) {
                                    $center_id = $this->center_model->loginId();
                                    $this->db->where('id', $center_id);
                                }
                                ?>
                                <select class="form-select" id="centre_id" name="center_id" data-control="select2"
                                    data-placeholder="Select a Center"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
$list = $this->db->where('type', 'center')->get('centers')->result();
foreach ($list as $row) {
    $selected = $center_id == $row->id ? 'selected' : '';
    if (isset($exam['id']) && !empty($exam['center_id'])) {
        $selected = $exam['center_id'] == $row->id ? 'selected' : '';
    }

    echo '<option value="' . $row->id . '" ' 
    . $selected 
    . ' data-search="' . strtolower($row->name . ' ' . $row->institute_name) . '"'
    . ' data-kt-rich-content-subcontent="' . $row->institute_name . '">'
    . $row->name . ' (' . $row->institute_name . ')</option>';

}
?>
                                </select>
                            </div>

                            <div class="form-group mb-4 col-lg-2 col-xs-12 col-sm-12">
                                <label class="form-label required">Enquiry No.</label>
                                <input type="text" name="enquiry_no" class="form-control" placeholder="Enter Enquiry NO.">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Course</label>
                                <select class="form-select" name="course_id" data-control="select2"
                                    data-placeholder="Select a Course" data-allow-clear="true">
                                    <option></option>
                                   </select>
                            </div>



                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Batch</label>
                                <select class="form-select" name="batch_id" id="batch_id" data-control="select2"
                                    data-placeholder="Select a Batch">
                                    <option></option>
                                </select>
                            </div>


                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Whatsapp Number</label>
                                <div class="input-group">
                                    <input type="text" name="contact_number" class="form-control"
                                        placeholder="Whatsapp Number" autocomplete="off">
                                    <span class="input-group-text" id="basic-addon2"
                                        style="width:100px;padding:0px!important">
                                        <select name="contact_no_type" data-control="select2"
                                            data-placeholder="Whatsapp Mobile Type" class="form-control">
                                            <?php
                                            foreach ($this->ki_theme->project_config('mobile_types') as $key => $value)
                                                echo "<option value='{$key}'>{$value}</option>";
                                            ?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label">Alternative Mobile</label>
                                <div class="input-group">
                                    <input type="text" name="alternative_mobile" class="form-control"
                                        placeholder="Mobile" autocomplete="off">
                                    <span class="input-group-text" id="basic-addon2"
                                        style="width:100px;padding:0px!important">
                                        <select name="alt_mobile_type" data-control="select2"
                                            data-placeholder="Alternative Mobile Type" class="form-control">
                                            <?php
                                            foreach ($this->ki_theme->project_config('mobile_types') as $key => $value)
                                                echo "<option value='{$key}'>{$value}</option>";
                                            ?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">E-Mail ID</label>
                                <input type="email" name="email_id" class="form-control" placeholder="Enter E-Mail ID">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Father Name</label>
                                <input type="text" name="father_name" class="form-control"
                                    placeholder="Enter Father Name">
                            </div>
                            <!-- <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Father Mobile</label>
                                <input type="text" name="father_mobile" class="form-control"
                                    placeholder="Enter Father MObile">
                            </div> -->
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Mother Name</label>
                                <input type="text" name="mother_name" id="aadhar_number" class="form-control"
                                    placeholder="Enter Mothe Name">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Family ID</label>
                                <input type="text" name="family_id" class="form-control" placeholder="Enter family ID">
                            </div>
                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Address</label>
                                <textarea class="form-control" name="address" placeholder="Address"></textarea>
                            </div>

                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Pincode</label>
                                <input class="form-control" name="pincode" placeholder="Enter Pincode">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Select State </label>
                                <select class="form-select get_city" name="state_id" data-control="select2"
                                    data-placeholder="Select a State">
                                    <option value="">--Select--</option>
                                    <option></option>
                                    <?php
                                    $state = $this->db->order_by('STATE_NAME', 'ASC')->get('state');
                                    if ($state->num_rows()) {
                                        foreach ($state->result() as $row)
                                            echo '<option value="' . $row->STATE_ID . '">' . $row->STATE_NAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12 form-group-city">
                                <label class="form-label required">Select Distric <span id="load"></span></label>
                                <select class="form-select list-cities" name="city_id" data-control="select2"
                                    data-placeholder="Select a City">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label"> Passed Exam</label>
                                <input type="text" name="passed_exam" class="form-control"
                                    placeholder="Enter Passed Exam">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Marks(%) / Grade</label>
                                <input type="text" name="marks" class="form-control" placeholder="Enter Marks/Grade">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Board</label>
                                <input type="text" name="board" class="form-control" placeholder="Enter Board">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label ">Passing Year</label>
                                <input type="text" name="passing_year" class="form-control single-year"
                                    placeholder="Enter Passing Year">
                            </div>

                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Estimated Join Date</label>
                                <input type="date" name="estimatedjoin_date" class="form-control selectdate"
                                    placeholder="Select Estimated Join Date" value="">
                            </div>

                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Follow Up Date</label>
                                <input type="date" name="followup_date" class="form-control selectdate"
                                    placeholder="Select Follow Up Date" value="">
                            </div>
                        </div>
                    </div>


                    <div class="card-footer">
                        {publish_button}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

