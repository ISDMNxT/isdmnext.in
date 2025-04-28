<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="tcourse" name="tcourse" value="<?php if(!empty($data['student_id'])) { echo $data['course_id']; } ?>">
        <input type="hidden" id="tbatch" name="tbatch" value="<?php if(!empty($data['student_id'])) { echo $data['batch_id']; } ?>">
        <input type="hidden" id="tdistrict" name="tdistrict" value="<?php if(!empty($data['student_id'])) { echo $data['DISTRICT_ID']; } ?>">
        <form id="form" action="" method="POST">
            <input type="hidden" id="enquiry_id" name="enquiry_id" value="<?php if(!empty($data['student_id'])) { echo $data['student_id']; } ?>">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Student Admission Form</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <?php if (CHECK_PERMISSION('REFERRAL_ADMISSION') && $this->center_model->isAdmin()) {
                            ?>
                             <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="liststudent" class="form-label required">Referral
                                        Student By</label>
                                    <select name="referral_id" data-control="select2" data-placeholder="Select Student"
                                        class="form-select first m-h-100px" data-allow-clear="true">
                                        <option></option>

                                    </select>
                                </div>
                            </div> 
                            <?php  } ?>
                        <div class="row">
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Admission Date</label>
                                <input type="text" name="admission_date" class="form-control current-date"
                                    placeholder="Select Admission Date" value="<?= $this->ki_theme->date() ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-4 col-xs-12 col-sm-12">
                                <label class="form-label required required">Student Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Student Name" value="<?php if(!empty($data['student_id'])) { echo $data['student_name']; } ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-2 col-xs-12 col-sm-12">
                                <label class="form-label required">Gender</label>
                                <select name="gender" class="form-select" data-control="select2"
                                    data-placeholder="Select Gender">
                                    <option></option>
                                    <option value="male" <?php if(!empty($data['student_id']) && $data['gender'] == 'male') { echo "selected='selected'"; } ?> >Male</option>
                                    <option value="female" <?php if(!empty($data['student_id']) && $data['gender'] == 'female') { echo "selected='selected'"; } ?> >Female</option>
                                    <option value="other" <?php if(!empty($data['student_id']) && $data['gender'] == 'other') { echo "selected='selected'"; } ?> >Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Date of birth</label>
                                <input type="text" value="<?php if(!empty($data['student_id'])) { echo $data['dob']; } ?>" name="dob" class="form-control" placeholder="Select date of birth">
                            </div>

                            <div class="form-group mb-4 col-lg-4 col-xs-12 col-sm-12">
                                <label class="form-label required">Center</label>
                                <?php
                                $center_id = 0;
                                if ($this->center_model->isCenter()) {
                                    $center_id = $this->center_model->loginId();
                                    $this->db->where('id', $center_id);
                                } else {
                                    if(!empty($data['student_id'])) { $center_id = $data['institute_id']; }
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
                                <label class="form-label required">Roll No.</label>
                                <input type="text" name="roll_no" class="form-control" placeholder="Enter Roll NO.">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Course</label>
                                <select class="form-select" name="course_id" data-control="select2"
                                    data-placeholder="Select a Course" data-allow-clear="true">
                                    <option></option>
                                    <?php
                                    // $listCourse = $this->db->get('course');
                                    // foreach ($listCourse->result() as $row) {
                                    //     echo '<option value="' . $row->id . '">' . $row->course_name . '</option>';
                                    // }
                                    ?>
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
                                    <input type="number" name="contact_number" class="form-control"
                                        placeholder="Whatsapp Number" autocomplete="off" value="<?php if(!empty($data['student_id'])) { echo $data['contact_number']; } ?>">
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
                                    <input type="number" name="alternative_mobile" class="form-control"
                                        placeholder="Mobile" autocomplete="off" value="<?php if(!empty($data['student_id'])) { echo $data['alternative_mobile']; } ?>">
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
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['email']; } ?>" type="email" name="email_id" class="form-control" placeholder="Enter E-Mail ID">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Father Name</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['father_name']; } ?>" type="text" name="father_name" class="form-control"
                                    placeholder="Enter Father Name">
                            </div>
                            <!-- <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Father Mobile</label>
                                <input type="text" name="father_mobile" class="form-control"
                                    placeholder="Enter Father MObile">
                            </div> -->
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Mother Name</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['mother_name']; } ?>" type="text" name="mother_name" id="aadhar_number" class="form-control"
                                    placeholder="Enter Mothe Name">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Family ID</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['family_id']; } ?>" type="text" name="family_id" class="form-control" placeholder="Enter family ID">
                            </div>
                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Address</label>
                                <textarea class="form-control" name="address" placeholder="Address"><?php if(!empty($data['student_id'])) { echo $data['address']; } ?></textarea>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Upload Photo</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Pincode</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['pincode']; } ?>" class="form-control" name="pincode" placeholder="Enter Pincode">
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
                                        foreach ($state->result() as $row){
                                            $sel = "";
                                            if(!empty($data['STATE_ID']) && $data['STATE_ID'] == $row->STATE_ID){
                                                $sel = "selected='selected'";
                                            }
                                            echo '<option value="' . $row->STATE_ID . '" '.$sel.'>' . $row->STATE_NAME . '</option>';
                                        }
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
                            <!-- <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Password</label>
                                <input type="text" name="password" class="form-control" placeholder="Enter">
                            </div> -->
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label"> Passed Exam</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['passed_exam']; } ?>" type="text" name="passed_exam" class="form-control"
                                    placeholder="Enter Passed Exam">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Marks(%) / Grade</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['marks']; } ?>" type="text" name="marks" class="form-control" placeholder="Enter Marks/Grade">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label">Board</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['board']; } ?>" type="text" name="board" class="form-control" placeholder="Enter Board">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label ">Passing Year</label>
                                <input value="<?php if(!empty($data['student_id'])) { echo $data['passing_year']; } ?>" type="text" name="passing_year" class="form-control single-year"
                                    placeholder="Enter Passing Year">
                            </div>
                            <div class="card card-body">
                                <h4>Upload Documents</h4>
                                <div class="row">
                                    <div class="col-md-3 mb-4">
                                        <div class="form-control">
                                            <label for="adhar_front" class="form-label required">Aadhar Card
                                                Card</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9 mb-4">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="adhar_card" id="adhar_front">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3 mb-4">
                                        <div class="form-control">
                                            <label for="adhar_back" class="form-label required">Aadhar Card Back</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9 mb-4">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="adhar_back" id="adhar_back">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                        <?php
                                        $uploadDocuments = $this->ki_theme->project_config('upload_ducuments');
                                        foreach ($uploadDocuments as $key => $value) {
                                            ?>
                                            <div class="col-md-3 mb-4">
                                                <div class="form-group">
                                                    <label for="<?=$key?>" class="form-label form-control"><?=$value?></label>
                                                    <input type="hidden" name="upload_docs[title][]" class="form-control"
                                                        value="<?=$key?>">
                                                </div>
                                            </div>
                                            <div class="col-md-9 mb-4">
                                                <div class="form-group">
                                                    <input type="file" class="form-control" id="<?=$key?>" name="upload_docs[file][]">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>

                            <div class="card card-body" style="margin-top: 20px;">
                                <h4>Fee Section</h4>
                                <div class="row">
                                    <table class="temp-list table table-striped table-bordered border-warning">
                                        <thead>
                                            <tr>
                                                <th>Fee Type</th>
                                                <th>Fee Period</th>
                                                <th>Payment Type</th>
                                                <th width="15%">Total Fee</th>
                                                <th width="15%">Paid Amount</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="feeTableBody">
                                            <tr id="fee_1">
                                                <td>
                                                    <select name="fees_type[]" class="form-select fee-type">
                                                        <option value="course_fees" selected>Course Fee</option>
                                                        <option value="admission_fees">Admission Fee</option>
                                                        <option value="registration_fees">Registration Fee</option>
                                                        <option value="exam_fees">Exam Fee</option>
                                                        <option value="other_fees">Other Fee</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="fees_period[]" class="form-select fee-period">
                                                        <option value="onetime" selected>One Time</option>
                                                        <option value="monthly">Monthly</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="fees_payment_type[]" class="form-select payment-type">
                                                        <option value="cash" selected>Cash</option>
                                                        <option value="cheque">Cheque</option>
                                                        <option value="upi">UPI</option>
                                                    </select>
                                                </td>
                                                <td class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                                                        <input type="number" class="form-control form-control-sm temp-amount" id="fee_amount_first" name="fees_amount[]" placeholder="Total Fee" 
                                                            autocomplete="off">
                                                    </div>
                                                </td>
                                                <td class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                                                        <input type="number" class="form-control form-control-sm disount-temp-amount" placeholder="Paid Amount"
                                                            name="fees_paid[]" autocomplete="off">
                                                    </div>
                                                </td>
                                                <td>
                                                    <textarea name="fees_description[]" rows="1" class="form-control" placeholder="Description"></textarea>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="add_more_temp"></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="text-align: right; margin-top: 10px;">
                                        <a href="javascript:void(0);" class="add_more badge badge-success" title="Add More">Add More</a>
                                    </div>
                                </div>
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

<script>
// Function to check for duplicate rows
function validateAllRows() {
    let rows = document.querySelectorAll('#feeTableBody tr');
    let combinations = [];

    // Loop through all rows and collect combinations of "Fee Type", "Fee Period", and "Payment Type"
    for (let row of rows) {
        let feeType = row.querySelector('.fee-type').value;
        let feePeriod = row.querySelector('.fee-period').value;
        let paymentType = row.querySelector('.payment-type').value;

        // Create a combination string to check uniqueness
        let combination = feeType + '-' + feePeriod + '-' + paymentType;

        // Check if this combination already exists
        if (combinations.includes(combination)) {
            alert('Duplicate combination found: ' + combination);
            return false; // Stop if any duplicate is found
        } else {
            combinations.push(combination); // Store the unique combination
        }
    }
    return true; // No duplicates, all good
}

// Add More Row
document.querySelector('.add_more').addEventListener('click', function () {
    if (validateAllRows()) {
        let firstRow = document.querySelector('#feeTableBody tr');
        // Ensure the first row exists before accessing its values
        if (firstRow) {
            // Clone the first row and append it as a new row
            let newRow = firstRow.cloneNode(true);
            newRow.id = '';  // Remove ID to avoid duplicates
            // Reset the cloned row values
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('input').forEach(input => input.id = '');
            newRow.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = '');

            // Update the action cell to remove the "Add More" link from new rows and add a "Remove" link
            let actionCell = newRow.querySelector('td:last-child');
            actionCell.innerHTML = '<a href="javascript:void(0);" class="remove_row badge badge-danger" title="Remove">Remove</a>';

            let tableBody = document.querySelector('#feeTableBody');
            tableBody.appendChild(newRow);
        }
    }
});

// Remove Row
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('remove_row')) {
        var row = event.target.closest('tr');
        var tableBody = document.querySelector('#feeTableBody');

        // Ensure at least one row remains
        if (tableBody.querySelectorAll('tr').length > 1) {
            row.remove();
        } else {
            alert('At least one fee row must remain.');
        }
    }
});
</script>