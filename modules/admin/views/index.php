<?php
$ci = &get_instance();
$loginTypes = $ci->session->userdata();
if ($this->center_model->isCenter() && table_exists('manual_notifications')) { ?>
    <div class="row">
        <?php 
            $receiver_user  = 'center';
            $receiver_id    = $this->center_model->loginId();
            if($this->session->userdata('admin_type') == 'center' && !empty($this->session->userdata('staff_id'))){
                $receiver_user = 'staff';
                $receiver_id    = $this->session->userdata('staff_id');
            }
            $cnotifications = $this->ki_theme->get_manual_notification(['receiver_id'=>$receiver_id,'receiver_user'=>$receiver_user]);
            foreach($cnotifications as $key => $notiArray){
        ?>    
                <!-- Notification Item -->
                <div id="dash_noti_<?= $notiArray['id'] ?>" class="alert alert-<?= $notiArray['notify_type'] ?> d-flex align-items-center justify-content-between" role="alert">
                    <div>
                        <strong><?= ucfirst($notiArray['notify_type']) ?>!</strong> <?= $notiArray['title'] ?></span>
                    </div>
                    <div class="d-flex align-items-center" data-id="<?= $notiArray['id'] ?>" data-user="<?= $receiver_user ?>" data-type="<?= $notiArray['notify_type'] ?>">
                        <!-- View Button -->
                        <button type="button" class="btn btn-link p-0 me-3 view-notification-dash" aria-label="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Close Button -->
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
        <?php } ?>
    </div>
<?php } ?>

<style>

    .dashboard-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      text-align: center;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card i {
      font-size: 40px;
      color: #007bff;
      margin-bottom: 10px;
    }

    .card h2 {
      margin: 0;
      font-size: 40px;
      color: #333;
    }

    .card p {
      margin: 10px 0 0;
      font-size: 18px;
      color: #666;
    }

    .dashboard-title {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      margin: 20px 0;
      color: #444;
    }

    .footer {
      text-align: center;
      padding: 10px;
      margin-top: 20px;
      font-size: 14px;
      color: #aaa;
    }

    @media (max-width: 600px) {
      .card {
        padding: 15px;
      }

      .card i {
        font-size: 30px;
      }

      .card h2 {
        font-size: 30px;
      }

      .card p {
        font-size: 16px;
      }
    }
</style>

