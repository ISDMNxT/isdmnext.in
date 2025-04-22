<?php
$where          = ['course_id' => $course_id, 'student_id' => $student_id, 'roll_no' => $roll_no];
//$getTrans       = $this->student_model->get_fee_transcations($where);

$listFeeRecords = $this->db->select('        
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
                    ->where('s.student_id', $student_id)->where('s.course_id', $course_id)->where('s.roll_no', $roll_no)->get();
//$data = $get->result_array();

$num       = $listFeeRecords->num_rows();

//$listFeeRecords = $this->student_model->get_switch('fetch_fee_transactions_group_by', ['student_id' => $student_id]);
//$listFeeRecords->num_rows();
$isCenterOrAdmin = $this->student_model->isAdminOrCenter();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fee Record</h3>
            </div>
            <?php if ($num): ?>
            <div class="card-body p-2">
                <table class="table table-bordered" id="fee-record">
                    <thead>
                        <tr>
                            <th colspan="7" class="text-center fs-bolder fs-2">{student_name}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Fee Type</th>
                            <th>Paid Amount</th>
                            <th>Payment Type</th>
                            <th>
                                <?= $isCenterOrAdmin ? 'Action' : 'Receipt' ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($listFeeRecords->result() as $record) {
                            echo '<tr>
                                <td>' . $i++ . '</td>
                                <td>' . $record->payment_date . '</td>
                                <td>' . $record->payment_id . '</td>
                                <td>' . ucwords(str_replace("_", " ", $record->fees_type)) .'
                                </td>
                                <td class="fs-4 fw-bolder">' . $record->amount .'
                                </td>
                                <td class="text-capitalize">' . $record->payment_type . '</td>
                                <td><div class="btn-group">
                                ';
                            if ($isCenterOrAdmin) {
                                echo $this->ki_theme
                                    ->with_icon('pencil')
                                    ->with_pulse('primary')
                                    ->outline_dashed_style('primary')
                                    ->set_attribute([
                                        'data-fee_id' => $record->trans_id,
                                        'class' => 'edit-installment-record'
                                    ])
                                    ->button('Edit');
                            }
                            // generate receipt
                            echo $this->ki_theme
                                ->with_icon('file')
                                ->with_pulse('success')
                                ->outline_dashed_style('success')
                                ->set_attribute([
                                    'data-fee_id' => $record->trans_id,
                                    'class' => 'print-receipt'
                                ])
                                ->button('Receipt');
                            if ($isCenterOrAdmin) {
                                echo $this->ki_theme
                                    ->with_icon('trash')
                                    ->with_pulse('danger')
                                    ->outline_dashed_style('danger')
                                    ->set_attribute([
                                        'data-fee_id' => $record->trans_id,
                                        'class' => 'delete-installment-record'
                                    ])
                                    ->button('Delete');
                            }
                            echo '
                                
                                </div></td>                            
                            </tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold fs-6">
                            <th colspan="4" class="text-nowrap text-end">Total</th>
                            <th colspan="3" class="text-success fs-3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if ($isCenterOrAdmin) {
    // 
    ?>
    <script id="formTemplate" type="text/x-handlebars-template">
                        <input type="hidden" name="id" value="{{id}}">
    
                        <div class="form-group mb-4">
                            <label class="form-label">Date</label>
                            <input type="text" name="date" class="form-control" placeholder="Enter Roll Number Prefix" value="{{date}}">
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Date</label>
                            <input type="text" name="date" class="form-control" placeholder="Enter Roll Number Prefix" value="{{date}}">
                        </div>
                    </script>
    <?php
}

?>