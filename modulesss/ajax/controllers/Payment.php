<?php
class Payment extends Ajax_Controller
{
    function student_payment_setting()
    {
        $this->response('data', $this->student_model->fix_payment_settings([
            'onlyFor' => $this->post('type')
        ],true)->result());
    }
    function save_student_payment_setting()
    {
        // $this->response('data',$this->post());
        foreach ($this->post('amount') as $id => $amount) {
            $this->db->where('id', $id)->update('student_fix_payment', [
                'amount' => $amount,
                'status' => isset($_POST['status'][$id]) ? 1 : 0
            ]);
        }
        $this->response('status', true);
        $this->response('html', 'Student Fix Payment Update Successfully..');
    }
    function collect_fee()
    {
        if (sizeof($this->post('fees_type'))) {
            $fees_type              = $this->post('fees_type');
            $fees_period            = $this->post('fees_period');
            $fees_payment_type      = $this->post('fees_payment_type');
            $fees_amount            = $this->post('fees_amount');
            $fees_paid              = $this->post('fees_paid');
            $fees_description       = $this->post('fees_description');
            foreach ($fees_type as $key => $value) {
                $feeData = [];
                if(trim($value) != ""){
                    $pending_amount = intval($fees_amount[$key]) - intval($fees_paid[$key]);
                    $feeData = [
                        'student_id' =>  $this->post('student_id'),
                        'roll_no' => $this->post('roll_no'),
                        'course_id' => $this->post('course_id'),
                        'center_id' => $this->post('center_id'),
                        'fees_type' => strtolower(str_replace(' ', '_', $fees_type[$key])),
                        'fees_period' => $fees_period[$key],
                        'payment_type' => $fees_payment_type[$key],
                        'total_amount' => $fees_amount[$key],
                        'paid_amount' => $fees_paid[$key],
                        'pending_amount' => $pending_amount
                    ];
                    $this->db->insert('student_fees', $feeData);
                    $trans_id = $this->db->insert_id();

                    $payment_id = date('hdYHis').rand(100,999);

                    $transData = [
                        'student_fees_id' =>  $trans_id,
                        'payment_id' => $payment_id,
                        'payment_date' => date('d-m-Y'),
                        'amount' => $fees_paid[$key],
                        'description' => $fees_description[$key],
                        'added_by' => $this->ki_theme->loginUser()
                    ];

                    $this->db->insert('student_fees_trans', $transData);
                }
            }
        }

        $this->response('status', true);
        $this->response('html', 'Fees Submitted Successfully...');
    }
    function student_fee_structure()
    {
        // sleep(4);
        // $this->set_data('student', $this->student_model->)
        $admissionFee = $this->student_model->fix_payment_settings('admission_fees')->row('amount');
        $exam_fee = $this->student_model->fix_payment_settings('exam_fee')->row('amount');
        $this->set_data('admission_fee', $admissionFee);
        $this->set_data('exam_fee', $exam_fee);
        $this->set_data($this->post());
        $this->set_data($this->student_model->get_student_via_id($this->post('student_id'))->row_array());
        $this->response('status', true);
        $this->response('html', $this->template('student-fees-structure'));
    }
}
