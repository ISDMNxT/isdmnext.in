<?php
$where          = ['course_id' => $course_id, 'student_id' => $student_id, 'roll_no' => $roll_no];
$center_course  = $this->center_model->get_assign_courses($institute_id, ['course_id' => $course_id]);
$course_fees    = 0;
$this->ki_theme->check_it_referral_stduent($student_id);
?>
<style>
    input.form-control.form-control-sm:read-only {
        cursor: not-allowed;
    }

    .form-check-input:disabled~.form-check-label,
    .form-check-input[disabled]~.form-check-label {
        opacity: 1;
    }

    tr th label.form-check-label {
        color: black
    }

    [data-bs-theme=dark] tr th label.form-check-label {
        color: white
    }

    tr.pending {
        color: white;
        background-color: #981e1e;
    }

    tr.success {
        background-color: #032c12;
        color: white
    }

    tr.pending th,
    tr.pending td,
    tr.pending label.form-check-label,
    tr.success th,
    tr.success td,
    tr.success label.form-check-label {
        color: white
    }
</style>
<?php
/*$getTrans       = $this->student_model->get_fee_transcations($where);
if ($getTrans->num_rows()) {
foreach ($getTrans->result() as $row) {
echo "<pre>";
print_r($row);
}
}*/
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-body p-0 mb-5">
            <label class="d-flex flex-stack cursor-pointer p-5">
                <!--begin:Label-->
                <span class="d-flex align-items-center me-2">
                    <!--begin:Icon-->
                    <span class="symbol symbol-50px me-6">
                        <span class="symbol-label bg-light-warning">
                            <span class="fs-2x text-warning">
                                <?= get_first_latter($course_name) ?>
                            </span>
                        </span>
                    </span>
                    <!--end:Icon-->
                    <!--begin:Info-->
                    <span class="d-flex flex-column">
                        <span class="fw-bold fs-6 text-capitalize course-name">
                            <?= $course_name ?>
                        </span>
                        <span class="fs-7 text-muted text-capitalize course-duration">
                            <?= humnize_duration($duration, $duration_type, false) ?>
                        </span>
                    </span>
                    <!--end:Info-->
                    <!-- <span class="d-flex flex-column text-end w-100px">
                        <span class="fw-bold fs-3">
                            <?= $course_fees ?> {inr}
                        </span>
                    </span> -->
                </span>
                <!--end:Label-->
            </label>
        </div>
    </div>
    <div class="col-md-12">
        <form action="" id="my-fee-form">
            <!-- <input type="hidden" name="course_duration" value="{duration}">
                    <input type="hidden" name="course_duration_type" value="{duration_type}"> -->
            <input type="hidden" name="student_id" value="{student_id}">
            <input type="hidden" name="roll_no" value="{roll_no}">
            <input type="hidden" name="course_id" value="{course_id}">
            <input type="hidden" name="center_id" value="{institute_id}">
            <div class="card border-danger myfee" id="myfee-form" data-kt-sticky="true"
                data-kt-sticky-name="docs-sticky-summary" data-kt-sticky-offset="{default: false, xl: '200px'}"
                data-kt-sticky-reverse="true" data-kt-sticky-width="{lg: '250px', xl: '600px'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
                data-kt-sticky-zindex="95">
                <div class="card-header bg-danger">
                    <h3 class="card-title ">Student Fees Details</h3>
                    <div class="card-toolbar"></div>
                </div>
                <div class="card-body">
                    <table class="temp-list table table-striped table-bordered border-warning">
                        <thead>
                            <tr class="bg-warning p-1 fs-1 fw-bold">
                                <th colspan="6" class="text-center text-black "><i
                                        class="ki-outline ki-information  fs-1 text-black fw-bold"></i>
                                    Fees Details </th>
                            </tr>
                            <tr>
                                <th>Fee Type</th>
                                <th>Payment Type</th>
                                <th width="10%">Fee {inr}</th>
                                <th width="10%">Paid Fee {inr}</th>
                                <th width="10%">Pending Fee {inr}</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $getTrans       = $this->student_model->fetch_fee_transactions_all($where);
                           // echo "<pre>";
                           // print_r($getTrans->result());
                            if ($getTrans->num_rows()) {
                                foreach ($getTrans->result() as $row) { 
                            ?>
                            <tr>
                                <td><?php echo ucwords(str_replace("_", " ", $row->fees_type)); ?></td>
                                <td><?php echo ucwords(str_replace("_", " ", $row->payment_type)); ?></td>
                                <td><?php echo $row->total_amount; ?></td>
                                <td><?php echo $row->paid_amount; ?></td>
                                <td><?php echo $row->pending_amount; ?></td>
                                <td>
                                    <?php 
                                    echo '<div class="btn-group">'; 
                                    echo $this->ki_theme
                                            ->with_icon('pencil')
                                            ->with_pulse('primary')
                                            ->outline_dashed_style('primary')
                                            ->set_attribute([
                                                'data-fee_id' => $row->id,
                                                'class' => 'edit-fee-record'
                                            ])
                                            ->button('Edit');

                                    echo $this->ki_theme
                                            ->with_icon('trash')
                                            ->with_pulse('danger')
                                            ->outline_dashed_style('danger')
                                            ->set_attribute([
                                                'data-fee_id' => $row->id,
                                                'class' => 'delete-fee-record'
                                            ])
                                            ->button('Delete');

                                    if(intval($row->pending_amount) > 0) {
                                    
                                        echo $this->ki_theme
                                            ->with_icon('file')
                                            ->with_pulse('success')
                                            ->outline_dashed_style('success')
                                            ->set_attribute([
                                                'data-main_fee_id' => $row->id,
                                                'class' => 'add-installment-record'
                                            ])
                                            ->button('Add Installment');
                                    }
                                    echo '</div>';
                                    ?>
                               
                                </td>
                            </tr>
                            <?php 
                            } 
                                }
                            ?>
                        </tbody>
                    </table>
                    
                    <table class="temp-list table table-striped table-bordered border-warning">
                        <tfoot>
                            <tr class="bg-warning p-1 fs-1 fw-bold">
                                <th colspan="4" class="text-center text-black "><i
                                        class="ki-outline ki-information  fs-1 text-black fw-bold"></i>
                                    Student Details</th>
                            </tr>
                            <?php
                            $submissionfees = $this->student_model->get_fee_transcations_ttl($where)
                                ?>
                            <tr>
                                <th colspan="2">Total Fee</th>
                                <td>
                                    {inr} <?= $submissionfees['ttl_fee'] ?>
                                </td>
                                <td class="w-50px" rowspan="2">
                                    <a href="{base_url}student/profile/{student_id}/fee-record"
                                        class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i> Records
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Submitted Fee</th>
                                <td colspan="1">
                                    {inr} <?= $submissionfees['ttl_paid'] ?> 
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Pending Fee</th>
                                <td colspan="1">
                                    {inr} <?= $submissionfees['ttl_pending'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Roll No. With Name</th>
                                <td><label class="badge badge-info">{roll_no}</label></td>
                                <td class="text-capitalize" colspan="2"> <b>{student_name}</b> </td>
                            </tr>
                            <tr>
                                <th colspan="2">Center Name</th>
                                <td class="text-capitalize" colspan="2"><b>{center_name}</b></td>
                            </tr>
                            <tr>
                                <th colspan="2">Batch</th>
                                <td class="text-capitalize" colspan="2"><b>{batch_name}</b></td>
                            </tr>
                        </tfoot>
                    </table>

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
                                    <input type="text" class="form-control form-control-sm fee-type" name="fees_type[]" placeholder="Fee Type" autocomplete="off">
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
                <div class="card-footer">
                    {save_button}
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