<?php if ($this->center_model->isAdmin()) { ?>
<div class="dashboard-container">
    <div class="card">
      <i class="fas fa-school"></i>
      <h2><?= $this->center_model->get_center()->num_rows() ?></h2>
      <p>Total Institutes</p>
    </div>
    <div class="card">
      <i class="fas fa-users"></i>
      <h2><?= $this->db->get('students')->num_rows() ?></h2>
      <p>Total Students Enrolled</p>
    </div>
    <div class="card">
      <i class="fas fa-user-graduate"></i>
      <h2><?= $this->student_model->get_switch('all', [
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Active Students</p>
    </div>
    <div class="card">
      <i class="fas fa-graduation-cap"></i>
      <h2><?= $this->student_model->get_switch('passout',[
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Passout Students</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?= $this->db->get('students_enquiry')->num_rows() ?></h2>
      <p>Total Enquiries</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
        <?php $tt = $this->db->select('SUM(amount) as total_wallet')->from('wallet_transcations')->where('wallet_status','credit')->where('status',1)->get()->result_array(); $twall = 0; if(!empty($tt[0]['total_wallet'])) { $twall = $tt[0]['total_wallet']; } ?>
        <span data-kt-countup="true" data-kt-countup-value="<?= $twall ?>" data-kt-countup-prefix='{inr}'>0</span>
        </h2>
      <p>Total Wallet Recharges</p>
    </div>
    <div class="card">
      <i class="fas fa-book"></i>
      <h2><?= $this->SiteModel->ttl_courses() ?></h2>
      <p>Total Courses</p>
    </div>
    <div class="card">
      <i class="fas fa-file-alt"></i>
      <h2><?= $this->db->get('exams_master')->num_rows() ?></h2>
      <p>Total Exam Requests</p>
    </div>
    <div class="card">
      <i class="fas fa-check-circle"></i>
      <h2><?= $this->db->where('is_admin_approved',2)->where('isDeleted',0)->get('exams_master')->num_rows() ?></h2>
      <p>Total Exams Conducted</p>
    </div>
</div>
<?php } ?>

<?php if ($this->center_model->isCenter() && empty($loginTypes['staff_id'])) { ?> 
<div class="dashboard-container">
    <div class="card">
      <i class="fas fa-users"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->get('students')->num_rows() ?></h2>
      <p>Total Students Enrolled</p>
    </div>
    <div class="card">
      <i class="fas fa-user-graduate"></i>
      <h2>
        <?= $this->student_model->get_switch('all', [
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Active Students</p>
    </div>
    <div class="card">
      <i class="fas fa-graduation-cap"></i>
      <h2><?= $this->student_model->get_switch('passout',[
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Passout Students</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->get('students_enquiry')->num_rows() ?></h2>
      <p>Total Enquiries</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?php $totalE = $this->db->where('center_id',$this->center_model->loginId())->get('students_enquiry')->num_rows(); $total = $this->db->get('students_enquiry')->num_rows(); if(!empty($total)) { $totalE = intval($totalE) * 100; $tt = (intval($totalE)/intval($total)); echo $tt; } else { echo 0; } ?></h2>
      <p>Conversion Ratio </p>
    </div>

    <div class="card">
      <i class="fas fa-book"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->group_by('batch_id')->get('students')->num_rows() ?></h2>
      <p>Active Batches </p>
    </div>
    <div class="card">
      <i class="fas fa-book"></i>
      <h2>
          <?php 
          $ss = $this->db->select('s.center_id,s.batch_id, COUNT(s.id) as total_student, COUNT(sce.id) as total_certificate')
                ->from('students as s')
                ->join('student_certificates as sce', 'sce.student_id = s.id AND sce.course_id = s.course_id','left')
                ->where('s.center_id', $this->center_model->loginId())
                ->where('s.admission_status', 1)->where('s.status', 1)->group_by('s.batch_id')->get()->result_array();
            $completed_batch = 0;
            foreach($ss as $key => $val){
                if($val['total_student'] == $val['total_certificate']){
                    $completed_batch++;
                }
            }
            echo $completed_batch;
          ?>
      </h2>
      <p>Completed Batches</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
      <?php 
      $total_colletion = $this->db->select('SUM(sf.paid_amount) as paid_amount')
            ->from('students as s')
            ->join('student_fees as sf', 'sf.student_id = s.id AND sf.center_id = s.center_id AND sf.course_id = s.course_id AND sf.roll_no = s.roll_no', 'left')
            ->where('s.center_id', $this->center_model->loginId())
            ->where('s.admission_status', 1)->where('s.status', 1)->get()->result_array();

      ?>
      {inr}<?= !empty($total_colletion[0]['paid_amount']) ? $total_colletion[0]['paid_amount'] : 0; ?>
      </h2>
      <p>Fees Collection</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
      <?php 
        $fee_pending = $this->db->select('SUM(sf.pending_amount) as pending_amount')
            ->from('students as s')
            ->join('student_fees as sf', 'sf.student_id = s.id AND sf.center_id = s.center_id AND sf.course_id = s.course_id AND sf.roll_no = s.roll_no', 'left')
            ->where('s.center_id', $this->center_model->loginId())
            ->where('s.admission_status', 1)->where('s.status', 1)->get()->result_array();

      ?>
      {inr}<?= !empty($fee_pending[0]['pending_amount']) ? $fee_pending[0]['pending_amount'] : 0; ?>
      </h2>
      <p>Fees Pending</p>
    </div>
    <div class="card">
      <i class="fas fa-check-circle"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->get('exams_master')->num_rows() ?></h2>
      <p>Exam Conducted</p>
    </div>
    <div class="card">
      <i class="fas fa-file-alt"></i>
      <h2><?= $this->db->where('is_admin_approved',1)->where('isDeleted',0)->get('exams_master')->num_rows() ?></h2>
      <p>Exams Pending</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
        <span data-kt-countup="true" data-kt-countup-value="<?= $center_data['wallet'] ?>"
                                            data-kt-countup-prefix='{inr}'>0</span>
        </h2>
      <p>Wallet Balance</p>
    </div>
</div>
<?php } ?>

<?php if ($this->center_model->isCenter() && !empty($loginTypes['staff_id'])) { ?> 
<div class="dashboard-container">
    <div class="card">
      <i class="fas fa-users"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->get('students')->num_rows() ?></h2>
      <p>Total Students Enrolled</p>
    </div>
    <div class="card">
      <i class="fas fa-user-graduate"></i>
      <h2>
        <?= $this->student_model->get_switch('all', [
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Active Students</p>
    </div>
    <div class="card">
      <i class="fas fa-graduation-cap"></i>
      <h2><?= $this->student_model->get_switch('passout',[
                            'without_admission_status' => true,
                            'admission_status' => 1
                        ])->num_rows() ?></h2>
      <p>Total Passout Students</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->get('students_enquiry')->num_rows() ?></h2>
      <p>Total Enquiries</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?php $totalE = $this->db->where('center_id',$this->center_model->loginId())->get('students_enquiry')->num_rows(); $total = $this->db->get('students_enquiry')->num_rows(); if(!empty($total)) { $totalE = intval($totalE) * 100; $tt = (intval($totalE)/intval($total)); echo $tt; } else { echo 0; } ?></h2>
      <p>Conversion Ratio </p>
    </div>

    <div class="card">
      <i class="fas fa-book"></i>
      <h2><?= $this->db->where('center_id',$this->center_model->loginId())->group_by('batch_id')->get('students')->num_rows() ?></h2>
      <p>Active Batches </p>
    </div>
    <div class="card">
      <i class="fas fa-book"></i>
      <h2>
          <?php 
          $ss = $this->db->select('s.center_id,s.batch_id, COUNT(s.id) as total_student, COUNT(sce.id) as total_certificate')
                ->from('students as s')
                ->join('student_certificates as sce', 'sce.student_id = s.id AND sce.course_id = s.course_id','left')
                ->where('s.center_id', $this->center_model->loginId())
                ->where('s.admission_status', 1)->where('s.status', 1)->group_by('s.batch_id')->get()->result_array();
            $completed_batch = 0;
            foreach($ss as $key => $val){
                if($val['total_student'] == $val['total_certificate']){
                    $completed_batch++;
                }
            }
            echo $completed_batch;
          ?>
      </h2>
      <p>Completed Batches</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
      <?php
      $this->db->select('cp.staff_id,c.institute_name as center_name, cc.name as trainer, cc.email as trainer_email, sm.subject_name, cp.title as class_name, cp.type as class_type, clr.rating')
            ->from('class_plan_rating as clr')
            ->join('class_plan as cp', 'cp.id = clr.class_plan_id')
            ->join('centers as c', "c.id = cp.center_id AND c.type = 'center'", 'left')
            ->join('centers as cc', "cc.id = cp.staff_id AND cc.type = 'staff' AND cc.role = 'Trainer'", 'left')
            ->join('subject_master as sm', "sm.id = cp.subject_id", 'left');
            
            $this->db->where('cp.staff_id', $loginTypes['staff_id']);
            
        $sdata = $this->db->get()->result();

        $tdata = [];
        foreach($sdata as $key => $row){
            $rating = [
                    'subject_name' => $row->subject_name,
                    'class_name' => $row->class_name,
                    'class_type' => $row->class_type,
                    'rating' => $row->rating,
                ];
            $tdata[$row->staff_id]['id'] =  $row->staff_id;
            $tdata[$row->staff_id]['center_name'] =  $row->center_name;
            $tdata[$row->staff_id]['trainer'] =  $row->trainer;
            $tdata[$row->staff_id]['trainer_email'] =  $row->trainer_email;
            $tdata[$row->staff_id]['rating_arr'][] =  $rating;
        }

        $data = [];
        $rating = 0;
        foreach($tdata as $key => $value){
            $total_count = count($value['rating_arr']);
            $total_rating = 0;
            foreach($value['rating_arr'] as $k => $v){
                $total_rating = intval($total_rating) + intval($v['rating']);
            }

            $rating = 0;
            if(!empty($total_rating)){
                $rating = intval($total_rating)/intval($total_count);
            }
            unset($value['rating_arr']);
            $value['rating'] = $rating." out of 5";
            $rating = $rating." out of 5";
            $data[] = $value;
        }
        echo $rating;
      ?>
      </h2>
      <p>Rating</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
      <?php
        $this->db->select('
                s.id,
                s.title,
                s.description,
                s.type,
                s.plan_date,
                s.notes'
        )
        ->from('class_plan as s')
        ->where('s.staff_id', $loginTypes['staff_id'])
        ->where('s.isDeleted', 0);

        

        $list = $this->db->get();
        
        echo $list->num_rows();
      ?>
      </h2>
      <p>No. of Class Conducted</p>
    </div>
    <div class="card">
      <i class="fas fa-check-circle"></i>
      <h2><?= $this->db->where('is_admin_approved',2)->get('exams_master')->num_rows() ?></h2>
      <p>Exam Conducted</p>
    </div>
    <div class="card">
      <i class="fas fa-file-alt"></i>
      <h2><?= $this->db->where('is_admin_approved',1)->where('isDeleted',0)->get('exams_master')->num_rows() ?></h2>
      <p>Exams Pending</p>
    </div>
</div>
<?php } ?>

<?php if ($this->center_model->isMaster()) { ?> 
<div class="dashboard-container">
    <div class="card">
      <i class="fas fa-users"></i>
      <h2><?= count($this->session->userdata('permission')) ?></h2>
      <p>Total Institute</p>
    </div>
    <div class="card">
      <i class="fas fa-user-graduate"></i>
      <h2>
        <?= $this->student_model->get_switch('all_student', [
                            'without_admission_status' => true,
                            'admission_status' => 1,
                            'all_c' => $this->session->userdata('permission')
                        ])->num_rows() ?></h2>
      <p>Total Students Enrolled</p>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h2>
        <?php 
          $master = $this->db->where('id',$this->center_model->loginId())->get('centers')->result_array();
        ?>
      {inr}<?= !empty($master[0]['wallet']) ? $master[0]['wallet'] : 0; ?>
      </h2>
      <p>Total Earning</p>
    </div>
    <div class="card">
      <i class="fas fa-envelope"></i>
      <h2><?= $this->db->where_in('center_id',$this->session->userdata('permission'))->get('students_enquiry')->num_rows() ?></h2>
      <p>Total Enquiries</p>
    </div>
    <div class="card">
      <i class="fas fa-book"></i>
      <h2><?= $this->SiteModel->ttl_courses() ?></h2>
      <p>Total Courses</p>
    </div>
    <div class="card">
      <i class="fas fa-check-circle"></i>
      <h2><?= $this->db->where('is_admin_approved',2)->where('isDeleted',0)->where_in('center_id',$this->session->userdata('permission'))->get('exams_master')->num_rows() ?></h2>
      <p>Total Exams</p>
    </div>
</div>
<?php } ?>
